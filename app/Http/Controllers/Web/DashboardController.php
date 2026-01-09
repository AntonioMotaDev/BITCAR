<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Trip;
use App\Models\Vehicle;
use App\Models\VehicleLog;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'total_vehicles' => Vehicle::count(),
            'active_vehicles' => Vehicle::where('status', 'activo')->count(),
            'total_operators' => User::where('role', 'operador')->count(),
            'active_trips' => Trip::whereNull('end_time')->count(),
            'today_logs' => VehicleLog::whereDate('created_at', today())->count(),
        ];

        $recentLogs = VehicleLog::with(['vehicle', 'user'])
            ->latest()
            ->limit(10)
            ->get();

        $activeTrips = Trip::with(['vehicle', 'user'])
            ->whereNull('end_time')
            ->latest()
            ->limit(10)
            ->get();

        return view('dashboard', compact('stats', 'recentLogs', 'activeTrips'));
    }
}
