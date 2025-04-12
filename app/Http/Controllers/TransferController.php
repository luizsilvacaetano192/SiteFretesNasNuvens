<?php

namespace App\Http\Controllers;

use App\Models\Transfer;
use App\Models\Driver;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class TransferController extends Controller
{
    public function index(Request $request)
    {
        $transfers = Transfer::query();

        // Filtro por período de datas
        if ($request->filled('date_range')) {
            try {
                [$start, $end] = explode(' - ', $request->date_range);

                $startDate = Carbon::createFromFormat('d/m/Y', trim($start))->startOfDay();
                $endDate = Carbon::createFromFormat('d/m/Y', trim($end))->endOfDay();

                $transfers->whereBetween('created_at', [$startDate, $endDate]);
            } catch (\Exception $e) {
                // Se erro na conversão de data, ignora o filtro
            }
        }

        // Outros filtros podem ser aplicados aqui, exemplo:
        if ($request->filled('freight_id')) {
            $transfers->where('freight_id', $request->freight_id);
        }

        if ($request->filled('type')) {
            $transfers->where('type', $request->type);
        }

        // Ordenação e paginação
        $transfers = $transfers->orderBy('created_at', 'desc')->paginate(50);

        return view('transfers.index', compact('transfers'));
    }

    public function transfer(Request $request, $driverId)
    {
       
        // Validação dos dados de entrada
        $validated = $this->validateRequest($request);

        try {
            // Busca o motorista com verificação
            $driver = Driver::findOrFail($driverId);
            
            // Prepara os dados para a API externa
            $apiPayload = $this->prepareApiPayload($validated, $driver);
            
            // Faz a requisição para a API externa
            $apiResponse = $this->callExternalApi($apiPayload);
            
            // Processa a resposta
            return $this->handleApiResponse($apiResponse, $driver, $validated);
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->errorResponse('Motorista não encontrado', 404);
            
        } catch (\Exception $e) {
            Log::error('Transfer Error: '.$e->getMessage(), [
                'driver_id' => $driverId,
                'trace' => $e->getTraceAsString()
            ]);
            return $this->errorResponse('Erro interno no servidor', 500);
        }
    }

    protected function validateRequest(Request $request)
    {
        return $request->validate([
            'type' => 'required|in:available_balance,blocked_balance',
            'amount' => 'required|numeric|min:0.01|max:999999.99',
            'description' => 'nullable|string|max:255',
            'freight_value' => 'nullable|numeric|min:0'
        ]);
    }

    protected function prepareApiPayload(array $validated, Driver $driver)
    {
        $payload = [
            'driver_id' => $driver->id,
            'type' => $validated['type'],
            'amount' => $validated['amount'],
            'description' => $validated['description'] ?? 'Transferência realizada pelo sistema',
            'transfer_date' =>  Carbon::now()->toDateString()
        ];
        
        return $payload;
    }

    protected function callExternalApi(array $payload)
    {
        return Http::withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ])
        ->post('https://4fuy7ttno9.execute-api.us-east-1.amazonaws.com/teste', $payload);
    }

    protected function handleApiResponse($response, Driver $driver, array $validated)
    {
        if ($response->successful()) {
            $responseData = $response->json();
            
            return response()->json([
                'success' => true,
                'message' => 'Transferência realizada com sucesso',
                'data' => [
                    'transfer_id' => $transfer->id,
                    'amount' => $transfer->amount,
                    'external_reference' => $transfer->external_reference,
                    'processed_at' => $transfer->created_at
                ]
            ]);
        }

        // Tratamento de erros da API
        $statusCode = $response->status();
        $errorMessage = 'Erro na API de transferência';
        
        if ($statusCode === 401) {
            $errorMessage = 'Não autorizado - verifique as credenciais da API';
        } elseif ($statusCode === 422) {
            $errorData = $response->json();
            $errorMessage = $errorData['message'] ?? 'Dados inválidos enviados para a API';
        }

        Log::error('API Transfer Error', [
            'status' => $statusCode,
            'response' => $response->body(),
            'driver_id' => $driver->id
        ]);

        return $this->errorResponse($errorMessage, $statusCode);
    }

  

    protected function errorResponse(string $message, int $statusCode)
    {
        return response()->json([
            'success' => false,
            'message' => $message
        ], $statusCode);
    }

}
