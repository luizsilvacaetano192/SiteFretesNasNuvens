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
        $query = MessagePush::with('driver:id,nome')
            ->select('message_push.*');
    
        // Filtros aqui
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
            ->addColumn('driver', fn($row) => $row->driver->nome ?? '—')
            ->addColumn('titulo', fn($row) => $row->titulo ?? '—')
            ->addColumn('send_label', fn($row) => $row->send ? '✅ Sim' : '❌ Não')
            ->addColumn('erro', fn($row) => $row->reason ? '❌' : '✅')
            ->addColumn('data', fn($row) => optional($row->created_at)->format('Y-m-d H:i:s'))
            ->addColumn('screen', fn($row) => $row->screen ?? '—')
            ->rawColumns(['erro'])
            ->make(true);
    }
    
}
