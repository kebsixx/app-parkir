<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ParkingSessionController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\VehicleTypeController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])
        ->middleware('throttle:5,1')
        ->name('login.store');
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

Route::middleware('auth')->group(function () {
    Route::redirect('/', '/dashboard');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/parking/active', [ParkingSessionController::class, 'index'])->name('parking.active');
    Route::get('/parking/check-in', [ParkingSessionController::class, 'create'])->name('parking.check-in');
    Route::post('/parking/check-in', [ParkingSessionController::class, 'store'])->name('parking.check-in.store');
    Route::get('/parking/{session}/checkout', [ParkingSessionController::class, 'checkout'])->name('parking.checkout');
    Route::post('/parking/{session}/checkout', [ParkingSessionController::class, 'processCheckout'])->name('parking.checkout.process');
    Route::get('/parking/{session}/success', [ParkingSessionController::class, 'success'])->name('parking.success');

    Route::resource('vehicles', VehicleController::class);

    Route::middleware('admin')->group(function () {
        Route::resource('vehicle-types', VehicleTypeController::class)
            ->except(['show'])
            ->parameters(['vehicle-types' => 'vehicleType']);
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    });
});
