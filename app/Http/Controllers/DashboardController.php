<?php



namespace App\Http\Controllers;

use App\Models\Shipment;
use App\Models\Freight;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard', [
            'totalShipments' => Shipment::count(),
            'totalFreights' => Freight::count(),
            'pendingShipments' => Shipment::where('status_id', 1)->count(),
            'inTransitShipments' => Shipment::where('status_id', 2)->count(),
            'deliveredShipments' => Shipment::where('status_id', 3)->count(),
            'pendingFreights' => Freight::where('status_id', 1)->count(),
            'inTransitFreights' => Freight::where('status_id', 2)->count(),
            'deliveredFreights' => Freight::where('status_id', 3)->count(),
        ]);
    }
}
