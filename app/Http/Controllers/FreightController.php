<?php

namespace App\Http\Controllers;

use App\Models\Freight;
use App\Models\Shipment;
use App\Models\Driver;
use App\Models\FreightStatus;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Models\Address;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class FreightController extends Controller
{
    public function index()
    {
        $freights = Freight::with(['shipment', 'driver', 'status'])->get();
        return view('freights.index', compact('freights'));
    }

    public function show(Freight $freight)
    {        
        // Obtenha a distância e o tempo, se necessário, através do Google Maps API ou um método de cálculo
        $distance = 'Calculando...';
        $duration = 'Calculando...';
        $shipment = Shipment::findOrFail($freight->shipment_id);
        // Retornar os dados com a distância e o tempo
        return response()->json([
            'start_address' => $freight->start_address,
            'destination_address' => $freight->destination_address,
            'start_lat'  => $freight->start_lat,
            'start_lng'  => $freight->start_lng,
            'destination_lat'  => $freight->destination_lat,
            'destination_lng'  => $freight->destination_lng,
            'current_lat'  => $freight->current_lat,
            'current_lng'  => $freight->current_lng,
            'distance' =>  $freight->distance,
            'duration' =>  $freight->duration,
            'freight_id' => $freight->id,
            'directions' => $freight->directions,
            'freight_value' => $freight->freight_value,
            'paymentLink' => $freight->paymentLink,
            'asaas_payment_id'=> $freight->asaas_payment_id,
            'is_payment_confirmed' => $freight->is_payment_confirmed
         
        ]);
    }

    public function map()
    {
       
        // Passando os fretes para a view
        $freights = Freight::with(['driver', 'status'])->get();

        return view('freights.map', compact('freights'));
    }

    public function transport($freightId)
    {
        $freight = Freight::with('shipment')->findOrFail($freightId);

        return view('freights.transport', compact('freight'));
    }

    public function getFreights()
    {
        $freights = Freight::with(['driver', 'status', 'company'])->get();
        return response()->json($freights);
    }


    public function getData()
    {
        $freights = Freight::with(['driver', 'status', 'company'])->select('*');

        return DataTables::of($freights)
            ->addColumn('driver.name', function ($freight) {
                return $freight->driver->name ?? 'N/A';
            })
            ->addColumn('status.name', function ($freight) {
                return $freight->status->name ?? 'Sem status';
            })
            ->addColumn('freight_value', function ($freight) {
                return $freight->freight_value ?? 'Sem valor';
            })
            ->addColumn('actions', function ($freight) {
                return '
                    <a href="/freights/' . $freight->id . '" class="btn btn-info btn-sm">Ver</a>
                    <button class="btn btn-danger btn-sm" onclick="deleteFreight(' . $freight->id . ')">Excluir</button>
                ';
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

    public function create($shipmentId)
    {
        $shipment = Shipment::findOrFail($shipmentId);
        $drivers = Driver::all();
        $statuses = FreightStatus::all();

        return view('freights.create', compact('shipment', 'drivers', 'statuses'));
    }

    public function updatePosition(Request $request, $id)
    {
        $freight = Freight::findOrFail($id);
        $freight->current_position = $request->current_position;
        $freight->save();

        return response()->json(['success' => true, 'message' => 'Localização atualizada!']);
    }

  

    public function edit($id)
    {
        $freight = Freight::findOrFail($id);
        $drivers = Driver::all();
        $statuses = FreightStatus::all();
        return view('freights.edit', compact('freight', 'drivers', 'statuses'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'current_position' => 'required|string',
            'status_id' => 'required|exists:freight_statuses,id',
        ]);

        $freight = Freight::findOrFail($id);
        $freight->update($validated);

        return redirect()->route('freights.index')->with('success', 'Freight updated successfully.');
    }

    public function store(Request $request)
{
    // Validação dos dados
    $validated = $request->validate([
        'shipment_id' => 'required|exists:shipments,id',
        'company_id' => 'required|exists:companies,id',
        'start_address' => 'required|string',
        'destination_address' => 'required|string',
        'current_position' => 'required|string',
        'current_lat' => 'required|numeric',
        'current_lng' => 'required|numeric',
        'start_lat' => 'required|numeric',
        'start_lng' => 'required|numeric',
        'destination_lat' => 'required|numeric',
        'destination_lng' => 'required|numeric',
        'truck_type' => 'required|string|in:pequeno,medio,grande',
        'status_id' => 'required|exists:freight_statuses,id',
        'freight_value' => 'required|numeric|min:0',
        'distance' => 'required|string',
        'duration' => 'required|string',
    ]);

    try {
        DB::beginTransaction();

        // Criar novo frete
        $freight = Freight::create($validated);

        // Criar ou buscar endereços
        Address::firstOrCreate(
            ['address' => $validated['start_address']],
            ['latitude' => $validated['start_lat'], 'longitude' => $validated['start_lng']]
        );

        Address::firstOrCreate(
            ['address' => $validated['destination_address']],
            ['latitude' => $validated['destination_lat'], 'longitude' => $validated['destination_lng']]
        );

        $paymentResponse = $this->createAsaasPayment($freight);
        
        DB::commit();

        

        return response()->json([
            'success' => true,
            'message' => 'Frete criado com sucesso!',
            'data' => [
                'freight_id' => $freight->id,
                'payment_link' => $paymentResponse['payment_link'] ?? null,
                'asaas_payment_id' => $paymentResponse['asaas_payment_id'] ?? null,
            
            ]
        ], 201);

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

protected function createAsaasPayment(Freight $freight)
{
    try {
        $response = Http::post('https://0xjej23ew7.execute-api.us-east-1.amazonaws.com/teste', [
            'name' => 'Frete #'.$freight->id,
            'billingType' => 'UNDEFINED',
            'value' => $freight->freight_value,
            'freight_id' => $freight->id,
            'successUrl' => 'https://52.91.243.105/freights'
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

    public function deleteAll()
    {
        try {
            // Exclui todos os registros da tabela 'freights'
            Freight::truncate();
            return response()->json(['message' => 'Todos os fretes foram excluídos com sucesso!']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao excluir os fretes.', 'error' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        $freight = Freight::findOrFail($id);
        $freight->delete();

        return response()->json(['success' => true, 'message' => 'Frete excluído com sucesso!']);
    }
}
