<?php

namespace App\Http\Controllers;

use App\Models\Freight;
use App\Models\Shipment;
use App\Models\Driver;
use App\Models\FreightStatus;
use App\Models\Company;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FreightController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->getDataTable($request);
        }
        
        $statuses = FreightStatus::all();
        return view('freights.index', compact('statuses'));
    }

    public function getDataTable(Request $request)
    {
        $query = Freight::with(['driver', 'status', 'company', 'shipment'])
            ->select('freights.*');

        return DataTables::of($query)
            ->addColumn('company_name', function($freight) {
                return $freight->company->name ?? 'N/A';
            })
            ->addColumn('shipment_info', function($freight) {
                return $freight->shipment ? 'Carga #'.$freight->shipment->id : 'N/A';
            })
            ->addColumn('driver_name', function($freight) {
                return $freight->driver ? $freight->driver->name : 'Não atribuído';
            })
            ->addColumn('status_badge', function($freight) {
                $status = $freight->status;
                if (!$status) return '<span class="badge bg-secondary">N/A</span>';
                
                $badgeClass = [
                    'pending' => 'bg-warning',
                    'active' => 'bg-primary',
                    'completed' => 'bg-success',
                    'cancelled' => 'bg-danger',
                ][strtolower($status->slug)] ?? 'bg-secondary';
                
                return '<span class="badge '.$badgeClass.'">'.$status->name.'</span>';
            })
            ->addColumn('formatted_value', function($freight) {
                return 'R$ '.number_format($freight->freight_value, 2, ',', '.');
            })
            ->addColumn('actions', function($freight) {
                return '
                    <div class="d-flex gap-2">
                        <button class="btn btn-sm btn-primary view-freight" data-id="'.$freight->id.'" title="Visualizar">
                            <i class="fas fa-eye"></i>
                        </button>
                        <a href="'.route('freights.edit', $freight->id).'" class="btn btn-sm btn-warning" title="Editar">
                            <i class="fas fa-edit"></i>
                        </a>
                        <button class="btn btn-sm btn-danger delete-freight" data-id="'.$freight->id.'" title="Excluir">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                ';
            })
            ->rawColumns(['status_badge', 'actions'])
            ->filter(function ($query) use ($request) {
                // Filtro por status
                if ($request->has('status') && $request->status != '') {
                    $query->where('status_id', $request->status);
                }
                
                // Filtro de pesquisa geral
                if ($request->has('search') && !empty($request->search['value'])) {
                    $search = $request->search['value'];
                    $query->where(function($q) use ($search) {
                        $q->whereHas('company', function($q) use ($search) {
                            $q->where('name', 'like', "%$search%");
                        })
                        ->orWhereHas('driver', function($q) use ($search) {
                            $q->where('name', 'like', "%$search%");
                        })
                        ->orWhere('start_address', 'like', "%$search%")
                        ->orWhere('destination_address', 'like', "%$search%");
                    });
                }
            })
            ->make(true);
    }

    public function show(Freight $freight)
    {
        return response()->json([
            'id' => $freight->id,
            'company' => [
                'name' => $freight->company->name ?? 'N/A',
                'id' => $freight->company_id
            ],
            'driver' => [
                'name' => $freight->driver->name ?? 'Não atribuído',
                'id' => $freight->driver_id
            ],
            'shipment' => [
                'id' => $freight->shipment_id,
                'weight' => $freight->shipment->weight ?? 'N/A',
                'cargo_type' => $freight->shipment->cargo_type ?? 'N/A'
            ],
            'start_address' => $freight->start_address,
            'destination_address' => $freight->destination_address,
            'start_lat' => $freight->start_lat,
            'start_lng' => $freight->start_lng,
            'destination_lat' => $freight->destination_lat,
            'destination_lng' => $freight->destination_lng,
            'current_lat' => $freight->current_lat,
            'current_lng' => $freight->current_lng,
            'current_position' => $freight->current_position,
            'distance' => $freight->distance,
            'duration' => $freight->duration,
            'freight_value' => $freight->freight_value,
            'status' => [
                'id' => $freight->status_id,
                'name' => $freight->status->name ?? 'N/A',
                'slug' => $freight->status->slug ?? 'N/A'
            ],
            'payment_info' => [
                'payment_link' => $freight->payment_link,
                'asaas_payment_id' => $freight->asaas_payment_id,
                'is_payment_confirmed' => $freight->is_payment_confirmed
            ],
            'created_at' => $freight->created_at->format('d/m/Y H:i'),
            'updated_at' => $freight->updated_at->format('d/m/Y H:i')
        ]);
    }

    public function getPositionHistory(Freight $freight)
    {
        // Implemente este método se tiver histórico de localização
        // Exemplo básico - ajuste conforme sua estrutura de dados
        $history = DB::table('freight_positions')
            ->where('freight_id', $freight->id)
            ->orderBy('created_at', 'desc')
            ->get(['address', 'lat', 'lng', 'created_at']);
            
        return response()->json($history);
    }

    public function create($shipmentId)
    {
        $shipment = Shipment::with('company')->findOrFail($shipmentId);
        $drivers = Driver::all();
        $statuses = FreightStatus::all();

        return view('freights.create', compact('shipment', 'drivers', 'statuses'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateFreightRequest($request);
        
        try {
            DB::beginTransaction();

            $freight = Freight::create($validated);
            $paymentData = $this->createAsaasPayment($freight);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Frete criado com sucesso!',
                'data' => [
                    'freight' => $freight,
                    'payment_link' => $paymentData['payment_link'],
                    'asaas_payment_id' => $paymentData['asaas_payment_id']
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Freight creation failed: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Erro ao criar frete',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    protected function validateFreightRequest(Request $request)
    {
        return $request->validate([
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
    }

    protected function createAsaasPayment(Freight $freight)
    {
        try {
            $response = Http::post('https://0xjej23ew7.execute-api.us-east-1.amazonaws.com/teste', [
                'name' => 'Frete #'.$freight->id,
                'billingType' => 'UNDEFINED',
                'value' => $freight->freight_value,
                'freight_id' => $freight->id,
                'successUrl' => route('freights.index')
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $paymentData = [
                    'payment_link' => $data['paymentLink'] ?? null,
                    'asaas_payment_id' => $data['paymentId'] ?? null
                ];

                $freight->update([
                    'payment_link' => $paymentData['payment_link'],
                    'asaas_payment_id' => $paymentData['asaas_payment_id']
                ]);

                return $paymentData;
            }

            throw new \Exception('Asaas API error: '.$response->body());

        } catch (\Exception $e) {
            Log::error('Asaas payment failed: '.$e->getMessage());
            return [
                'payment_link' => null,
                'asaas_payment_id' => null
            ];
        }
    }

    public function edit(Freight $freight)
    {
        $drivers = Driver::all();
        $statuses = FreightStatus::all();
        return view('freights.edit', compact('freight', 'drivers', 'statuses'));
    }

    public function update(Request $request, Freight $freight)
    {
        $validated = $request->validate([
            'driver_id' => 'nullable|exists:drivers,id',
            'status_id' => 'required|exists:freight_statuses,id',
            'current_position' => 'required|string|max:500',
            'current_lat' => 'required|numeric',
            'current_lng' => 'required|numeric',
        ]);

        $freight->update($validated);

        return redirect()->route('freights.index')
            ->with('success', 'Frete atualizado com sucesso!');
    }

    public function updatePosition(Request $request, Freight $freight)
    {
        $validated = $request->validate([
            'current_position' => 'required|string|max:500',
            'current_lat' => 'required|numeric',
            'current_lng' => 'required|numeric',
        ]);

        $freight->update($validated);

        // Opcional: registrar no histórico de posições
        DB::table('freight_positions')->insert([
            'freight_id' => $freight->id,
            'address' => $validated['current_position'],
            'lat' => $validated['current_lat'],
            'lng' => $validated['current_lng'],
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Posição atualizada com sucesso!',
            'data' => $freight
        ]);
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

    public function getStats()
    {
        $stats = FreightStatus::withCount(['freights as count'])
            ->get()
            ->mapWithKeys(function($status) {
                return [strtolower($status->slug) => $status->count];
            });
            
        return response()->json([
            'active' => $stats['active'] ?? 0,
            'pending' => $stats['pending'] ?? 0,
            'completed' => $stats['completed'] ?? 0,
            'cancelled' => $stats['cancelled'] ?? 0,
            'total' => array_sum($stats->toArray())
        ]);
    }
}