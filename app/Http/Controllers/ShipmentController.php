<?php

namespace App\Http\Controllers;

use App\Models\Shipment;
use App\Models\Company;
use App\Models\Driver;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ShipmentController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->getShipments($request);
        }
        
        return view('shipments.index');
    }

    public function show(Shipment $shipment)
    {
        return response()->json([
            'company' => $shipment->company,
            'driver' => $shipment->driver,
            'weight' => $shipment->weight,
            'cargo_type' => $shipment->cargo_type,
            'start_address' => $shipment->start_address,
            'destination_address' => $shipment->destination_address,
            'expected_start_date' => $shipment->expected_start_date?->format('d/m/Y H:i'),
            'expected_delivery_date' => $shipment->expected_delivery_date?->format('d/m/Y H:i'),
            'distance' => $shipment->distance ?? 'Não calculado',
            'duration' => $shipment->duration ?? 'Não calculado',
            'status' => $shipment->status,
        ]);
    }

    public function getShipments(Request $request)
    {
        $shipments = Shipment::with(['company', 'freight'])
            ->select('shipments.*');

        return DataTables::of($shipments)
            ->addColumn('company_name', function($shipment) {
                return $shipment->company->name;
            })
            ->addColumn('freight_status', function($shipment) {
                return $shipment->freight ? $shipment->freight->status : 'Sem frete';
            })
            ->addColumn('action', function($shipment) {
                $btnView = '<a href="'.route('shipments.show', $shipment->id).'" 
                           class="btn btn-info btn-sm me-1" 
                           data-bs-toggle="tooltip" title="Visualizar">
                           <i class="fas fa-eye"></i></a>';

                $btnEdit = '<a href="'.route('shipments.edit', $shipment->id).'" 
                            class="btn btn-warning btn-sm me-1" 
                            data-bs-toggle="tooltip" title="Editar">
                            <i class="fas fa-edit"></i></a>';

                $btnFreight = '<a href="'.route('shipments.requestFreight', $shipment->id).'" 
                               class="btn btn-success btn-sm me-1" 
                               data-bs-toggle="tooltip" title="Solicitar Frete">
                               <i class="fas fa-truck"></i></a>';

                $btnDelete = '<form action="'.route('shipments.destroy', $shipment->id).'" method="POST" style="display:inline;">
                             '.csrf_field().'
                             '.method_field('DELETE').'
                             <button type="submit" class="btn btn-danger btn-sm" 
                                     data-bs-toggle="tooltip" title="Excluir"
                                     onclick="return confirm(\'Tem certeza que deseja excluir?\')">
                                     <i class="fas fa-trash"></i></button>
                             </form>';

                return '<div class="d-flex">'.$btnView.$btnEdit.$btnFreight.$btnDelete.'</div>';
            })
            ->addColumn('status_badge', function($shipment) {
                $status = $shipment->status ?? 'pending';
                $badgeClass = [
                    'pending' => 'bg-warning',
                    'approved' => 'bg-success',
                    'rejected' => 'bg-danger',
                    'completed' => 'bg-primary',
                ][$status] ?? 'bg-secondary';

                return '<span class="badge '.$badgeClass.'">'.
                    ucfirst($status).
                    '</span>';
            })
            ->rawColumns(['action', 'status_badge'])
            ->filter(function($query) use ($request) {
                if ($request->has('search') && !empty($request->search['value'])) {
                    $search = $request->search['value'];
                    $query->where(function($q) use ($search) {
                        $q->where('weight', 'like', "%$search%")
                          ->orWhere('cargo_type', 'like', "%$search%")
                          ->orWhere('dimensions', 'like', "%$search%")
                          ->orWhereHas('company', function($q) use ($search) {
                              $q->where('name', 'like', "%$search%");
                          });
                    });
                }
            })
            ->make(true);
    }

    public function create()
    {
        $companies = Company::all();
        $drivers = Driver::all();
        return view('shipments.create', compact('companies', 'drivers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            // Company information
            'company_id' => 'required|exists:companies,id',
            
            // Cargo information
            'cargo_type' => 'required|string|max:255|in:Secos,Frios,Granel,Perigosos,Fragil,Outros',
            'weight' => 'required|numeric|min:0.01',
            'dimensions' => 'required|string|max:255|regex:/^\d+x\d+x\d+$/',
            'volume' => 'nullable|numeric|min:0',
            'description' => 'nullable|string|max:500',
            
            // Flags
            'is_fragile' => 'sometimes|boolean',
            'is_hazardous' => 'sometimes|boolean',
            'requires_temperature_control' => 'sometimes|boolean',
            
            // Temperature control fields (required only if temperature control is needed)
            'min_temperature' => 'required_if:requires_temperature_control,true|numeric|nullable',
            'max_temperature' => 'required_if:requires_temperature_control,true|numeric|nullable',
            'temperature_tolerance' => 'nullable|numeric|min:0.1',
            'temperature_control_type' => 'nullable|string|in:refrigeration,freezing,climate_controlled',
            'temperature_unit' => 'nullable|string|in:celsius,fahrenheit',
            'temperature_notes' => 'nullable|string|max:500',
        ], [
            'dimensions.regex' => 'As dimensões devem estar no formato LxAxC (ex: 120x80x60)',
            'min_temperature.required_if' => 'A temperatura mínima é obrigatória quando o controle de temperatura é necessário',
            'max_temperature.required_if' => 'A temperatura máxima é obrigatória quando o controle de temperatura é necessário',
        ]);
    
        // Set default values for boolean fields if not provided
        $validated['is_fragile'] = $request->has('is_fragile');
        $validated['is_hazardous'] = $request->has('is_hazardous');
        $validated['requires_temperature_control'] = $request->has('requires_temperature_control');
    
        // Create the shipment
        $shipment = Shipment::create($validated);
    
        return redirect()->route('shipments.index')
            ->with('success', 'Carga cadastrada com sucesso!');
    }

    public function edit(Shipment $shipment)
    {
        $companies = Company::all();
        $drivers = Driver::all();
        return view('shipments.edit', compact('shipment', 'companies', 'drivers'));
    }

    public function update(Request $request, Shipment $shipment)
    {
        $validated = $request->validate([
            'company_id' => 'required|exists:companies,id',
            'driver_id' => 'nullable|exists:drivers,id',
            'weight' => 'required|numeric|min:0',
            'cargo_type' => 'required|string|max:255',
            'dimensions' => 'required|string|max:255',
            'start_address' => 'required|string|max:500',
            'destination_address' => 'required|string|max:500',
            'expected_start_date' => 'required|date',
            'expected_delivery_date' => 'required|date|after:expected_start_date',
            'status' => 'sometimes|in:pending,approved,rejected,completed',
        ]);

        $shipment->update($validated);

        return redirect()->route('shipments.index')
            ->with('success', 'Carga atualizada com sucesso!');
    }

    public function destroy(Shipment $shipment)
    {
        $shipment->delete();
        return response()->json(['success' => 'Carga excluída com sucesso!']);
    }

    public function requestFreight($id)
    {
        $shipment = Shipment::findOrFail($id);
        $companies = Company::findOrFail($shipment->company_id);
        return view('shipments.requestFreight', compact('shipment', 'companies'));
    }
}