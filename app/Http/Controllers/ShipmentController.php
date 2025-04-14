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
            'company_id' => 'required|exists:companies,id',
            'weight' => 'required|numeric|min:0',
            'cargo_type' => 'required|string|max:255',
            'dimensions' => 'required|string|max:255',
            'start_address' => 'required|string|max:500',
            'destination_address' => 'required|string|max:500',
            'expected_start_date' => 'required|date',
            'expected_delivery_date' => 'required|date|after:expected_start_date',
        ]);

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

    public function requestFreight(Shipment $shipment)
    {
        return view('shipments.requestFreight', compact('shipment'));
    }
}