<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Shipment;
use App\Http\Controllers\ShipmentController;
use App\Http\Controllers\FreightController;
use App\Http\Controllers\DriverAnalysisController;


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


Route::post('/analyze-driver', [DriverAnalysisController::class, 'analyze']);


Route::get('/shipments', [ShipmentController::class, 'getAllShipments']);

Route::get('/freights', [FreightController::class, 'getFreights']); 

Route::post('/freights/{id}/update-position', [FreightController::class, 'updatePosition']);
