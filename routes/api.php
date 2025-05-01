<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Shipment;
use App\Http\Controllers\ShipmentController;
use App\Http\Controllers\FreightController;
use App\Http\Controllers\DriverAnalysisController;
use App\Http\Controllers\DriverController;
use Illuminate\Support\Facades\Storage;
use App\Models\Driver;
use App\Models\Truck;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/driver-truck-details/{driver}/{truck}', function(Driver $driver, Truck $truck) {
    return response()->json([
        'driver' => $driver->append([
            'driver_license_front_url',
            'driver_license_back_url',
            'face_photo_url'
        ]),
        'truck' => $truck,
        'implements' => $truck->implements->map(function($implement) {
            $implement->photo_url = $implement->photo 
                ? Storage::disk('s3')->url($implement->photo) 
                : null;
            return $implement;
        })
    ]);
})->middleware('auth:api'); // Add appropriate middleware

Route::post('/analyze-driver', [DriverAnalysisController::class, 'analyze']);

Route::get('/shipments', [ShipmentController::class, 'getAllShipments']);

Route::get('/freights', [FreightController::class, 'getFreights']); 

Route::post('/freights/{id}/update-position', [FreightController::class, 'updatePosition']);

Route::post('/create-asaas-account', [DriverController::class, 'createAsaasAccount']);
