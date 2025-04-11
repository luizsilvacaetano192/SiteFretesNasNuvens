<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use App\Models\MessagePush;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use App\Models\Transfer;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Carbon\Carbon;


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



    protected function formatDate($date): string
    {
        if (is_string($date)) {
            return Carbon::parse($date)->format('d/m/Y');
        }
        
        return $date->format('d/m/Y');
    }


        public function show(Driver $driver): View
        {
            $driver->load(['userAccount.transfers' => function($query) {
                $query->with(['freight.company'])
                     ->orderBy('transfer_date', 'desc');
            }]);
    
            $transfers = $driver->userAccount->transfers ?? collect();
    
            // Pré-formata os dados para a view
            $transfers->each(function ($transfer) {
                $transfer->formatted_date = $this->safeFormatDate($transfer->transfer_date);
                $transfer->formatted_amount = number_format($transfer->amount, 2, ',', '.');
                $transfer->type_formatted = $this->formatTransferType($transfer->type);
                $transfer->badge_color = $this->transferBadgeColor($transfer->type);
            });
    
            return view('drivers.balance', [
                'driver' => $driver,
                'transfers' => $transfers,
                'account' => $driver->userAccount
            ]);
        }
    
        
    
            public function show(Driver $driver): View
            {
                try {
                    // Carrega as relações necessárias com tratamento de erros
                    $driver->load(['userAccount.transfers' => function($query) {
                        $query->with(['freight.company'])
                             ->orderBy('transfer_date', 'desc');
                    }]);
        
                    // Obtém a conta ou cria uma instância vazia para evitar erros
                    $account = $this->getUserAccount($driver);
                    
                    // Pré-formata as transferências
                    $transfers = $this->prepareTransfers($account);
        
                    return view('drivers.balance', [
                        'driver' => $driver,
                        'account' => $account,
                        'transfers' => $transfers
                    ]);
        
                } catch (\Exception $e) {
                    Log::error('DriverBalanceController error: ' . $e->getMessage());
                    
                    // Retorna a view com dados vazios em caso de erro
                    return view('drivers.balance', [
                        'driver' => $driver ?? new Driver(),
                        'account' => new UserAccount(),
                        'transfers' => collect(),
                        'error' => 'Ocorreu um erro ao carregar os dados'
                    ]);
                }
            }
        
            /**
             * Obtém a conta do usuário com tratamento seguro
             *
             * @param Driver $driver
             * @return UserAccount
             */
            private function getUserAccount(Driver $driver): UserAccount
            {
                try {
                    return $driver->userAccount ?? new UserAccount();
                } catch (\Exception $e) {
                    Log::warning("Failed to get user account for driver {$driver->id}: " . $e->getMessage());
                    return new UserAccount();
                }
            }
        
            /**
             * Prepara e formata as transferências para exibição
             *
             * @param UserAccount $account
             * @return \Illuminate\Support\Collection
             */
            private function prepareTransfers(UserAccount $account)
            {
                try {
                    $transfers = $account->transfers ?? collect();
                    
                    return $transfers->map(function ($transfer) {
                        return (object)[
                            'id' => $transfer->id ?? null,
                            'transfer_date' => $transfer->transfer_date ?? null,
                            'formatted_date' => $this->safeFormatDate($transfer->transfer_date ?? null),
                            'type' => $transfer->type ?? null,
                            'type_formatted' => $this->formatTransferType($transfer->type ?? ''),
                            'amount' => $transfer->amount ?? 0,
                            'formatted_amount' => number_format($transfer->amount ?? 0, 2, ',', '.'),
                            'description' => $transfer->description ?? 'Transferência',
                            'freight' => $transfer->freight ?? null,
                            'freight_id' => $transfer->freight_id ?? null,
                            'asaas_identifier' => $transfer->asaas_identifier ?? null,
                            'badge_color' => $this->transferBadgeColor($transfer->type ?? ''),
                            'created_at' => $transfer->created_at ?? null,
                            'formatted_created_at' => $this->safeFormatDate($transfer->created_at ?? null, 'd/m/Y H:i')
                        ];
                    });
                    
                } catch (\Exception $e) {
                    Log::warning("Failed to prepare transfers: " . $e->getMessage());
                    return collect();
                }
            }
        
            /**
             * Formata uma data de forma segura
             *
             * @param mixed $date
             * @param string $format
             * @return string
             */
            private function safeFormatDate($date, string $format = 'd/m/Y'): string
            {
                try {
                    if (empty($date)) {
                        return '';
                    }
        
                    if ($date instanceof \Carbon\Carbon) {
                        return $date->format($format);
                    }
        
                    return Carbon::parse($date)->format($format);
                } catch (\Exception $e) {
                    Log::warning("Date formatting failed: " . $e->getMessage());
                    return '';
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
                    'INTERNAL' => 'Interna',
                    'BLOCKED' => 'Bloqueado',
                    'PIX_DEBIT' => 'PIX Débito',
                    'WITHDRAW' => 'Saque',
                    'DEPOSIT' => 'Depósito'
                ];
        
                return $types[$type] ?? $type;
            }
        
            /**
             * Retorna a classe CSS para o badge conforme o tipo
             *
             * @param string $type
             * @return string
             */
            private function transferBadgeColor(string $type): string
            {
                $colors = [
                    'PIX' => 'bg-success',
                    'TED' => 'bg-primary',
                    'DOC' => 'bg-info',
                    'INTERNAL' => 'bg-secondary',
                    'BLOCKED' => 'bg-warning text-dark',
                    'PIX_DEBIT' => 'bg-danger',
                    'WITHDRAW' => 'bg-dark',
                    'DEPOSIT' => 'bg-success'
                ];
        
                return $colors[$type] ?? 'bg-secondary';
            }
        }
    
        private function safeFormatDate($date, string $format = 'd/m/Y'): string
        {
            try {
                if (empty($date)) {
                    return '';
                }
    
                if ($date instanceof \Carbon\Carbon) {
                    return $date->format($format);
                }
    
                return Carbon::parse($date)->format($format);
            } catch (\Exception $e) {
                Log::warning("Date formatting failed for value: " . print_r($date, true));
                return '';
            }
        }
    
        private function formatTransferType(string $type): string
        {
            $types = [
                'PIX' => 'PIX',
                'TED' => 'TED',
                'DOC' => 'DOC',
                'INTERNAL' => 'Interna',
                'BLOCKED' => 'Bloqueado',
                'PIX_DEBIT' => 'PIX Débito',
                'WITHDRAW' => 'Saque',
                'DEPOSIT' => 'Depósito'
            ];
    
            return $types[$type] ?? $type;
        }
    
        private function transferBadgeColor(string $type): string
        {
            $colors = [
                'PIX' => 'bg-success',
                'TED' => 'bg-primary',
                'DOC' => 'bg-info',
                'INTERNAL' => 'bg-secondary',
                'BLOCKED' => 'bg-warning text-dark',
                'PIX_DEBIT' => 'bg-danger',
                'WITHDRAW' => 'bg-dark',
                'DEPOSIT' => 'bg-success'
            ];
    
            return $colors[$type] ?? 'bg-secondary';
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
