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
    $query = MessagePush::with('driver:id,name')->select('messages_push.*');

    return DataTables::of($query)
        ->addColumn('driver', fn($row) => $row->driver->name ?? '—')
        ->addColumn('titulo', fn($row) => $row->titulo ?? '—')

        // ✅ Coluna visível formatada
        ->addColumn('send_label', fn($row) => $row->send ? '✅ Sim' : '❌ Não')

        // ✅ Coluna oculta para filtro
        ->addColumn('send', fn($row) => $row->send ? '1' : '0')

        // ✅ Coluna oculta com reason puro
        ->addColumn('reason', fn($row) => $row->reason ?? '')

        ->addColumn('erro', fn($row) => $row->reason ? '<span class="text-danger">'.$row->reason.'</span>' : '—')
        ->addColumn('data', fn($row) => optional($row->created_at)->format('Y-m-d')) // manter formato padrão para filtro
        ->addColumn('screen', fn($row) => $row->screen ?? '—')

        ->rawColumns(['erro'])
        ->make(true);
    }

}
