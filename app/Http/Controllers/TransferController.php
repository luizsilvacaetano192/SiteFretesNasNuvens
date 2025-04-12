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
    protected $lastRequestPayload;

    public function index(Request $request)
    {
        $transfers = Transfer::with('driver')->query();

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

        if ($request->filled('freight_id')) {
            $transfers->where('freight_id', $request->freight_id);
        }

        if ($request->filled('type')) {
            $transfers->where('type', $request->type);
        }

        if ($request->filled('driver_id')) {
            $transfers->where('driver_id', $request->driver_id);
        }

        if ($request->filled('status')) {
            $transfers->where('status', $request->status);
        }

        $transfers = $transfers->orderBy('created_at', 'desc')->paginate(50);
        return view('transfers.index', compact('transfers'));
    }

    public function transfer(Request $request, $driverId)
    {
        DB::beginTransaction();
        
        try {
            $validated = $this->validateRequest($request);
            $driver = Driver::findOrFail($driverId);
            $apiPayload = $this->prepareApiPayload($validated, $driver);
            $apiResponse = $this->callExternalApi($apiPayload);
            
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

        $this->lastRequestPayload = $payload;
        return $payload;
    }

    protected function callExternalApi(array $payload)
    {
        return Http::withOptions([
            'debug' => config('app.debug'),
            'verify' => config('app.env') === 'production' ? storage_path('certs/cacert.pem') : false,
            'timeout' => 30
        ])
        ->withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'X-Requested-With' => 'XMLHttpRequest'
        ])
        ->timeout(30)
        ->retry(3, 1000)
        ->post('https://4fuy7ttno9.execute-api.us-east-1.amazonaws.com/teste', $payload);
    }

    protected function handleApiResponse($response, Driver $driver, array $validated)
    {
        // Check for HTTP 200 status
        if ($response->status() === 200) {
            try {
                $responseData = $response->json();
                
                // Check if error is false or not present
                if (!isset($responseData['error']) || $responseData['error'] === false) {
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
                        'data' => $transfer
                    ]);
                }
                
                // HTTP 200 but error is true
                Log::error('API Error', [
                    'driver_id' => $driver->id,
                    'response' => $responseData,
                    'payload' => $this->lastRequestPayload
                ]);
                
                DB::rollBack();
                
                return response()->json([
                    'success' => false,
                    'message' => $responseData['message'] ?? 'Erro na transferência',
                    'api_response' => $responseData
                ], 200); // Maintain 200 status as that's what API returned
                
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('API Response Parsing Error', [
                    'driver_id' => $driver->id,
                    'error' => $e->getMessage(),
                    'response' => $response->body()
                ]);
                
                return $this->errorResponse('Erro ao processar resposta da API', 500);
            }
        }
        
        // Handle non-200 responses
        return $this->handleApiError($response, $driver);
    }

    protected function handleApiError($response, $driver)
    {
        DB::rollBack();
        
        try {
            $errorData = $response->json();
            $errorMessage = $errorData['message'] ?? 'Erro na API de transferência';
            
            Log::error('API Transfer Error', [
                'status_code' => $response->status(),
                'driver_id' => $driver->id,
                'api_response' => $errorData
            ]);
            
            return response()->json([
                'success' => false,
                'message' => $errorMessage,
                'api_error' => $errorData,
                'status_code' => $response->status()
            ], $response->status());
            
        } catch (\Exception $e) {
            Log::error('API Transfer Raw Error', [
                'status_code' => $response->status(),
                'driver_id' => $driver->id,
                'raw_response' => $response->body()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erro inesperado na API de transferência',
                'raw_response' => $response->body(),
                'status_code' => $response->status()
            ], $response->status());
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