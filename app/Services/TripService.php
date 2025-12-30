<?php

namespace App\Services;

use App\Models\Trip;
use App\Models\TripLocation;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\VehicleLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class TripService
{
    /**
     * Iniciar un viaje (al crear log de salida)
     */
    public function startTrip(VehicleLog $startLog, Vehicle $vehicle, User $user): Trip
    {
        return Trip::create([
            'vehicle_id' => $vehicle->id,
            'user_id' => $user->id,
            'start_vehicle_log_id' => $startLog->id,
            'started_at' => now(),
        ]);
    }

    /**
     * Finalizar un viaje (al crear log de entrada)
     */
    public function endTrip(Trip $trip, VehicleLog $endLog): Trip
    {
        $trip->update([
            'end_vehicle_log_id' => $endLog->id,
            'ended_at' => now(),
            'total_distance_km' => $this->calculateTotalDistance($trip),
            'estimated_fuel_consumption' => $this->estimateFuelConsumption($trip, $endLog),
        ]);

        return $trip->fresh();
    }

    /**
     * Registrar ubicaciones GPS del viaje
     */
    public function recordLocations(Trip $trip, array $locations): void
    {
        $data = collect($locations)->map(function ($location) use ($trip) {
            return [
                'trip_id' => $trip->id,
                'latitude' => $location['latitude'],
                'longitude' => $location['longitude'],
                'accuracy' => $location['accuracy'],
                'speed' => $location['speed'] ?? null,
                'recorded_at' => Carbon::parse($location['recorded_at']),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        })->toArray();

        TripLocation::insert($data);
    }

    /**
     * Calcular distancia total usando fórmula Haversine
     */
    public function calculateTotalDistance(Trip $trip): float
    {
        $locations = $trip->locations()
            ->orderBy('recorded_at')
            ->get();

        if ($locations->count() < 2) {
            return 0;
        }

        $totalDistance = 0;

        for ($i = 0; $i < $locations->count() - 1; $i++) {
            $from = $locations[$i];
            $to = $locations[$i + 1];
            
            $totalDistance += $this->haversineDistance(
                $from->latitude,
                $from->longitude,
                $to->latitude,
                $to->longitude
            );
        }

        return round($totalDistance, 2);
    }

    /**
     * Fórmula Haversine para calcular distancia entre dos puntos GPS
     * Retorna distancia en kilómetros
     */
    private function haversineDistance(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $earthRadius = 6371; // Radio de la Tierra en km

        $latDiff = deg2rad($lat2 - $lat1);
        $lonDiff = deg2rad($lon2 - $lon1);

        $a = sin($latDiff / 2) * sin($latDiff / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($lonDiff / 2) * sin($lonDiff / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }

    /**
     * Estimar consumo de combustible basado en distancia y odómetro
     */
    private function estimateFuelConsumption(Trip $trip, VehicleLog $endLog): float
    {
        $startLog = $trip->startLog;
        
        $distance = $trip->total_distance_km ?? 0;
        $fuelDiff = $startLog->fuel_level - $endLog->fuel_level;
        
        // Si hay consumo de combustible y distancia, calcular rendimiento
        if ($fuelDiff > 0 && $distance > 0) {
            return round($fuelDiff, 2);
        }

        return 0;
    }

    /**
     * Obtener viaje activo de un usuario
     */
    public function getActiveTrip(User $user): ?Trip
    {
        return Trip::where('user_id', $user->id)
            ->whereNull('ended_at')
            ->with(['vehicle', 'startLog'])
            ->first();
    }

    /**
     * Verificar si un vehículo tiene viaje activo
     */
    public function hasActiveTrip(Vehicle $vehicle): bool
    {
        return Trip::where('vehicle_id', $vehicle->id)
            ->whereNull('ended_at')
            ->exists();
    }
}
