<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
   
        public function index()
        {
            // Busca as configurações ou cria um registro padrão se não existir
            $settings = Setting::firstOrCreate([]);
            return view('settings.create', compact('settings'));
        }
    
        public function save(Request $request)
        {
            $validated = $request->validate([
                'cloud_percentage' => 'required|numeric|between:0,100',
                'advance_percentage' => 'required|numeric|between:0,100',
                'small_truck_price' => 'required|numeric|min:0',
                'medium_truck_price' => 'required|numeric|min:0',
                'large_truck_price' => 'required|numeric|min:0',
                'small_refrigerated_rate' => 'required|numeric|min:0',
                'medium_refrigerated_rate' => 'required|numeric|min:0',
                'large_refrigerated_rate' => 'required|numeric|min:0',
                'small_tanker_rate' => 'required|numeric|min:0',
                'medium_tanker_rate' => 'required|numeric|min:0',
                'large_tanker_rate' => 'required|numeric|min:0',
                'minimum_freight_value' => 'required|numeric|min:0',
                'weight_surcharge_3000' => 'required|numeric|between:0,100',
                'weight_surcharge_5000' => 'required|numeric|between:0,100',
                'fragile_surcharge' => 'required|numeric|between:0,100',
                'hazardous_surcharge' => 'required|numeric|between:0,100',
            ]);
    
            // Atualiza ou cria as configurações
            Setting::updateOrCreate(['id' => 1], $validated);
    
            return response()->json([
                'success' => true,
                'message' => 'Configurações salvas com sucesso!'
            ]);
        }
    
}