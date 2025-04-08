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
        $query = MessagePush::with('driver:id,name') // Assumindo que você tem relação com motoristas
            ->select('messages_push.*');

        return DataTables::of($query)
            ->addColumn('driver', fn($row) => $row->driver->name ?? '—')
            ->addColumn('titulo', fn($row) => $row->titulo ?? '—')
            ->addColumn('send', fn($row) => $row->send ? '✅ Sim' : '❌ Não')
            ->addColumn('erro', fn($row) => $row->erro ? '<span class="text-danger">'.$row->erro.'</span>' : '')
            ->addColumn('data', fn($row) => optional($row->created_at)->format('d/m/Y H:i'))
            ->rawColumns(['erro']) // Permitir HTML no campo de erro
            ->addColumn('screen', fn($row) => $row->screen ?? '—')
            ->make(true);
    }
}
