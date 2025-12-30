<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\ChecklistController;
use App\Http\Controllers\Api\V1\TripController;
use App\Http\Controllers\Api\V1\VehicleLogController;
use Illuminate\Support\Facades\Route;

// API v1
Route::prefix('v1')->group(function () {
    // Rutas públicas
    Route::post('/login', [AuthController::class, 'login']);

    // Rutas protegidas con Sanctum
    Route::middleware('auth:sanctum')->group(function () {
        // Auth
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/me', [AuthController::class, 'me']);

        // Checklist activo
        Route::get('/checklists/active', [ChecklistController::class, 'active']);

        // Vehicle Logs
        Route::post('/vehicle-logs/exit', [VehicleLogController::class, 'storeExit']);
        Route::post('/vehicle-logs/entry', [VehicleLogController::class, 'storeEntry']);
        Route::post('/vehicle-logs/{log}/incidents', [VehicleLogController::class, 'addIncident']);

        // Trips
        Route::get('/trips', [TripController::class, 'index']);
        Route::get('/trips/active', [TripController::class, 'active']);
        Route::post('/trips/{trip}/locations', [TripController::class, 'storeLocations']);
    });
});
