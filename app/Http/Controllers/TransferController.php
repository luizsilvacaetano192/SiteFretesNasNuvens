<?php

namespace App\Http\Controllers;
use App\Models\Transfer;

use Illuminate\Http\Request;

class TransferController extends Controller
{
    public function index(Request $request)
    {
        $freightId = $request->freight_id;
        $type = $request->type;
        $date = $request->date;

        $query = Transfer::with([
            'userAccount.driver',
            'userAccount.driver.freights.shipment.company'
        ])
        ->select('transfers.*')
        ->join('user_accounts', 'transfers.user_account_id', '=', 'user_accounts.id')
        ->join('drivers', 'user_accounts.driver_id', '=', 'drivers.id')
        ->join('freights', 'drivers.id', '=', 'freights.driver_id')
        ->join('shipments', 'freights.shipment_id', '=', 'shipments.id');

        if ($freightId) {
            $query->where('freights.id', $freightId);
        }

        if ($type) {
            $query->where('transfers.type', $type);
        }

        if ($date) {
            $query->whereDate('transfers.transfer_date', $date);
        }

        $transfers = $query->get();

        return view('admin.transfers.index', compact('transfers'));
    }

}
