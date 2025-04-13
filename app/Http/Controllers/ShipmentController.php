<?php

namespace App\Http\Controllers;

use App\Models\Shipment;
use App\Models\Company;
use App\Models\Driver;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ShipmentController extends Controller
{
    public function index()
    {
        $shipments = Shipment::with(['Company'])->get();
     
        return view('shipments.index', compact('shipments'));
    }

 
    public function show(Shipment $shipment)
    {
        // Obtenha a distância e o tempo, se necessário, através do Google Maps API ou um método de cálculo
        $distance = 'Calculando...';
        $duration = 'Calculando...';
    
        // Retornar os dados com a distância e o tempo
        return response()->json([
            'company' => $shipment->company,
            'driver' => $shipment->driver,
            'weight' => $shipment->weight,
            'cargo_type' => $shipment->cargo_type,
            'start_address' => $shipment->start_address,
            'destination_address' => $shipment->destination_address,
            'expected_start_date' => $shipment->expected_start_date,
            'expected_delivery_date' => $shipment->expected_delivery_date,
            'distance' => $distance,
            'duration' => $duration,
        ]);
    }

    public function getAllShipments()
    {
        $shipments = Shipment::with(['freight', 'company', 'driver'])->select('*')->get();
        return response()->json($shipments);
    }
    


    public function getShipments(Request $request)
    {
        if ($request->ajax()) {
        
            $shipments = Shipment::with(['freight', 'company'])->select('*')->get();
         
            return DataTables::of($shipments)
                ->addColumn('action', function ($shipment) {
                    return '
                        <a href="' . route("shipments.edit", $shipment->id) . '" class="btn btn-warning btn-sm">Editar</a>
                        <form action="' . route("shipments.destroy", $shipment->id) . '" method="POST" style="display:inline;">
                            ' . csrf_field() . '
                            ' . method_field("DELETE") . '
                            <button type="submit" class="btn btn-danger btn-sm">Excluir</button>
                        </form>
                    ';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }


    public function create()
    {
        $companies = Company::all(); // Recuperando todas as empresas
        $drivers = Driver::all(); // Recuperando todos os motoristas (se necessário)
        return view('shipments.create', compact('companies', 'drivers'));
    }

  

    public function store(Request $request)
    {
        $request->validate([
            'company_id' => 'required|exists:companies,id',
            'weight' => 'required|numeric',
            'cargo_type' => 'required|string|max:255',
            'dimensions' => 'required|string|max:255',
        ]);
    
        // Criar a carga com os dados informados
        $shipment = new Shipment([
            'company_id' => $request->input('company_id'),
            'weight' => $request->input('weight'),
            'cargo_type' => $request->input('cargo_type'),
            'dimensions' => $request->input('dimensions'),
        ]);
        
        $shipment->save();
    
        return redirect()->route('/shipments/index')->with('success', 'Carga cadastrada com sucesso!');
       
    }

    public function edit($id)
    {
        $shipment = Shipment::findOrFail($id);
        return view('shipments.edit', compact('shipment'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'company_id' => 'required',
            'driver_id' => 'required',
            'weight' => 'required|numeric',
            'cargo_type' => 'required|string',
            'start_address' => 'required|string',
            'destination_address' => 'required|string',
            'expected_start_date' => 'required|date',
            'expected_delivery_date' => 'required|date',
            'deadline' => 'required|integer',
            'start_time' => 'required|date_format:H:i',
        ]);

        $shipment = Shipment::findOrFail($id);
        $shipment->update($request->all());

        return redirect()->route('shipments.index')->with('success', 'Carga atualizada com sucesso!');
    }

    public function destroy($id)
    {
        $shipment = Shipment::findOrFail($id);
        $shipment->delete();

        return response()->json(['success' => 'Carga excluída com sucesso!']);
    }

    public function requestFreight($id)
    {
        // Buscar o shipment pelo ID
        $shipment = Shipment::findOrFail($id);

        // Retornar a view de solicitação de frete
        return view('shipments.requestFreight', compact('shipment'));
    }

    

    
}
