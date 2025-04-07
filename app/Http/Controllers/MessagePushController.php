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
        $query = MessagePush::with('driver:id,nome') // Assumindo que você tem relação com motoristas
            ->select('messages_push.*');

        return DataTables::of($query)
            ->addColumn('driver', fn($row) => $row->driver->name ?? '—')
            ->editColumn('send', fn($row) => $row->send ? '✅ Sim' : '❌ Não')
            ->editColumn('erro', fn($row) => $row->erro ? '<span class="text-danger">'.$row->erro.'</span>' : '—')
            ->editColumn('data', fn($row) => optional($row->data)->format('d/m/Y H:i'))
            ->rawColumns(['erro']) // Permitir HTML no campo de erro
            ->make(true);
    }
}
