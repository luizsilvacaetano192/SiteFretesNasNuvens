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
            'Accept' => 'application/json',
        ])->post('https://2saeuklnwb.execute-api.us-east-1.amazonaws.com/teste/', $body);

        if ($response->successful()) {
            // Aqui decodifica o "body" interno
            $data = $response->json();

            // Se o "body" vier como string JSON, decodifica ele também:
            if (isset($data['body'])) {
                $bodyDecoded = json_decode($data['body'], true);

                // Retorna o conteúdo certo para o front
                return response()->json($bodyDecoded);
            }

            // Se por algum motivo não existir body:
            return response()->json($data);
        }

        return response()->json([
            'success' => false,
            'message' => $response->body() ?: 'Erro ao conectar com o serviço de sms'
        ], 400);
    }
}
