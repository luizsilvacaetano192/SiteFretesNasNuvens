<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DriverAnalysisController extends Controller
{
    public function analyze(Request $request)
    {
        $driver = $request->all();

        $payload = [
            "model" => "gpt-4-vision-preview",
            "messages" => [
                [
                    "role" => "user",
                    "content" => [
                        [
                            "type" => "text",
                            "text" => "Compare as imagens com os dados a seguir. Diga se estão coerentes ou há alguma divergência.
                            Nome: {$driver['name']}
                            Endereço: {$driver['address']}"
                        ],
                        [
                            "type" => "image_url",
                            "image_url" => ["url" => $driver['driver_license_front'], "detail" => "high"]
                        ],
                        [
                            "type" => "image_url",
                            "image_url" => ["url" => $driver['address_proof'], "detail" => "high"]
                        ],
                        [
                            "type" => "image_url",
                            "image_url" => ["url" => $driver['face_photo'], "detail" => "high"]
                        ]
                    ]
                ]
            ],
            "max_tokens" => 500
        ];

        $response = Http::withToken(env('OPENAI_API_KEY'))
            ->post('https://api.openai.com/v1/chat/completions', $payload);

        if ($response->successful()) {
            return response()->json([
                'status' => 'analisado',
                'message' => $response['choices'][0]['message']['content']
            ]);
        }

        return response()->json(['status' => 'erro', 'message' => 'Falha na análise com IA.'], 500);
    }
}
