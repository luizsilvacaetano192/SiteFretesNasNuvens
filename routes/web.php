<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MapaController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ShipmentController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\FreightController;
use App\Http\Controllers\FreightStatusController;
use App\Models\Shipment;
use App\Models\Freight;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\MessagePushController;
use App\Http\Controllers\TransferController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\SendSmsController;
use App\Http\Controllers\TruckController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DriverAnalysisController;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Rotas Públicas (acessíveis sem autenticação)
Route::middleware(['guest', 'throttle:5,1'])->group(function () {
    // Rota única para exibir o formulário e processar login
    Route::get('/login', [AuthController::class, 'index'])->name('login.form');
    Route::post('/login', [AuthController::class, 'handleLogin'])->name('login');

    
    // Redireciona raiz para login
    Route::redirect('/', '/login');
});

Route::middleware(['auth'])->group(function () {
    // Rota home padrão
    Route::get('/home', [App\Http\Controllers\FreightController::class, 'index'])
        ->name('home');
    
    // ... suas outras rotas autenticadas
});

// Rotas Protegidas (requerem autenticação)
Route::middleware(['auth', 'verified'])->group(function () {
    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    Route::get('/driver-truck-details/{freightsDriver}', [FreightController::class, 'getDriverTruckDetails']);

    // Shipments
    Route::prefix('shipments')->group(function () {
        Route::get('/data', [ShipmentController::class, 'getShipments'])->name('shipments.data');
        Route::post('/store', [ShipmentController::class, 'store'])->name('shipments.store');
        Route::get('/create', [ShipmentController::class, 'create'])->name('shipments.create');
        Route::get('/index', [ShipmentController::class, 'index'])->name('shipments.index');
        Route::get('/edit', [ShipmentController::class, 'edit'])->name('shipments.edit');
        Route::get('/destroy', [ShipmentController::class, 'destroy'])->name('shipments.destroy');
        Route::get('/{shipment}', [ShipmentController::class, 'show'])->name('shipments.show');
        Route::get('/{id}/request-freight', [ShipmentController::class, 'requestFreight'])->name('shipments.requestFreight');
        Route::post('/{id}/store-freight', [ShipmentController::class, 'storeFreight'])->name('shipments.storeFreight');
        Route::delete('/clear', [ShipmentController::class, 'clear'])->name('shipments.clear');
    });

    // Companies
    Route::prefix('companies')->group(function () {
        Route::get('/create', [CompanyController::class, 'create'])->name('companies.create');
        Route::post('/store', [CompanyController::class, 'store'])->name('companies.store');
        Route::get('/', [CompanyController::class, 'index'])->name('companies.index');
        Route::get('/data', [CompanyController::class, 'getData'])->name('companies.data');
        Route::get('/list', [CompanyController::class, 'list'])->name('companies.list');
    });

    // Drivers
    Route::prefix('drivers')->group(function () {
        Route::delete('/{id}', [DriverController::class, 'destroy'])->name('drivers.destroy');
        Route::get('/{id}/analyze', [DriverAnalysisController::class, 'analyze']);
        Route::post('/store', [DriverController::class, 'store'])->name('drivers.store');
        Route::get('/create', [DriverController::class, 'create'])->name('drivers.create');
        Route::get('/', [DriverController::class, 'index'])->name('drivers.index');
        Route::get('/data', [DriverController::class, 'getData'])->name('drivers.data');
        Route::get('/list', [DriverController::class, 'list'])->name('drivers.list');
        Route::get('/send-push', [DriverController::class, 'showSendPushForm'])->name('drivers.pushForm');
        Route::get('/{id}', [DriverController::class, 'show'])->name('drivers.show');
        Route::post('/send-push', [DriverController::class, 'sendPush'])->name('drivers.sendPush');
        Route::get('/{driver}/balance-data', [DriverController::class, 'balanceData']);
        Route::get('/{driver}/freights', [DriverController::class, 'getDriverFreightsWithDetails']);
        Route::post('/{driver}/activate', [DriverController::class, 'activate']);
        Route::post('/{driver}/block', [DriverController::class, 'block']);
        Route::post('/{id}/update-status', [DriverController::class, 'updateStatus'])->name('drivers.updateStatus');
    });

    // Transfers
    Route::prefix('transfers')->group(function () {
        Route::post('/{driver}', [TransferController::class, 'transfer'])->name('transfer');
        Route::get('/', [TransferController::class, 'index'])->name('transfers.index');
    });

    // SMS
    Route::post('/send-sms', [SendSmsController::class, 'store'])->name('sendSms.store');

    // Messages Push
    Route::prefix('messages-push')->group(function () {
        Route::get('/', [MessagePushController::class, 'index'])->name('messages-push.index');
        Route::get('/list', [MessagePushController::class, 'list'])->name('messages-push.list');
        Route::post('/resend/{id}', [MessagePushController::class, 'resend'])->name('messages-push.resend');
    });

    // Settings
    Route::prefix('settings')->group(function() {
        Route::get('/', [SettingController::class, 'index'])->name('settings.index');
        Route::post('/save', [SettingController::class, 'save'])->name('settings.save');
    });


    Route::get('freights/{freight}/history', [FreightController::class, 'history'])
    ->name('freights.history');

    // Trucks
    Route::prefix('trucks')->group(function () {
        Route::get('/', function(Request $request) {
            $driverId = $request->input('driver_id');
            $response = Http::post(
                "https://5lk2dh8nk1.execute-api.us-east-1.amazonaws.com/teste/",
                ["driver_id" => $driverId]
            );
            return $response->body();
        });
        Route::post('/toggle-status', [TruckController::class, 'toggleStatus'])->name('trucks.toggleStatus');
    });

    // Freights
    Route::prefix('freights')->group(function () {
        Route::get('/data', [FreightController::class, 'getDataTable'])->name('freights.data');
        Route::get('/stats', [FreightController::class, 'getStats'])->name('freights.stats');
        Route::get('/statuses', [FreightController::class, 'getStatuses'])->name('freights.statuses');
        Route::get('/{id}', [FreightController::class, 'show'])->name('freights.show');
        Route::get('/', [FreightController::class, 'index'])->name('freights.index');
        Route::post('/store', [FreightController::class, 'store'])->name('freights.store');
        Route::get('/create', [FreightController::class, 'create'])->name('freights.create');
        Route::delete('/delete-all', [FreightController::class, 'deleteAll'])->name('freights.deleteAll');
        Route::delete('/{id}', [FreightController::class, 'destroy'])->name('freights.destroy');
        Route::put('/{freightsDriver}/update-status', [FreightController::class, 'updateStatus'])->name('freights.update-status');
        Route::get('/mapa-frete-app', [FreightController::class, 'map'])->name('freights.map');
        Route::get('/{freightId}/transport', [FreightController::class, 'transport'])->name('freights.transport');
    });

   
    // Freight Statuses
    Route::prefix('freight-statuses')->group(function () {
        Route::get('/', [FreightStatusController::class, 'index'])->name('freight_statuses.index');
        Route::get('/data', [FreightStatusController::class, 'getData'])->name('freight_statuses.data');
        Route::get('/create', [FreightStatusController::class, 'create'])->name('freight_statuses.create');
        Route::post('/store', [FreightStatusController::class, 'store'])->name('freight-statuses.store');
    });

    Route::get('/freights/{freight}/last-position', [FreightController::class, 'lastPosition'])
    ->name('freights.last-position');

    // Pending Tasks
    Route::prefix('pending-tasks')->group(function () {
        Route::get('/', function () {
            $task = \App\Models\PendingTask::where('seen', false)
                ->orderBy('created_at', 'desc')
                ->get();
            return response()->json($task);
        });
        
        Route::post('/{id}/seen', function ($id) {
            $message = \App\Models\PendingTask::find($id);
            if ($message) {
                $message->seen = true;
                $message->save();
                return response()->json(['success' => true]);
            }
            return response()->json(['success' => false], 404);
        });
    });

    // Freight Position
    Route::get('/freights/{id}/position', function ($id) {
        $freight = Freight::find($id);
        if ($freight && $freight->current_position) {
            return response()->json([
                'success' => true,
                'position' => $freight->current_position,
                'current_lat' => $freight->current_lat,
                'current_lng' => $freight->current_lng
            ]);
        }
        return response()->json(['success' => false, 'position' => null]);
    });

    // Additional Shipments Route
    Route::get('/shipments', function () {
        $shipments = Shipment::all();
        return view('shipments.index', compact('shipments'));
    });
});

Route::get('/institucional', function () {
   
    return view('institucional');
});