<?php

namespace App\Http\Controllers\cliente;

use App\Models\Freight;
use App\Models\Shipment;
use App\Models\Driver;
use App\Models\FreightStatus;
use App\Models\FreightsDriver;
use App\Models\Company;
use App\Models\FreightDriver;
use App\Models\Truck;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;

class CliFreightController extends Controller
{

    public function chartData()
    {
        return response()->json([
            'status_chart' => $this->getStatusChartData(),
            'monthly_chart' => $this->getMonthlyChartData()
        ]);
    }


    public function getChartData(Request $request)
    {
        return response()->json([
            'status_chart' => $this->getStatusChartData(),
            'monthly_chart' => $this->getMonthlyChartData()
        ]);
    }

    public function dashboard(Request $request)
    {
        $statuses = FreightStatus::all();
        
        if ($request->ajax()) {
            if ($request->has('charts_only')) {
                return $this->getChartData($request);
            }
            
            $query = $this->buildDashboardQuery($request);
            
            return datatables()->eloquent($query)
                ->with([
                    'summary' => $this->getDashboardSummary(),
                    'statuses' => $statuses
                ])
                ->addColumn('truck_type_name', function($freight) {
                    return $freight->truck_type_name;
                })
                ->rawColumns(['action', 'freight_status'])
                ->toJson();
        }
        
        return view('freights.cliente.dashboard', [
            'statuses' => $statuses,
            'summary' => $this->getDashboardSummary(),
            'charts' => $this->getDashboardCharts()
        ]);
    }

    protected function buildDashboardQuery(Request $request)
    {
        $query = Freight::with(['freightStatus'])
            ->select('freights.*')
            ->where('freights.company_id', auth()->id());
        
        if ($request->has('status_filter') && $request->status_filter !== 'all') {
            $query->where('status_id', $request->status_filter);
        }
        
        if ($request->has('map_filter') && $request->map_filter !== 'all') {
            $query->where('status_id', $this->getStatusIdFromFilter($request->map_filter));
        }
        
        return $query;
    }

    protected function getStatusIdFromFilter($filter)
    {
        return match($filter) {
            'pending' => 1,
            'in_progress' => 2,
            'completed' => 3,
            default => null
        };
    }

    protected function getDashboardSummary()
    {
        return [
            'total_freights' => Freight::where('freights.company_id', auth()->id())->count(),
            'in_progress' => Freight::where('status_id', 2)
            ->where('freights.company_id', auth()->id())->
             count(),
            'pending' => Freight::where('status_id', 1)
            ->where('freights.company_id', auth()->id())
            ->count(),
            'total_value' => Freight::where('freights.company_id', auth()->id())
            ->sum('freight_value')
        ];
    }

    protected function getDashboardCharts()
    {
        return [
            'status_chart' => $this->getStatusChartData(),
            'monthly_chart' => $this->getMonthlyChartData()
        ];
    }

    protected function getStatusChartData()
    {
        $data = Freight::join('freight_statuses', 'freights.status_id', '=', 'freight_statuses.id')
            ->where('freights.company_id', auth()->id())
            ->select('freight_statuses.name as status', DB::raw('count(*) as total'))
            ->groupBy('freight_statuses.name')
            ->get();
        return [
            'labels' => $data->pluck('status'),
            'data' => $data->pluck('total')
        ];
    }

    protected function getMonthlyChartData()
    {
        $currentYear = date('Y');
        $monthlyData = Freight::where('freights.company_id', auth()->id())
                ->select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('COUNT(*) as total')
            )
            ->whereYear('created_at', $currentYear)
            ->groupBy('month')
            ->get()
            ->keyBy('month');
        
        return [
            'data' => collect(range(1, 12))->map(fn($month) => $monthlyData[$month]->total ?? 0)
        ];
    }

    
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->getDataTable($request);
        }
        
        $statuses = FreightStatus::all();
        return view('freights.cliente.index', compact('statuses'));
    }

    // In your controller
    public function getDriverTruckDetails($freightsDriver_id)
    {
        $freightDriver = FreightsDriver::findOrFail($freightsDriver_id);
        $driver = Driver::findOrFail($freightDriver->driver_id);
        $truck = Truck::findOrFail($freightDriver->truck_id);

        return response()->json([
            'driver' => $driver->append([
                'driver_license_front_url',
                'driver_license_back_url',
                'face_photo_url'
            ]),
            'truck' => $truck->append([
                'front_photo_full_url',
                'rear_photo_full_url',
                'left_side_photo_full_url',
                'right_side_photo_full_url',
                'crv_photo_full_url',
                'crlv_photo_full_url'
            ]),
            'implements' => $truck->implements->map(function($implement) {
                $implement->photo_url = $implement->photo 
                    ? Storage::disk('s3')->url($implement->photo) 
                    : null;
                return $implement;
            })
        ]);
    }


public function updateStatus(FreightsDriver $freightsDriver, Request $request)
{
    $validated = $request->validate([
        'status_id' => 'required|integer|exists:freight_statuses,id'
    ]);

    $freightsDriver->status_id = $validated['status_id'];

    // Se o novo status for 5, chama a API externa
    if ($freightsDriver->status_id == 5) {
    
        try {
            $response = Http::post('https://qpo5gxrs74.execute-api.us-east-1.amazonaws.com/teste', [
                'freights_driver_id' => $freightsDriver->id
            ]);

            // Verifica se a requisição foi bem-sucedida (opcional)
            if ($response->successful()) {
                // Faça algo com a resposta, se necessário
                // $responseData = $response->json();
            } else {
                // Log de erro, se a API retornar status 4xx/5xx
                \Log::error('Falha na chamada da API: ' . $response->status());
            }
        } catch (\Exception $e) {
            // Log em caso de erro de conexão, timeout, etc.
            \Log::error('Erro ao chamar API externa: ' . $e->getMessage());
        }
    }

    if ($freightsDriver->status_id == 10) {
    
        try {
            $response = Http::post('https://8t163e6pei.execute-api.us-east-1.amazonaws.com/teste', [
                'freights_driver_id' => $freightsDriver->id
            ]);

            // Verifica se a requisição foi bem-sucedida (opcional)
            if ($response->successful()) {
                // Faça algo com a resposta, se necessário
                // $responseData = $response->json();
            } else {
                // Log de erro, se a API retornar status 4xx/5xx
                \Log::error('Falha na chamada da API: ' . $response->status());
            }
        } catch (\Exception $e) {
            // Log em caso de erro de conexão, timeout, etc.
            \Log::error('Erro ao chamar API externa: ' . $e->getMessage());
        }
    }

    return response()->json([
        'success' => true,
        'message' => 'Status atualizado com sucesso!'
    ]);
}

    

    public function getDataTable(Request $request)
    {

          // dd('ta aq');

        $query = Freight::with(['freightStatus',  'shipment', 'charge', 'freightsDriver.driver'])
        ->where('freights.company_id', auth()->id()) // Filtra pelo cliente logado        
        ->select('freights.*');
    
        // Aplica os filtros antes de passar para o DataTables
        if ($request->status_filter) {
            $query->where('status_id', $request->status_filter);
        }
    
        if ($request->driver_filter) {
            $query->where('driver_id', $request->driver_filter);
        }
    
        // Filtros de data
        if ($request->start_date) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
    
        if ($request->end_date) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }
    
        // Ordenação padrão
        $query->orderBy('id', 'desc');
    
        return DataTables::of($query)
          
            ->addColumn('driver_name', function($freight) {
                if (!$freight->freightsDriver) {
                    return '<span class="badge bg-info text-muted">Não atribuído</span>';
                }
                
                return '
                <div class="d-flex flex-column">
                    <span class="badge bg-success mb-1">' . e($freight->freightsDriver->driver->name) . '</span>
                    <div class="d-flex gap-1 align-self-start">' .
                        ($freight->freightsDriver->status_id == 9 ? '
                            <button onclick="aprovar(' . $freight->freightsDriver->id . ', 5); return false;" 
                                    class="btn btn-sm btn-success">
                                Aprovar
                            </button>
                            <button onclick="reprovar(' . $freight->freightsDriver->id . ', 10); return false;" 
                                    class="btn btn-sm btn-danger">
                                Recusar
                            </button>'
                            : ''
                        ) . '
                        <a href="#" 
                            onclick="detailsDriverTruck(' . $freight->freightsDriver->id . '); return false;" 
                            class="btn btn-sm btn-primary">
                            Ver Detalhes
                        </a>
                    </div>
                </div>';
            })
            ->addColumn('status_badge', function($freight) {
                $status = $freight->freightStatus;
                if (!$status) return '<span class="badge bg-secondary">N/A</span>';
                
                $badgeClass = [
                    '1' => 'bg-secondary',
                    '3' => 'bg-warning',
                    '4' => 'bg-info',
                    '5' => 'bg-secondary',
                    '6' => 'bg-primary',
                    '7' => 'bg-primary',
                    '8' => 'bg-info',
                    '9' => 'bg-success',
                ][$status->id] ?? 'bg-secondary';
                
                return '<span class="badge '.$badgeClass.'">'.$status->name.'</span>';
            })
            ->addColumn('formatted_value', function($freight) {
                return $freight->freight_value ? 'R$ '.number_format($freight->freight_value, 2, ',', '.') : 'N/A';
            })
            ->addColumn('payment_button', function($freight) {
                if (!$freight->charge) return '<span class="text-muted">N/A</span>';
                
                $status = strtolower($freight->charge->status ?? '');
                
                if ($status === 'paid') {
                    if ($freight->charge->receipt_url) {
                        return '
                            <a href="'.$freight->charge->receipt_url.'" class="btn btn-sm btn-info" target="_blank" title="Visualizar Recibo">
                                <i class="fas fa-file-invoice-dollar"></i> Recibo
                            </a>
                        ';
                    }
                    return '<span class="badge bg-success">Pago</span>';
                } else {
                    if ($freight->charge->charge_url) {
                        return '
                            <a href="'.$freight->charge->charge_url.'" class="btn btn-sm btn-success" target="_blank" title="Realizar Pagamento">
                                <i class="fas fa-credit-card"></i> Pagar
                            </a>
                        ';
                    }
                    return '<span class="badge bg-warning">Pendente</span>';
                }
            })
            ->addColumn('actions', function($freight) {
                return '
                    <div class="d-flex gap-2">
                        <button class="btn btn-sm btn-primary view-freight" data-id="'.$freight->id.'" title="Visualizar">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="btn btn-sm btn-danger delete-freight" data-id="'.$freight->id.'" title="Excluir">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                ';
            })
            ->rawColumns(['status_badge', 'actions', 'payment_button', 'driver_name'])
            ->make(true);
    }

    public function create()
    {
        $companies = Company::all();
        $drivers = Driver::all();
        $statuses = FreightStatus::all();
        $shipments = Shipment::whereDoesntHave('freight')->get();

        return view('freights.create', compact('companies', 'drivers', 'statuses', 'shipments'));  
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'shipment_id' => 'required|exists:shipments,id',
            'company_id' => 'required|exists:companies,id',
            'driver_id' => 'nullable|exists:drivers,id',
            'status_id' => 'required|exists:freight_statuses,id',
            'start_address' => 'required|string|max:500',
            'destination_address' => 'required|string|max:500',
            'current_position' => 'required|string|max:500',
            'start_lat' => 'required|numeric',
            'start_lng' => 'required|numeric',
            'destination_lat' => 'required|numeric',
            'destination_lng' => 'required|numeric',
            'current_lat' => 'required|numeric',
            'current_lng' => 'required|numeric',
            'truck_type' => 'required|string',
            'freight_value' => 'required|numeric|min:0',
            'distance' => 'required|string',
            'duration' => 'required|string',
            'driver_freight_value' => 'required|numeric|min:0'
        ]);

        try {
            $validated['company_id'] =  auth()->id();
            DB::beginTransaction();
            $freight = Freight::create($validated);
            DB::commit();
            $paymentData = $this->createAsaasPayment($freight);
            return  $paymentData ;

        } catch (\Exception $e) {
            DB::rollBack();
            if (isset($freight)) {
                $freight->delete();
            }
            Log::error('Freight creation failed: '.$e->getMessage());

            return back()->withInput()
                ->with('error', 'Erro ao criar frete: '.$e->getMessage());
        }
    }

    public function show($id)
    {

        $freight = Freight::with(['freightStatus', 'company', 'shipment', 'charge','history','FreightsDriver'])
        ->select('freights.*')->findorfail($id);
        // Determina a classe do badge baseado no status
        
    
        return view('freights.cliente.map', [
            'freight' => $freight,
            'statusBadgeClass' => '',
            'paymentBadgeClass' => ''
        ]);
    }

    public function showRoute(Freight $freight)
{
     $this->authorize('view', $freight);
   // $this->authorize('view', $freight);
    
    $statusBadgeClass = match($freight->freightStatus->name) {
        'Em Trânsito' => 'info',
        'Entregue' => 'success',
        'Cancelado' => 'danger',
        default => 'warning'
    };
    
    return view('freights.route', [
        'freight' => $freight,
        'statusBadgeClass' => $statusBadgeClass
    ]);
}

public function lastPosition(Freight $freight)
{
  //  $this->authorize('view', $freight);
    
    $lastLocation = $freight->history()
        ->orderBy('date', 'desc')
        ->orderBy('time', 'desc')
        ->first();
    
    return response()->json([
        'latitude' => $lastLocation->latitude ?? null,
        'longitude' => $lastLocation->longitude ?? null,
        'address' => $lastLocation->address ?? null,
        'date' => $lastLocation->date ?? null,
        'time' => $lastLocation->time ?? null,
        'status' => $lastLocation->status ?? null
    ]);
}

public function history(Freight $freight)
{
   // $this->authorize('view', $freight);
    
    $history = $freight->history()
        ->orderBy('date', 'desc')
        ->orderBy('time', 'desc')
        ->get()
        ->map(function($item) {
            return [
                'latitude' => $item->latitude,
                'longitude' => $item->longitude,
                'address' => $item->address,
                'date' => $item->date,
                'time' => $item->time,
                'status' => $item->status
            ];
        });
    
    return response()->json($history);
}

public function currentStatus(Freight $freight)
{
    $this->authorize('view', $freight);
    
    return response()->json([
        'status' => $freight->freightStatus->name
    ]);
}


    public function getLastPosition($id)
    {
        $freight = Freight::findOrFail($id);
        $lastPosition = $freight->history()
            ->orderBy('created_at', 'desc')
            ->first();
            
        return response()->json($lastPosition);
    }
    
    private function getStatusBadgeClass($statusName)
    {
        switch (strtolower($statusName)) {
            case 'em transito':
                return 'info';
            case 'entregue':
                return 'success';
            case 'cancelado':
                return 'danger';
            default:
                return 'warning';
        }
    }


       public function edit(Freight $freight)
    {
        $companies = Company::all();
        $drivers = Driver::all();
        $statuses = FreightStatus::all();
        $shipments = Shipment::whereDoesntHave('freight')
            ->orWhere('id', $freight->shipment_id)
            ->get();

        return view('freights.edit', compact('freight', 'companies', 'drivers', 'statuses', 'shipments',));
    }

    public function update(Request $request, Freight $freight)
    {
        $validated = $request->validate([
            'shipment_id' => 'required|exists:shipments,id',
            'company_id' => 'required|exists:companies,id',
            'driver_id' => 'nullable|exists:drivers,id',
            'status_id' => 'required|exists:freight_statuses,id',
            'start_address' => 'required|string|max:500',
            'destination_address' => 'required|string|max:500',
            'current_position' => 'required|string|max:500',
            'start_lat' => 'required|numeric',
            'start_lng' => 'required|numeric',
            'destination_lat' => 'required|numeric',
            'destination_lng' => 'required|numeric',
            'current_lat' => 'required|numeric',
            'current_lng' => 'required|numeric',
            'truck_type' => 'required|string|in:pequeno,medio,grande',
            'freight_value' => 'required|numeric|min:0',
            'distance' => 'required|string',
            'duration' => 'required|string',
        ]);

        try {
            $freight->update($validated);
            return redirect()->route('freights.index')
                ->with('success', 'Frete atualizado com sucesso!');
        } catch (\Exception $e) {
            Log::error('Freight update failed: '.$e->getMessage());
            return back()->withInput()
                ->with('error', 'Erro ao atualizar frete: '.$e->getMessage());
        }
    }

    public function destroy(Freight $freight)
    {
        try {
            $freight->delete();
            return response()->json([
                'success' => true,
                'message' => 'Frete excluído com sucesso!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao excluir frete',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function deleteAll()
    {
        try {
            DB::table('freights')->truncate();
            return response()->json([
                'success' => true,
                'message' => 'Todos os fretes foram excluídos com sucesso!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao excluir todos os fretes',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getPositionHistory(Freight $freight)
    {
        $history = DB::table('freight_positions')
            ->where('freight_id', $freight->id)
            ->orderBy('date', 'desc')
            ->get(['address', 'lat', 'lng', 'status', 'created_at']);
            
        return response()->json($history);
    }

    public function getStats()
    {
        $stats = Freight::join('freight_statuses', 'freights.status_id', '=', 'freight_statuses.id')
            ->selectRaw('freight_statuses.name, COUNT(*) as count')
            ->groupBy('freight_statuses.name')
            ->get()
            ->mapWithKeys(function($item) {
                return [$item->name => $item->count];
            });
        
        return response()->json([
            'Aguardando pagamento' => $stats['Aguardando pagamento'] ?? 0,
            'Aguardando motorista' => $stats['Aguardando motorista'] ?? 0,
            'Aguardando Aprovação empresa' => $stats['Aguardando Aprovação empresa'] ?? 0,
            'Aguardando retirada' => $stats['Aguardando retirada'] ?? 0,
            'Indo retirar carga' => $stats['Indo retirar carga'] ?? 0,
            'Em processo de entrega' => $stats['Em processo de entrega'] ?? 0,
            'Carga entregue' => $stats['Carga entregue'] ?? 0,
            'Cancelado' => $stats['Cancelado'] ?? 0,
            'total' => array_sum($stats->toArray())
        ]);
    }


    public function getStatuses()
    {
        return FreightStatus::all(['id', 'name']);
    }

    public function export()
    {
        return Excel::download(new FreightsExport, 'fretes.xlsx');
    }

    protected function createAsaasPayment(Freight $freight)
    {
        try {
            $response = Http::post('https://0xjej23ew7.execute-api.us-east-1.amazonaws.com/teste', [
                'name' => 'Frete #'.$freight->id,
                'billingType' => 'PIX',
                'value' => $freight->freight_value,
                'freight_id' => $freight->id,
                'successUrl' => 'https://fretesemnuvens.com.br/freights'
            ]);

            $data = $response->json();
         
            if ($data['success']) {
               
                $paymentData = [
                    'payment_link' => $data['asaasResponse']['url'] ?? null,
                    'asaas_payment_id' => $data['asaasResponse']['id'] ?? null
                ];

         
                $jsonObject = json_encode($paymentData, JSON_FORCE_OBJECT);
                return $jsonObject;
            }

            throw new \Exception('Asaas API error: '.$response->body());

        } catch (\Exception $e) {
            
            Log::error('Asaas payment failed: '.$e->getMessage());
            return [
                'payment_link' => null,
                'asaas_payment_id' => null,
                'erro' => $e->getMessage(),
                'retorno api' =>  $data
            ];
        }
    }
}