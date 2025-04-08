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

    public function resend($id)
    {
        $message = MessagePush::findOrFail($id);

        // Aqui pode colocar lógica real de reenvio via FCM, se quiser

        $message->erro = null;
        $message->save();

        return response()->json(['success' => true, 'message' => 'Mensagem sera enviada novamente.']);
    }

    public function list(Request $request)
    {
        $query = MessagePush::with('driver:id,name')
            ->select('messages_push.*');
    
        // Filtros
        if ($request->filled('send')) {
            $query->where('send', $request->send);
        }
    
        if ($request->filled('error')) {
            $query->whereNotNull('erro')->where('erro', '!=', "''");
        }
    
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }
    
        return datatables()->of($query)
            ->addColumn('driver', fn($row) => $row->driver->name ?? '—')
            ->addColumn('titulo', fn($row) => $row->titulo ?? '—')
            ->addColumn('texto', fn($row) => $row->titulo ?? '—')
            ->addColumn('send_label', fn($row) => $row->send ? '✅ Sim' : '❌ Não')
            ->addColumn('data', fn($row) => optional($row->created_at)->format('d/m/Y H:i:s'))
            ->addColumn('screen', fn($row) => $row->screen ?? '—')
            ->addColumn('actions', function ($row) {
                if (!empty($row->erro)) {
                    return '<button class="btn btn-sm btn-warning resend-btn" data-id="' . $row->id . '">🔁 Reenviar</button>';
                }
                return '—';
            })
            ->rawColumns(['actions'])
            ->make(true);
    }
    
    
}
