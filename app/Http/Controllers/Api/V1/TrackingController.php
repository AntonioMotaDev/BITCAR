<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Trip;
use Illuminate\Http\JsonResponse;

class TrackingController extends Controller
{
    /**
     * Obtiene todos los viajes activos con su última ubicación registrada
     */
    public function activeTripsWithLocations(): JsonResponse
    {
        // Usar solo end_time como indicador de viaje activo
        $activeTrips = Trip::whereNull('end_time')
            ->with([
                'user' => function ($query) {
                    $query->select('id', 'name');
                },
                'vehicle' => function ($query) {
                    $query->select('id', 'brand', 'model', 'color', 'license_plate');
                },
                'tripLocations' => function ($query) {
                    $query->latest('recorded_at')->limit(1);
                }
            ])
            ->select('id', 'user_id', 'vehicle_id', 'start_time', 'is_active', 'end_time')
            ->get()
            ->map(function ($trip) {
                $lastLocation = $trip->tripLocations->first();
                
                $tripData = [
                    'id' => $trip->id,
                    'user_id' => $trip->user_id,
                    'vehicle_id' => $trip->vehicle_id,
                    'user' => $trip->user,
                    'vehicle' => $trip->vehicle,
                    'start_time' => $trip->start_time->format('H:i'),
                    'is_active' => $trip->is_active,
                    'end_time' => $trip->end_time,
                    'locations_count' => $trip->tripLocations()->count(),
                    'last_location' => $lastLocation ? [
                        'latitude' => (float) $lastLocation->latitude,
                        'longitude' => (float) $lastLocation->longitude,
                        'accuracy' => (float) $lastLocation->accuracy,
                        'speed' => (float) $lastLocation->speed,
                        'recorded_at' => $lastLocation->recorded_at->format('Y-m-d H:i:s'),
                        'recorded_at_human' => $lastLocation->recorded_at->format('d/m/Y H:i'),
                    ] : null,
                ];
                
                return $tripData;
            });

        return response()->json([
            'success' => true,
            'data' => $activeTrips,
            'count' => $activeTrips->count(),
        ]);
    }
}
