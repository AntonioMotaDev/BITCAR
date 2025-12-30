<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\VehicleLog;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VehicleLogController extends Controller
{
    public function index(Request $request): View
    {
        $query = VehicleLog::with(['vehicle', 'user', 'checklist']);

        // Filtros
        if ($request->has('vehicle_id')) {
            $query->where('vehicle_id', $request->vehicle_id);
        }

        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        $logs = $query->latest()->paginate(20);

        return view('vehicle-logs.index', compact('logs'));
    }

    public function show(VehicleLog $vehicleLog): View
    {
        $vehicleLog->load([
            'vehicle',
            'user',
            'checklist.items',
            'items.checklistItem',
            'photos',
            'signature',
            'incidents'
        ]);

        return view('vehicle-logs.show', compact('vehicleLog'));
    }
}
