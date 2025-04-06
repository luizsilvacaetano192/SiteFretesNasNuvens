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
                            "text" => "Você é um verificador inteligente. Analise as imagens e os dados informados a seguir. 
                            Verifique se a imagem do rosto confere com a foto da CNH, e se o endereço do comprovante confere com o informado. 
                            Diga se está tudo coerente ou aponte as divergências. Dados:
                            Nome: {$driver['name']}
                            Endereço: {$driver['address']}"
                        ],
                        [
                            "type" => "image_url",
                            "image_url" => [
                                "url" => $driver['driver_license_front'],
                                "detail" => "high"
                            ]
                        ],
                        [
                            "type" => "image_url",
                            "image_url" => [
                                "url" => $driver['address_proof'],
                                "detail" => "high"
                            ]
                        ],
                        [
                            "type" => "image_url",
                            "image_url" => [
                                "url" => $driver['face_photo'],
                                "detail" => "high"
                            ]
                        ]
                    ]
                ]
            ],
            "max_tokens" => 800
        ];

        try {
            $response = Http::withToken(env('OPENAI_API_KEY'))
                ->withHeaders([
                    'Content-Type' => 'application/json'
                ])
                ->post('https://api.openai.com/v1/chat/completions', $payload);

            if ($response->successful()) {
                $data = $response->json();
                return response()->json([
                    'status' => 'analisado',
                    'message' => $data['choices'][0]['message']['content'] ?? 'Sem resposta clara da IA.'
                ]);
            }

            return response()->json([
                'status' => 'erro',
                'message' => 'Erro da OpenAI: ' . $response->body()
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'erro',
                'message' => 'Falha na análise com IA: ' . $e->getMessage()
            ], 500);
        }
    }
}
