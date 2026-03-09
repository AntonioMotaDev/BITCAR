<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\VehicleResource;
use App\Models\Vehicle;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    /**
     * Obtener vehículos asignados al usuario autenticado
     */
    public function assigned(Request $request): JsonResponse
    {
        $user = $request->user();

        $vehicles = Vehicle::whereHas('vehicleAssignments', function ($query) use ($user) {
            $query->where('user_id', $user->id)
                ->where('is_active', true);
        })->get();

        return response()->json([
            'data' => VehicleResource::collection($vehicles),
        ]);
    }
}
