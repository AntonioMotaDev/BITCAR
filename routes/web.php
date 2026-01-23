<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\VehicleController;
use App\Http\Controllers\Web\UserController;
use App\Http\Controllers\Web\ChecklistController;
use App\Http\Controllers\Web\VehicleLogController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Vehicles - Solo admin
    Route::middleware('can:viewAny,App\Models\Vehicle')->group(function () {
        Route::resource('vehicles', VehicleController::class);
        Route::post('vehicles/documents/upload', [VehicleController::class, 'uploadDocument'])->name('vehicles.documents.store');
    });

    // Users - Solo admin
    Route::middleware('can:viewAny,App\Models\User')->group(function () {
        Route::resource('users', UserController::class);
        Route::post('users/documents/upload', [UserController::class, 'uploadDocument'])->name('users.documents.store');
    });

    // Checklists - Admin y supervisor
    Route::middleware('can:viewAny,App\Models\Checklist')->group(function () {
        Route::resource('checklists', ChecklistController::class);
    });

    // Vehicle Logs
    Route::prefix('vehicle-logs')->name('vehicle-logs.')->group(function () {
        Route::get('/', [VehicleLogController::class, 'index'])->name('index');
        Route::get('/{vehicleLog}', [VehicleLogController::class, 'show'])->name('show');
    });
});

require __DIR__.'/auth.php';
