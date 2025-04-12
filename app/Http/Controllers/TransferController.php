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
                Log::warning('Invalid date filter', ['input' => $request->date_range]);
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
        try {
            $validated = $this->validateRequest($request);
            $driver = Driver::findOrFail($driverId);
            $apiPayload = $this->prepareApiPayload($validated, $driver);
            $apiResponse = $this->callExternalApi($apiPayload);
            
            return $this->handleApiResponse($apiResponse, $driver, $validated);
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->errorResponse('Driver not found', 404);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
            
        } catch (\Exception $e) {
            Log::error('Transfer Error: '.$e->getMessage(), [
                'driver_id' => $driverId,
                'trace' => $e->getTraceAsString()
            ]);
            return $this->errorResponse('Server error: '.$e->getMessage(), 500);
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
                        $fail('Amount must be numeric');
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
            'description' => (string)($validated['description'] ?? 'System transfer'),
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
            'timeout' => 3000
        ])
        ->withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ])
        ->post('https://4fuy7ttno9.execute-api.us-east-1.amazonaws.com/teste', $payload);
    }

    protected function handleApiResponse($response, Driver $driver, array $validated)
    {
        if ($response->status() === 200) {
            print_r($response);die;
            try {
                $responseData = $response->json();
                print_r($responseData['success']);die;
                if (isset($responseData['success']) && $responseData['success'] == true) {
                    die('esta aqs');

                    return response()->json([
                        'success' => true,
                        'message' => $responseData['message'] ?? 'Transfer completed successfully',
                        'data' => [
                            'amount' => $validated['amount'],
                            'new_balance' => $responseData['data']['new_balance'] ?? null,
                            'asaas_id' => $responseData['data']['asaas_id'] ?? null,
                            'driver_id' => $driver->id
                        ]
                    ]);
                }
                
                if (isset($responseData['error']) && $responseData['error'] === true) {
                    return $this->handleApiError($responseData, $driver);
                }
                
                Log::warning('Unknown API response format', [
                    'driver_id' => $driver->id,
                    'response' => $responseData
                ]);
                
                return $this->errorResponse('Unknown API response format', 502);
                
            } catch (\Exception $e) {
                Log::error('API response processing failed', [
                    'driver_id' => $driver->id,
                    'error' => $e->getMessage(),
                    'response' => $response->body()
                ]);
                return $this->errorResponse('Failed to process API response', 500);
            }
        }
        
        return $this->handleHttpError($response, $driver);
    }

    protected function handleApiError(array $responseData, Driver $driver)
    {
        Log::error('API transfer error', [
            'driver_id' => $driver->id,
            'response' => $responseData
        ]);
        
        return response()->json([
            'success' => false,
            'message' => $responseData['message'] ?? 'Transfer failed',
            'api_error' => $responseData
        ], 200);
    }

    protected function handleHttpError($response, $driver)
    {
        try {
            $errorData = $response->json();
            $errorMessage = $errorData['message'] ?? 'API communication error';
            
            Log::error('API HTTP error', [
                'status' => $response->status(),
                'driver_id' => $driver->id,
                'response' => $errorData
            ]);
            
            return response()->json([
                'success' => false,
                'message' => $errorMessage,
                'status_code' => $response->status()
            ], $response->status());
            
        } catch (\Exception $e) {
            Log::error('Failed to parse API error', [
                'status' => $response->status(),
                'driver_id' => $driver->id,
                'raw_response' => $response->body()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Unexpected API error',
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