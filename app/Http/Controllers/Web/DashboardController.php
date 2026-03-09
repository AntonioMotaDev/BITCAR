<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Trip;
use App\Models\Vehicle;
use App\Models\VehicleLog;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $stats = [
            'total_vehicles' => Vehicle::count(),
            'active_vehicles' => Vehicle::where('status', 'activo')->count(),
            'total_operators' => User::where('role', 'operador')->count(),
            'active_trips' => Trip::whereNull('end_time')->count(),
            'today_logs' => VehicleLog::whereDate('created_at', today())->count(),
        ];

        $recentLogsQuery = VehicleLog::with([
            'vehicle',
            'user',
            'trip',
            'vehicleLogItems.checklistItem',
            'incidents',
            'vehicleLogPhotos',
            'signatures',
        ])
            ->latest();

        if ($request->filled('user_id')) {
            $recentLogsQuery->where('user_id', $request->input('user_id'));
        }

        if ($request->filled('vehicle_id')) {
            $recentLogsQuery->where('vehicle_id', $request->input('vehicle_id'));
        }

        if ($request->filled('date')) {
            $recentLogsQuery->whereDate('created_at', $request->input('date'));
        }

        if ($request->filled('log_type')) {
            $recentLogsQuery->where('type', $request->input('log_type'));
        }

        $recentLogs = $recentLogsQuery->limit(50)->get();

        $activeTrips = Trip::with(['vehicle', 'user'])
            ->whereNull('end_time')
            ->latest()
            ->limit(10)
            ->get();

        $pendingTripLogs = VehicleLog::with(['vehicle', 'user'])
            ->where('type', 'trip_start')
            ->whereNull('trip_id')
            ->latest()
            ->limit(10)
            ->get();

        $users = User::select('id', 'name', 'role')
            ->orderBy('name')
            ->get();

        $vehicles = Vehicle::all();

        return view('dashboard', compact('stats', 'recentLogs', 'activeTrips', 'pendingTripLogs', 'users', 'vehicles'));
    }

    public function approveTrip(Request $request, VehicleLog $vehicleLog): RedirectResponse
    {
        $user = $request->user();
        abort_unless($user && ($user->isAdmin() || $user->isSupervisor()), 403);

        if ($vehicleLog->type !== 'trip_start' || $vehicleLog->trip_id !== null) {
            return redirect()
                ->route('dashboard')
                ->with('status', 'La solicitud ya fue procesada.');
        }

        $activeTrip = Trip::where('vehicle_id', $vehicleLog->vehicle_id)
            ->where('user_id', $vehicleLog->user_id)
            ->whereNull('end_time')
            ->where('is_active', true)
            ->first();

        if ($activeTrip) {
            return redirect()
                ->route('dashboard')
                ->with('status', 'Ya existe un viaje activo para este operador.');
        }

        $trip = Trip::create([
            'vehicle_id' => $vehicleLog->vehicle_id,
            'user_id' => $vehicleLog->user_id,
            'start_time' => now(),
            'start_mileage' => $vehicleLog->mileage,
            'start_fuel_level' => $vehicleLog->fuel_level,
            'is_active' => true,
        ]);

        $vehicleLog->update(['trip_id' => $trip->id]);

        return redirect()
            ->route('dashboard')
            ->with('status', 'Viaje aceptado correctamente.');
    }

    public function rejectTrip(Request $request, VehicleLog $vehicleLog): RedirectResponse
    {
        $user = $request->user();
        abort_unless($user && ($user->isAdmin() || $user->isSupervisor()), 403);

        if ($vehicleLog->type !== 'trip_start' || $vehicleLog->trip_id !== null) {
            return redirect()
                ->route('dashboard')
                ->with('status', 'La solicitud ya fue procesada.');
        }

        $now = now();

        $trip = Trip::create([
            'vehicle_id' => $vehicleLog->vehicle_id,
            'user_id' => $vehicleLog->user_id,
            'start_time' => $now,
            'end_time' => $now,
            'start_mileage' => $vehicleLog->mileage,
            'end_mileage' => $vehicleLog->mileage,
            'start_fuel_level' => $vehicleLog->fuel_level,
            'end_fuel_level' => $vehicleLog->fuel_level,
            'distance_km' => 0,
            'estimated_fuel_consumption' => 0,
            'is_active' => false,
        ]);

        $vehicleLog->update(['trip_id' => $trip->id]);

        return redirect()
            ->route('dashboard')
            ->with('status', 'Viaje rechazado y finalizado.');
    }
}
