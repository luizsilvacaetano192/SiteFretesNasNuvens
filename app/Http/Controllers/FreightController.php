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
                    </div>
                ';
            })
            ->rawColumns(['status_badge', 'actions'])
            ->filter(function ($query) use ($request) {
                if ($request->has('status') && $request->status != '') {
                    $query->where('status_id', $request->status);
                }
                
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
            'truck_type' => 'required|string|in:pequeno,medio,grande',
            'freight_value' => 'required|numeric|min:0',
            'distance' => 'required|string',
            'duration' => 'required|string',
        ]);

        try {
            DB::beginTransaction();

            $freight = Freight::create($validated);

            DB::commit();

            dd( $freight);
            
            $paymentData = $this->createAsaasPayment($freight);

       

            return  $paymentData ;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Freight creation failed: '.$e->getMessage());

            return back()->withInput()
                ->with('error', 'Erro ao criar frete: '.$e->getMessage());
        }
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

    public function edit(Freight $freight)
    {
        $companies = Company::all();
        $drivers = Driver::all();
        $statuses = FreightStatus::all();
        $shipments = Shipment::whereDoesntHave('freight')
            ->orWhere('id', $freight->shipment_id)
            ->get();

        return view('freights.edit', compact('freight', 'companies', 'drivers', 'statuses', 'shipments'));
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
            ->orderBy('created_at', 'desc')
            ->get(['address', 'lat', 'lng', 'status', 'created_at']);
            
        return response()->json($history);
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
                'successUrl' => route('freights')
            ]);

            $data = $response->json();
            dd($data);
            if ($data->success) {
               
                $paymentData = [
                    'payment_link' => $data->asaasResponse['url'] ?? null,
                    'asaas_payment_id' => $data->asaasResponse['id'] ?? null
                ];

                return $paymentData;
            }

            throw new \Exception('Asaas API error: '.$response->body());

        } catch (\Exception $e) {
            
            Log::error('Asaas payment failed: '.$e->getMessage());
            return [
                'payment_link' => null,
                'asaas_payment_id' => null,
                'erro' => $e->getMessage()
            ];
        }
    }
}