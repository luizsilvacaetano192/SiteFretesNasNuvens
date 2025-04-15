<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::firstOrNew();
        return view('settings.create', compact('settings'));
    }

    public function save(Request $request)
    {
        $validated = $request->validate([
            'cloud_percentage' => 'required|numeric|min:0|max:100',
            'advance_percentage' => 'required|numeric|min:0|max:100',
            'small_truck_price' => 'required|numeric|min:0',
            'medium_truck_price' => 'required|numeric|min:0',
            'large_truck_price' => 'required|numeric|min:0',
        ]);

        try {
            $settings = Setting::firstOrNew();
            $settings->fill($validated);
            $settings->save();

            return response()->json([
                'success' => true,
                'message' => 'Configurações salvas com sucesso!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao salvar configurações: ' . $e->getMessage()
            ], 500);
        }
    }
}