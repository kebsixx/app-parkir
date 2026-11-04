<?php

use App\Http\Controllers\Api\ParkingSessionController;
use App\Http\Controllers\Api\StatsController;
use App\Http\Controllers\Api\VehicleController;
use App\Http\Controllers\Api\VehicleTypeController;
use Illuminate\Support\Facades\Route;

Route::get('/vehicle-types', [VehicleTypeController::class, 'index']);
Route::apiResource('vehicles', VehicleController::class);

Route::get('/parking-sessions', [ParkingSessionController::class, 'index']);
Route::post('/parking-sessions', [ParkingSessionController::class, 'store']);
Route::get('/parking-sessions/{session}', [ParkingSessionController::class, 'show']);
Route::post('/parking-sessions/{session}/checkout', [ParkingSessionController::class, 'checkout']);

Route::get('/stats', [StatsController::class, 'index']);
