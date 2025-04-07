<?php

namespace App\Http\Controllers;

use App\Models\FreightStatus;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class FreightStatusController extends Controller
{

    public function index()
    {
        return view('freight_status.index');
    }

    public function getData()
    {
        $statuses = FreightStatus::select('id', 'name', 'created_at');

        return DataTables::of($statuses)
            ->addColumn('actions', function ($status) {
                return '
                    <a href="#" class="btn btn-warning btn-sm">Edit</a>
                    <a href="#" class="btn btn-danger btn-sm">Delete</a>
                ';
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

    public function create()
    {
        return view('freight_status.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        FreightStatus::create([
            'name' => $request->name,
        ]);

        return redirect()->route('freight_statuses.index')->with('success', 'Status do frete cadastrado com sucesso!');
    }
}
