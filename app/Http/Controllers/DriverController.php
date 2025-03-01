<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class DriverController extends Controller
{
    public function index()
    {
        return view('drivers.index');
    }

    public function getData()
    {
        $drivers = Driver::select(['id', 'name', 'phone', 'license_number']);

        return DataTables::of($drivers)
            ->addColumn('actions', function ($driver) {
                return '
                    <a href="'.route('shipments.index', ['driver_id' => $driver->id]).'" class="btn btn-primary btn-sm">🚚 Ver Fretes</a>
                   
                ';
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

    public function create()
    {
        return view('drivers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'cpf' => 'required|string|unique:drivers,cpf|size:14', // 14 caracteres com máscara
            'phone' => 'nullable|string|size:15', // 15 caracteres com máscara
            'license_number' => 'required|string|unique:drivers,license_number|max:20',
            'license_category' => 'required|string|max:3',
        ]);

        Driver::create($request->all());

        return redirect()->route('drivers.index')->with('success', 'Driver registered successfully.');
    }

    public function edit(Driver $driver)
    {
        return view('drivers.edit', compact('driver'));
    }

    public function update(Request $request, Driver $driver)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'cpf' => 'required|string|size:14|unique:drivers,cpf,' . $driver->id,
            'phone' => 'nullable|string|size:15',
            'license_number' => 'required|string|max:20|unique:drivers,license_number,' . $driver->id,
            'license_category' => 'required|string|max:3',
        ]);

        $driver->update($request->all());

        return redirect()->route('drivers.index')->with('success', 'Driver updated successfully.');
    }

    public function destroy(Driver $driver)
    {
        $driver->delete();
        return redirect()->route('drivers.index')->with('success', 'Driver deleted successfully.');
    }
}
