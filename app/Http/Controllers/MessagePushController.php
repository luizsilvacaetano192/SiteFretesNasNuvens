<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\MessagePush;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class MessagePushController extends Controller
{
    public function index()
    {
        return view('drivers.messages.index');
    }

    public function list(Request $request)
{
    $query = MensagemPush::with('driver');

    if ($request->filled('send')) {
        $query->where('send', $request->send);
    }

    if ($request->filled('error')) {
        if ($request->error == 1) {
            $query->whereNotNull('reason')->where('reason', '!=', '');
        } elseif ($request->error == 0) {
            $query->where(function ($q) {
                $q->whereNull('reason')->orWhere('reason', '');
            });
        }
    }

    if ($request->filled('date')) {
        $query->whereDate('created_at', $request->date);
    }

    return datatables()->of($query)
        ->addColumn('driver', function ($msg) {
            return $msg->driver->nome ?? 'N/A';
        })
        ->addColumn('send_label', function ($msg) {
            return $msg->send ? '✅' : '❌';
        })
        ->addColumn('erro', function ($msg) {
            return $msg->reason ? '❌' : '✅';
        })
        ->toJson();
}
}
