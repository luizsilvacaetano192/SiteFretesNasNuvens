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

    public function balanceData($driver): JsonResponse
    {
        try {
            // Se não for instância de Driver, carrega o modelo
            if (!$driver instanceof Driver) {
                $driver = Cache::remember("driver_{$driver}", now()->addHour(), function() use ($driver) {
                    return Driver::with('userAccount')->findOrFail($driver);
                });
            }

            // Verifica se o motorista tem conta associada
            if (!$driver->userAccount) {
                return response()->json([
                    'error' => 'Conta não encontrada para este motorista',
                    'account_status' => 'not_found'
                ], 404);
            }

            // Obtém os dados da conta
            $account = $driver->userAccount;
            
            // Carrega as transferências com cache de 5 minutos
            $transfers = Cache::remember("driver_transfers_{$driver->id}", now()->addMinutes(5), function() use ($account) {
                return $account->transfers()
                    ->select([
                        'id',
                        'type',
                        'amount',
                        'description',
                        'transfer_date',
                        'asaas_identifier',
                        'created_at'
                    ])
                    ->orderBy('transfer_date', 'desc')
                    ->get();
            });

            // Formata a resposta
            return response()->json([
                'account' => [
                    'asaas_identifier' => $account->asaas_identifier,
                    'total_balance' => (float) $account->total_balance,
                    'blocked_balance' => (float) $account->blocked_balance,
                    'available_balance' => (float) $account->available_balance,
                    'last_updated' => $account->updated_at->toDateTimeString(),
                ],
                'transfers' => $transfers->map(function ($transfer) {
                    return [
                        'id' => $transfer->id,
                        'type' => $transfer->type,
                        'amount' => (float) $transfer->amount,
                        'description' => $transfer->description,
                        'transfer_date' => $transfer->transfer_date,
                        'asaas_identifier' => $transfer->asaas_identifier,
                        'month_year' => date('m/Y', strtotime($transfer->transfer_date)),
                        'month_name' => ucfirst(\Carbon\Carbon::parse($transfer->transfer_date)->formatLocalized('%B %Y')),
                        'created_at' => $transfer->created_at->toDateTimeString(),
                    ];
                }),
                'summary' => [
                    'total_transfers' => $transfers->count(),
                    'total_amount' => (float) $transfers->sum('amount'),
                    'last_transfer_date' => optional($transfers->first())->transfer_date,
                ]
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error("Motorista não encontrado: " . $e->getMessage());
            return response()->json([
                'error' => 'Motorista não encontrado',
                'code' => 'driver_not_found'
            ], 404);

        } catch (\Exception $e) {
            Log::error("Erro ao buscar saldo do motorista: " . $e->getMessage());
            return response()->json([
                'error' => 'Erro interno ao processar sua requisição',
                'message' => $e->getMessage(),
                'code' => 'internal_error'
            ], 500);
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
