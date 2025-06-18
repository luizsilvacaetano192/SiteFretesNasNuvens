<?php

namespace App\Http\Controllers;

use App\Models\ServiceKey;
use Illuminate\Http\Request;

class ServiceKeyController extends Controller
{
    public function index()
    {
        return ServiceKey::all();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'key'  => 'required|string',
        ]);

        return ServiceKey::create($validated);
    }

    public function show(ServiceKey $serviceKey)
    {
        return $serviceKey;
    }

    public function update(Request $request, ServiceKey $serviceKey)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'key'  => 'sometimes|required|string',
        ]);

        $serviceKey->update($validated);
        return $serviceKey;
    }

    public function destroy(ServiceKey $serviceKey)
    {
        $serviceKey->delete();
        return response()->json(['message' => 'Service key deleted successfully.']);
    }
}
