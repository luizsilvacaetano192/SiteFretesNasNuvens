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

Route::get('/', function () {
    return view('welcome');
 

});


Route::get('/mapa', [MapaController::class, 'index'])->name('mapa');
Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::post('/mapa/rota', [MapaController::class, 'getRota'])->name('mapa.rota');
//Route::resource('shipments', ShipmentController::class);
Route::get('/shipments/data', [ShipmentController::class, 'getShipments'])->name('shipments.data');
Route::post('/shipments/store', [ShipmentController::class, 'store'])->name('shipments.store');
Route::get('/shipments/create', [ShipmentController::class, 'create'])->name('shipments.create');
Route::get('/shipments/index', [ShipmentController::class, 'index'])->name('shipments.index');
Route::get('/shipments/edit', [ShipmentController::class, 'edit'])->name('shipments.edit');
Route::get('/shipments/destroy', [ShipmentController::class, 'destroy'])->name('shipments.destroy');

Route::get('/companies/create', [CompanyController::class, 'create'])->name('companies.create');
Route::post('/companies/store', [CompanyController::class, 'store'])->name('companies.store');
Route::get('/companies', [CompanyController::class, 'index'])->name('companies.index');
Route::get('/companies/data', [CompanyController::class, 'getData'])->name('companies.data');

Route::post('/drivers/store', [DriverController::class, 'store'])->name('drivers.store');
Route::get('/drivers/create', [DriverController::class, 'create'])->name('drivers.create');
Route::get('/drivers', [DriverController::class, 'index'])->name('drivers.index');
Route::get('/drivers/data', [DriverController::class, 'getData'])->name('drivers.data');
Route::post('/transfer/{driver}', [TransferController::class, 'transfer'])
    ->name('transfer');

Route::get('/drivers/send-push', [DriverController::class, 'showSendPushForm'])->name('drivers.pushForm');
Route::get('/drivers/{id}', [DriverController::class, 'show'])->name('drivers.show');
Route::post('/drivers/send-push', [DriverController::class, 'sendPush'])->name('drivers.sendPush');

Route::prefix('messages-push')->group(function () {
    Route::get('/', [MessagePushController::class, 'index'])->name('messages-push.index');
    Route::get('/list', [MessagePushController::class, 'list'])->name('messages-push.list');
    Route::post('/resend/{id}', [MessagePushController::class, 'resend'])->name('messages-push.resend');
});

Route::get('/drivers/{driver}/balance-data', [DriverController::class, 'balanceData']);
Route::get('/drivers/{driver}/freights', [DriverController::class, 'getDriverFreightsWithDetails']);

Route::get('/transfers', [TransferController::class, 'index']);

Route::post('/drivers/{driver}/activate', [DriverController::class, 'activate']);
Route::post('/drivers/{driver}/block', [DriverController::class, 'block']);
Route::post('/drivers/{id}/update-status', [DriverController::class, 'updateStatus'])->name('drivers.updateStatus');

Route::prefix('settings')->group(function() {
    Route::get('/', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/save', [SettingController::class, 'save'])->name('settings.save');
});

Route::get('/pending-tasks', function () {
    $task = \App\Models\PendingTask::where('seen', false)->orderBy('created_at', 'desc')->get();
    return response()->json($task);
});

Route::post('/pending-tasks/{id}/seen', function ($id) {
    $message = \App\Models\PendingTask::find($id);
    if ($message) {
        $message->seen = true;
        $message->save();
        return response()->json(['success' => true]);
    }
    return response()->json(['success' => false], 404);
});

// No seu backend (ex: Laravel)
Route::get('/trucks', function(Request $request) {
    $driverId = $request->input('driver_id');
    $response = Http::post("https://5lk2dh8nk1.execute-api.us-east-1.amazonaws.com/teste/",
    ["driver_id" => $driverId]
);
    return $response->body();
});

//Route::get('/shipments/{shipment}', [ShipmentController::class, 'show'])->name('shipments.show');
Route::get('/shipments/{shipment}', [ShipmentController::class, 'show'])->name('shipments.show');

Route::get('freights/stats', [FreightController::class, 'getStats'])->name('freights.stats');

Route::get('/shipments/{id}/request-freight', [ShipmentController::class, 'requestFreight'])->name('shipments.requestFreight');

// Defina a rota 'freights' que chama o método 'index' no seu controlador
Route::get('freights', [FreightController::class, 'index'])->name('freights');

Route::post('/freight/store', [FreightController::class, 'store'])->name('freight.store');
Route::post('/freights/store', [FreightController::class, 'store'])->name('freights.store');

Route::get('/freight/create', [FreightController::class, 'create'])->name('freight.create');
Route::get('/freights/data', [FreightController::class, 'getDataTable'])->name('freights.data');


Route::get('/freights/{freight}', [FreightController::class, 'show'])->name('freights.show');
Route::get('/freights/index', [FreightController::class, 'index'])->name('freights.index');



Route::delete('/shipments/clear', [ShipmentController::class, 'clear'])->name('shipments.clear');


Route::get('/freight-statuses', [FreightStatusController::class, 'index'])->name('freight_statuses.index');
Route::get('/freight-statuses/data', [FreightStatusController::class, 'getData'])->name('freight_statuses.data');
Route::get('/freight-statuses/create', [FreightStatusController::class, 'create'])->name('freight_statuses.create');
Route::post('/freight-statuses/store', [FreightStatusController::class, 'store'])->name('freight-statuses.store');



Route::get('/mapa-frete-app', [FreightController::class, 'map'])->name('freights.map');
Route::get('/freights/{freightId}/transport', [FreightController::class, 'transport'])->name('freights.transport');

Route::post('/shipments/{id}/store-freight', [ShipmentController::class, 'storeFreight'])->name('shipments.storeFreight');


Route::delete('/freights/delete-all', [FreightController::class, 'deleteAll'])->name('freights.deleteAll');
Route::delete('/freights/{id}', [FreightController::class, 'destroy'])->name('freights.destroy');


Route::get('shipments', function () {
    $shipments = Shipment::all();
    return view('shipments.index', compact('shipments'));
});

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

Route::get('/freights/{freight}/history', [HistoryController::class, 'getHistory']);



Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

