<?php

namespace App\Http\Controllers;

use App\Models\Freight;
use App\Models\Shipment;
use App\Models\Driver;
use App\Models\FreightStatus;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

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
       // dd($shipment);
     
    
        // Retornar os dados com a distância e o tempo
        return response()->json([
            'start_address' => $shipment->start_address,
            'destination_address' => $shipment->destination_address,
            'distance' => $distance,
            'duration' => $duration,
            'freight_id' => $freight->id
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
        $freights = Freight::with(['driver', 'status'])->get();
        return response()->json($freights);
    }


    public function getData()
    {
        $freights = Freight::with(['driver', 'status'])->select('freights.*');

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

    public function store(Request $request)
    {
        $validated = $request->validate([
            'shipment_id' => 'required|exists:shipments,id',
            'current_position' => 'required|string',
            'status_id' => 'required|exists:freight_statuses,id',
        ]);

        $freight = Freight::create([
            'shipment_id' => $request->shipment_id,
            'current_position' => (string) $request->current_position, // Converte para string
            'status_id' => $request->status_id,
        ]);
    

        return response()->json([
            'success' => true,
            'message' => 'Frete cadastrado com sucesso!',
            'freight' => $freight // Retorna os dados do frete criado, caso necessário
        ]);
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

    public function destroy($id)
    {
        $freight = Freight::findOrFail($id);
        $freight->delete();

        return response()->json(['success' => true, 'message' => 'Frete excluído com sucesso!']);
    }
}
