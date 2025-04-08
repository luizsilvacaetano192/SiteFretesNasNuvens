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
        $query = MessagePush::with('driver:id,name')
            ->select('messages_push.*');
    
        // Filtro de envio (send = 1 ou 0)
        if ($request->has('send') && $request->send !== '') {
            $query->where('send', $request->send);
        }
    
        // Filtro de erro (reason preenchido ou não)
        if ($request->has('error')) {
            if ($request->error == '1') {
                $query->whereNotNull('reason')->where('reason', '!=', '');
            } elseif ($request->error == '0') {
                $query->where(function ($q) {
                    $q->whereNull('reason')->orWhere('reason', '');
                });
            }
        }
    
        // Filtro por data
        if ($request->has('date') && $request->date) {
            $query->whereDate('created_at', $request->date);
        }
    
        return DataTables::of($query)
            ->addColumn('driver', fn($row) => $row->driver->name ?? '—')
            ->addColumn('titulo', fn($row) => $row->titulo ?? '—')
            ->addColumn('send_label', fn($row) => $row->send ? '✅ Sim' : '❌ Não')
            ->addColumn('erro', fn($row) => $row->erro ? '<span class="text-danger">' . $row->erro . '</span>' : '')
            ->addColumn('data', fn($row) => optional($row->created_at)->format('Y-m-d H:i:s'))
            ->addColumn('screen', fn($row) => $row->screen ?? '—')
            ->rawColumns(['erro']) // Permitir HTML no campo de erro
            ->make(true);
    }
}
