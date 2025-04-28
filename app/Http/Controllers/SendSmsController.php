<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class SendSmsController extends Controller
{
    public function store(Request $request)
    {
        $body = json_encode([
            'phone_number' => $request->phone,
            'message' => $request->message
        ]);

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ])->post('https://2saeuklnwb.execute-api.us-east-1.amazonaws.com/teste/', $body);

        if ($response->successful()) {
            return response()->json($response->json());
        }
        return response()->json([
            'success' => false,
            'message' => $response->body() ?: 'Erro ao conectar com o serviço de sms'
        ], 400);
    }
}
