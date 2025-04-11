<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use App\Models\MessagePush;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;


class DriverController extends Controller
{
    public function index()
    {
        return view('drivers.index');
    }

    public function updateStatus(Request $request, $id)
    {
        $driver = Driver::findOrFail($id);
        $driver->status = $request->status;
        $driver->reason = $request->reason ?? null;
        $driver->save();

        return response()->json(['success' => true]);
    }


    
    class DriverController extends Controller
    {
        /**
         * Retorna os dados de saldo e transferências do motorista
         * Inclui transferências com e sem frete associado
         *
         * @param int|Driver $driver ID ou instância do Driver
         * @return JsonResponse
         */
        public function balanceData($driver): JsonResponse
        {
            try {
                // Carrega o motorista com suas transferências
                if (!$driver instanceof Driver) {
                    $driver = Driver::with(['userAccount.transfers.freight.company'])
                        ->findOrFail($driver);
                }
    
                // Verifica se existe conta associada
                if (!$driver->userAccount) {
                    return response()->json([
                        'success' => false,
                        'error' => 'Conta não encontrada para este motorista',
                        'account_status' => 'not_found'
                    ], 404);
                }
    
                $account = $driver->userAccount;
                
                // Formata todas as transferências
                $formattedTransfers = $account->transfers
                    ->sortByDesc('transfer_date')
                    ->map(function ($transfer) use ($driver) {
                        // Informações base
                        $transferData = [
                            'motorista' => $driver->name,
                            'motorista_id' => $driver->id,
                            'transferencia_id' => $transfer->id,
                            'data' => $transfer->transfer_date->format('d/m/Y'),
                            'data_iso' => $transfer->transfer_date->format('Y-m-d'),
                            'tipo' => $this->formatTransferType($transfer->type),
                            'valor' => (float) $transfer->amount,
                            'valor_formatado' => 'R$ ' . number_format($transfer->amount, 2, ',', '.'),
                            'descricao' => $transfer->description ?? 'Transferência',
                            'asaas_id' => $transfer->asaas_identifier ?? null,
                            'created_at' => $transfer->created_at->format('d/m/Y H:i')
                        ];
    
                        // Informações do frete (se existir)
                        if ($transfer->freight) {
                            $transferData['frete'] = [
                                'frete_id' => $transfer->freight->id,
                                'cliente' => optional($transfer->freight->company)->name ?? 'Cliente não informado',
                                'cliente_id' => optional($transfer->freight->company)->id
                            ];
                        } else {
                            $transferData['frete'] = [
                                'frete_id' => null,
                                'cliente' => 'Não vinculado a frete',
                                'cliente_id' => null
                            ];
                        }
    
                        return $transferData;
                    });
    
                // Calcula totais
                $totalTransferencias = $formattedTransfers->count();
                $totalValor = $formattedTransfers->sum('valor');
    
                return response()->json([
                    'success' => true,
                    'data' => [
                        'motorista' => [
                            'id' => $driver->id,
                            'nome' => $driver->name,
                            'conta_asaas' => $account->asaas_identifier
                        ],
                        'saldos' => [
                            'total' => (float) $account->total_balance,
                            'total_formatado' => 'R$ ' . number_format($account->total_balance, 2, ',', '.'),
                            'bloqueado' => (float) $account->blocked_balance,
                            'bloqueado_formatado' => 'R$ ' . number_format($account->blocked_balance, 2, ',', '.'),
                            'disponivel' => (float) $account->available_balance,
                            'disponivel_formatado' => 'R$ ' . number_format($account->available_balance, 2, ',', '.'),
                            'ultima_atualizacao' => $account->updated_at->format('d/m/Y H:i')
                        ],
                        'transferencias' => $formattedTransfers->values(), // Reindexa o array
                        'totalizadores' => [
                            'quantidade' => $totalTransferencias,
                            'valor_total' => $totalValor,
                            'valor_total_formatado' => 'R$ ' . number_format($totalValor, 2, ',', '.')
                        ]
                    ]
                ]);
    
            } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
                return response()->json([
                    'success' => false,
                    'error' => 'Motorista não encontrado',
                    'message' => $e->getMessage()
                ], 404);
    
            } catch (\Exception $e) {
                Log::error("Erro no balanceData - " . $e->getMessage());
                return response()->json([
                    'success' => false,
                    'error' => 'Erro ao processar requisição',
                    'message' => $e->getMessage()
                ], 500);
            }
        }
    
        /**
         * Formata o tipo de transferência para exibição
         * 
         * @param string $type
         * @return string
         */
        private function formatTransferType(string $type): string
        {
            $types = [
                'PIX' => 'PIX',
                'TED' => 'TED',
                'DOC' => 'DOC',
                'INTERNAL' => 'Transferência Interna',
                'BLOCKED' => 'Valor Bloqueado',
                'PIX_DEBIT' => 'Débito via PIX',
                'WITHDRAW' => 'Saque',
                'DEPOSIT' => 'Depósito'
            ];
    
            return $types[$type] ?? $type;
        }
    }
}


    public function showSendPushForm()
    {
        $drivers = Driver::select('id', 'name',  'address', 'token_push')->get();
        $enderecos = $drivers->pluck('address')->filter()->unique();

        $estados = $enderecos->map(function ($address) {
            // Assume formato: "... - Estado"
            $parts = explode('-', $address);
            return trim(end($parts));
        })->unique()->filter()->values();
        
        $cidades = $enderecos->map(function ($address) {
            // Assume formato: "... , Cidade - Estado"
            $parts = explode(',', $address);
            if (count($parts) >= 2) {
                $cidadeEstado = trim(end($parts)); // "Cidade - Estado"
                $cidade = explode('-', $cidadeEstado)[0];
                return trim($cidade);
            }
            return null;
        })->unique()->filter()->values();

        return view('drivers.drivers-push', compact('drivers', 'estados', 'cidades'));
    }

    public function balanceInfo($id)
    {
        $driver = Driver::findOrFail($id);
        $account = $driver->userAccount;
        $transfers = $account ? $account->transfers()->get() : collect();

        return response()->json([
            'account' => $account,
            'transfers' => $transfers
        ]);
    }

    public function sendPush(Request $request)
    {
        $request->validate([
            'title'   => 'required|string',
            'message' => 'required|string',
            'screen'  => 'nullable|string',
            'drivers' => 'required|array',
        ]);
    
        $titulo      = $request->input('title');
        $mensagem    = $request->input('message');
        $screen      = $request->input('screen');
        $motoristaIds = $request->input('drivers');
    
        $resultados = [];
    
        foreach ($motoristaIds as $id) {
            $motorista = Driver::find($id);
    
            if ($motorista) {
                try {
                    MessagePush::create([
                        'driver_id' => $motorista->id,
                        'titulo'    => $titulo,
                        'texto'     => $mensagem,
                        'token'     => $motorista->token_push,
                        'data'      => Carbon::now(),
                        'send'      => false,
                        'type'      => 'info',
                        'screen'    => $screen,
                    ]);
    
                    $resultados[] = "✅ Mensagem registrada para {$motorista->name}";
                } catch (\Exception $e) {
                    Log::error("Erro ao salvar push para {$motorista->name}: " . $e->getMessage());
                    $resultados[] = "❌ Erro ao salvar mensagem para {$motorista->name}";
                }
            } else {
                $resultados[] = "⚠️ Motorista com ID {$id} não encontrado";
            }
        }
    
        return response()->json([
            'message'    => 'Envio concluído!',
            'resultados' => $resultados
        ]);
    }

    public function getData()
    {
        $drivers = Driver::all();

        return DataTables::of($drivers)
            ->addColumn('driver_license_front', fn($driver) => $driver->driver_license_front_url)
            ->addColumn('driver_license_back', fn($driver) => $driver->driver_license_back_url)
            ->addColumn('face_photo', fn($driver) => $driver->face_photo_url)
            ->addColumn('address_proof', fn($driver) => $driver->address_proof_url)
            ->toJson();
    ;

        return DataTables::of($drivers)->make(true);
    }

    public function data(Request $request)
    {
        $query = Driver::query();

        return DataTables::of($query)
            ->addColumn('driver_license_front', fn($driver) => $driver->driver_license_front_url)
            ->addColumn('driver_license_back', fn($driver) => $driver->driver_license_back_url)
            ->addColumn('face_photo', fn($driver) => $driver->face_photo_url)
            ->addColumn('address_proof', fn($driver) => $driver->address_proof_url)
            ->toJson();
    }

    public function create()
    {
        return view('drivers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'cpf' => 'required|string|unique:drivers,cpf|size:14', // 14 caracteres com máscara
            'phone' => 'nullable|string|size:15', // 15 caracteres com máscara
            'license_number' => 'required|string|unique:drivers,license_number|max:20',
            'license_category' => 'required|string|max:3',
        ]);

        Driver::create($request->all());

        return redirect()->route('drivers.index')->with('success', 'Driver registered successfully.');
    }

    public function edit(Driver $driver)
    {
        return view('drivers.edit', compact('driver'));
    }

    public function update(Request $request, Driver $driver)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'cpf' => 'required|string|size:14|unique:drivers,cpf,' . $driver->id,
            'phone' => 'nullable|string|size:15',
            'license_number' => 'required|string|max:20|unique:drivers,license_number,' . $driver->id,
            'license_category' => 'required|string|max:3',
        ]);

        $driver->update($request->all());

        return redirect()->route('drivers.index')->with('success', 'Driver updated successfully.');
    }

    public function destroy(Driver $driver)
    {
        $driver->delete();
        return redirect()->route('drivers.index')->with('success', 'Driver deleted successfully.');
    }
}
