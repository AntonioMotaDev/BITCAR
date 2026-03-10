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
    Route::get('/dashboard/logs/{vehicleLog}/modal-data', [DashboardController::class, 'logModalData'])->name('dashboard.logs.modal-data');
    Route::post('/trip-requests/{vehicleLog}/approve', [DashboardController::class, 'approveTrip'])->name('trips.approve');
    Route::post('/trip-requests/{vehicleLog}/reject', [DashboardController::class, 'rejectTrip'])->name('trips.reject');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Vehicles - Solo admin
    Route::middleware('can:viewAny,App\Models\Vehicle')->group(function () {
        Route::resource('vehicles', VehicleController::class);
        Route::post('vehicles/documents/upload', [VehicleController::class, 'uploadDocument'])->name('vehicles.documents.store');
        Route::post('vehicles/assignment/store', [VehicleController::class, 'storeAssignment'])->name('vehicles.assignment.store');
        Route::put('vehicles/assignment/{assignment}', [VehicleController::class, 'updateAssignment'])->name('vehicles.assignment.update');
        Route::delete('vehicles/assignment/{assignment}', [VehicleController::class, 'destroyAssignment'])->name('vehicles.assignment.destroy');
    });

    // Users - Solo admin
    Route::middleware('can:viewAny,App\Models\User')->group(function () {
        Route::resource('users', UserController::class);
        Route::post('users/documents/upload', [UserController::class, 'uploadDocument'])->name('users.documents.store');
    });

    // Checklists - Admin y supervisor
    Route::middleware('can:viewAny,App\Models\Checklist')->group(function () {
        Route::resource('checklists', ChecklistController::class);
        Route::post('checklists/{checklist}/duplicate', [ChecklistController::class, 'duplicate'])->name('checklists.duplicate');
    });

    // Vehicle Logs
    Route::prefix('vehicle-logs')->name('vehicle-logs.')->group(function () {
        Route::get('/', [VehicleLogController::class, 'index'])->name('index');
        Route::get('/{vehicleLog}', [VehicleLogController::class, 'show'])->name('show');
    });
});

require __DIR__.'/auth.php';
