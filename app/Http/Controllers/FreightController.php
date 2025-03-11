<?php

namespace App\Http\Controllers;

use App\Models\Freight;
use App\Models\Shipment;
use App\Models\Driver;
use App\Models\FreightStatus;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Models\Address;

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
            'directions' => $freight->directions
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
        $request->validate([
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
            'status_id' => 'required|exists:freight_statuses,id', // Verifique se o status_id existe na tabela statuses
        ]);

        // Criar um novo frete
        Freight::create([
            'shipment_id' => $request->shipment_id,
            'company_id' => $request->company_id,
            'start_address' => $request->start_address,
            'destination_address' => $request->destination_address,
            'current_position' => $request->current_position,
            'current_lat' => $request->current_lat,
            'current_lng' => $request->current_lng,
            'start_lat' => $request->start_lat,
            'start_lng' => $request->start_lng,
            'destination_lat' => $request->destination_lat,
            'destination_lng' => $request->destination_lng,
            'truck_type' => $request->truck_type,
            'status_id' => $request->status_id,
            'distance'  => $request->distance,
            'duration' => $request->duration,
            'directions' => $request->directions,
        ]);

        $startAddress = Address::firstOrCreate(
            [
                'address' => $request->start_address,
            ],
            [
                'latitude' => $request->start_lat,
                'longitude' => $request->start_lng,
            ]
        );
    
        // Verificar se o endereço de destino já existe, senão criar um novo
        $destinationAddress = Address::firstOrCreate(
            [
                'address' => $request->destination_address,
            ],
            [
                'latitude' => $request->destination_lat,
                'longitude' => $request->destination_lng,
            ]
        );
    
        // Retornar uma resposta de sucesso
        return response()->json(['message' => 'Frete criado com sucesso!'], 201);
    }

    public function destroy($id)
    {
        $freight = Freight::findOrFail($id);
        $freight->delete();

        return response()->json(['success' => true, 'message' => 'Frete excluído com sucesso!']);
    }
}
