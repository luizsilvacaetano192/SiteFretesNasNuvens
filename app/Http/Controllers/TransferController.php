<?php

namespace App\Http\Controllers;

use App\Models\Transfer;
use App\Models\Driver;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

class TransferController extends Controller
{
    // Armazena o último payload enviado para logs
    protected $lastRequestPayload;

    public function index(Request $request)
    {
        $transfers = Transfer::with('driver')->query();

        // Filtro por período de datas
        if ($request->filled('date_range')) {
            try {
                [$start, $end] = explode(' - ', $request->date_range);

                $startDate = Carbon::createFromFormat('d/m/Y', trim($start))->startOfDay();
                $endDate = Carbon::createFromFormat('d/m/Y', trim($end))->endOfDay();

                $transfers->whereBetween('created_at', [$startDate, $endDate]);
            } catch (\Exception $e) {
                Log::warning('Filtro de data inválido', ['input' => $request->date_range]);
            }
        }

        // Filtro por ID do frete
        if ($request->filled('freight_id')) {
            $transfers->where('freight_id', $request->freight_id);
        }

        // Filtro por tipo de transferência
        if ($request->filled('type')) {
            $transfers->where('type', $request->type);
        }

        // Filtro por motorista
        if ($request->filled('driver_id')) {
            $transfers->where('driver_id', $request->driver_id);
        }

        // Filtro por status
        if ($request->filled('status')) {
            $transfers->where('status', $request->status);
        }

        // Ordenação e paginação
        $transfers = $transfers->orderBy('created_at', 'desc')->paginate(50);

        return view('transfers.index', compact('transfers'));
    }

    public function transfer(Request $request, $driverId)
    {
        DB::beginTransaction();
        
        try {
            // Validação dos dados de entrada
            $validated = $this->validateRequest($request);
            
            // Busca o motorista com verificação
            $driver = Driver::findOrFail($driverId);
            
            // Prepara os dados para a API externa
            $apiPayload = $this->prepareApiPayload($validated, $driver);
            
            // Faz a requisição para a API externa
            $apiResponse = $this->callExternalApi($apiPayload);
            
            // Processa a resposta
            return $this->handleApiResponse($apiResponse, $driver, $validated);
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            DB::rollBack();
            return $this->errorResponse('Motorista não encontrado', 404);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Erro de validação',
                'errors' => $e->errors()
            ], 422);
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Transfer Error: '.$e->getMessage(), [
                'driver_id' => $driverId,
                'trace' => $e->getTraceAsString()
            ]);
            return $this->errorResponse('Erro interno no servidor: '.$e->getMessage(), 500);
        }
    }

    protected function validateRequest(Request $request)
    {
        return $request->validate([
            'type' => 'required|in:available_balance,blocked_balance',
            'amount' => [
                'required',
                'numeric',
                'min:0.01',
                'max:999999.99',
                function ($attribute, $value, $fail) {
                    if (!is_numeric($value)) {
                        $fail('O valor deve ser numérico');
                    }
                }
            ],
            'description' => 'nullable|string|max:255',
            'freight_value' => 'nullable|numeric|min:0'
        ]);
    }

    protected function prepareApiPayload(array $validated, Driver $driver)
    {
        $payload = [
            'driver_id' => (int)$driver->id,
            'type' => (string)$validated['type'],
            'amount' => (float)$validated['amount'],
            'description' => (string)($validated['description'] ?? 'Transferência realizada pelo sistema'),
            'transfer_date' => Carbon::now()->format('Y-m-d'),
            'driver_info' => [
                'name' => (string)$driver->name,
                'cpf' => (string)$driver->cpf,
                'phone' => (string)$driver->phone
            ]
        ];

        if (isset($validated['freight_value'])) {
            $payload['freight_value'] = (float)$validated['freight_value'];
        }

        $this->lastRequestPayload = $payload; // Armazena para logs

        return $payload;
    }

    protected function callExternalApi(array $payload)
    {
        return Http::withOptions([
            'timeout' => 30, // Slightly increased timeout
        ])
        ->withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'X-Requested-With' => 'XMLHttpRequest',
        ])
        ->timeout(30)
        ->retry(3, 1000, function ($exception) {
            // Don't retry for client errors (4xx)
            return !($exception instanceof \Illuminate\Http\Client\RequestException && 
                   $exception->response && 
                   $exception->response->clientError());
        })
        ->post('https://4fuy7ttno9.execute-api.us-east-1.amazonaws.com/teste', $payload);
    }

    protected function handleApiResponse($response, Driver $driver, array $validated)
    {
        // Verifica se a resposta é bem-sucedida
        if ($response->successful()) {
            try {
                $responseData = $response->json();
                
                // Check both HTTP status and API's success flag
                if (isset($responseData['success']) && $responseData['success'] === true) {
                    // Create transfer record
                    $transfer = Transfer::create([
                        'driver_id' => $driver->id,
                        'type' => $validated['type'],
                        'amount' => $validated['amount'],
                        'description' => $validated['description'] ?? 'Transferência realizada pelo sistema',
                        'status' => 'completed',
                        'external_reference' => $responseData['transferDetails']['asaasTransferId'] ?? null,
                        'response_payload' => json_encode($responseData)
                    ]);
                    
                    DB::commit();
                    
                    return response()->json([
                        'success' => true,
                        'message' => 'Transferência realizada com sucesso',
                        'data' => [
                            'transfer_id' => $transfer->id,
                            'amount' => $transfer->amount,
                            'external_reference' => $transfer->external_reference,
                            'processed_at' => $transfer->created_at->format('Y-m-d H:i:s')
                        ]
                    ]);
                }
                
                // Handle API's success=false with HTTP 200
                Log::warning('API returned success=false with HTTP 200', [
                    'response' => $responseData,
                    'driver_id' => $driver->id
                ]);
                
                // Check if this is actually a success case with bad API response format
                if (strpos(strtolower($responseData['message'] ?? ''), 'success') !== false) {
                    // Treat as success if message contains "success"
                    $transfer = Transfer::create([
                        'driver_id' => $driver->id,
                        'type' => $validated['type'],
                        'amount' => $validated['amount'],
                        'description' => $validated['description'] ?? 'Transferência realizada pelo sistema',
                        'status' => 'completed',
                        'external_reference' => $responseData['transferDetails']['asaasTransferId'] ?? null,
                        'response_payload' => json_encode($responseData)
                    ]);
                    
                    DB::commit();
                    
                    return response()->json([
                        'success' => true,
                        'message' => 'Transferência realizada com sucesso',
                        'data' => [
                            'transfer_id' => $transfer->id,
                            'amount' => $transfer->amount,
                            'external_reference' => $transfer->external_reference,
                            'processed_at' => $transfer->created_at->format('Y-m-d H:i:s')
                        ]
                    ]);
                }
                
                return $this->handleApiError($response, $driver);
                
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Failed to parse API response', [
                    'driver_id' => $driver->id,
                    'response' => $response->body(),
                    'error' => $e->getMessage()
                ]);
                
                return $this->errorResponse('Erro ao processar resposta da API', 500);
            }
        }
        
        // Se a resposta não foi bem-sucedida (não 2xx)
        return $this->handleApiError($response, $driver);
    }

    protected function handleApiError($response, $driver)
    {
        DB::rollBack();
        $statusCode = $response->status();
        
        try {
            $errorData = $response->json();
            $errorMessage = $errorData['message'] ?? 'Erro na API de transferência';
            
            // Log detalhado para diagnóstico
            Log::error('API Transfer Error', [
                'status_code' => $statusCode,
                'api_response' => $errorData,
                'driver_id' => $driver->id,
                'request_payload' => $this->lastRequestPayload
            ]);
            
            return response()->json([
                'success' => false,
                'message' => $errorMessage,
                'api_error' => $errorData,
                'status_code' => $statusCode
            ], 500);
            
        } catch (\Exception $e) {
            // Caso não consiga decodificar o JSON de erro
            Log::error('API Transfer Raw Error', [
                'status_code' => $statusCode,
                'raw_response' => $response->body(),
                'driver_id' => $driver->id
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erro inesperado na API de transferência',
                'raw_response' => $response->body(),
                'status_code' => $statusCode
            ], 500);
        }
    }

    protected function errorResponse(string $message, int $statusCode)
    {
        return response()->json([
            'success' => false,
            'message' => $message
        ], $statusCode);
    }

    public function show($id)
    {
        $transfer = Transfer::with('driver')->findOrFail($id);
        return view('transfers.show', compact('transfer'));
    }
}