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
        $drivers = Driver::select(['*']);

        return DataTables::of($drivers)
            
            ->addColumn('driver_license_front', fn($driver) => $driver->driver_license_front_url)
            ->addColumn('driver_license_back', fn($driver) => $driver->driver_license_back_url)
            ->addColumn('face_photo', fn($driver) => $driver->face_photo_url)
            ->addColumn('address_proof', fn($driver) => $driver->address_proof_url)
            ->toJson()
            
            ->rawColumns(['actions'])
            ->make(true);
    }

    public function data(Request $request)
    {
        $query = Driver::query();

        return DataTables::of($query)
            ->addColumn('driver_license_front', fn($driver) => $driver->driver_license_front_url)
            ->addColumn('driver_license_back', fn($driver) => $driver->driver_license_back_url)
            ->addColumn('face_photo', fn($driver) => $driver->face_photo_url)
            ->addColumn('address_proof', fn($driver) => $driver->address_proof_url)
            ->toJson();
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
