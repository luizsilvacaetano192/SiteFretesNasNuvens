<?php

namespace App\Http\Controllers;

use App\Models\Truck;
use Illuminate\Http\Request;

class TruckController extends Controller
{
    public function toggleStatus(Request $request)
    {
        $client = new \GuzzleHttp\Client();
        
        try {
            $response = $client->post('https://8w2l4marz8.execute-api.us-east-1.amazonaws.com/teste', [
                'json' => $request->all(),
                'headers' => [
                    'Content-Type' => 'application/json',
                ]
            ]);
            
            return response()->json(json_decode($response->getBody(), true));
            
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}