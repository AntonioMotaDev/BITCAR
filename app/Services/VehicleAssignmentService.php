<?php

namespace App\Services;

use App\Models\User;
use App\Models\Vehicle;
use App\Models\VehicleAssignment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class VehicleAssignmentService
{
    /**
     * Asignar un vehículo a un usuario
     */
    public function assignVehicle(Vehicle $vehicle, User $user, Carbon $startDate): VehicleAssignment
    {
        return DB::transaction(function () use ($vehicle, $user, $startDate) {
            // Desactivar asignaciones previas del usuario
            VehicleAssignment::where('user_id', $user->id)
                ->where('is_active', true)
                ->update([
                    'is_active' => false,
                    'end_date' => now(),
                ]);

            // Desactivar asignaciones previas del vehículo
            VehicleAssignment::where('vehicle_id', $vehicle->id)
                ->where('is_active', true)
                ->update([
                    'is_active' => false,
                    'end_date' => now(),
                ]);

            // Crear nueva asignación
            return VehicleAssignment::create([
                'vehicle_id' => $vehicle->id,
                'user_id' => $user->id,
                'start_date' => $startDate,
                'is_active' => true,
            ]);
        });
    }

    /**
     * Finalizar asignación activa de un usuario
     */
    public function unassignVehicle(User $user, Carbon $endDate = null): void
    {
        VehicleAssignment::where('user_id', $user->id)
            ->where('is_active', true)
            ->update([
                'is_active' => false,
                'end_date' => $endDate ?? now(),
            ]);
    }

    /**
     * Obtener vehículo asignado actualmente a un usuario
     */
    public function getActiveVehicle(User $user): ?Vehicle
    {
        $assignment = VehicleAssignment::where('user_id', $user->id)
            ->where('is_active', true)
            ->with('vehicle')
            ->first();

        return $assignment?->vehicle;
    }

    /**
     * Verificar si un vehículo está asignado
     */
    public function isVehicleAssigned(Vehicle $vehicle): bool
    {
        return VehicleAssignment::where('vehicle_id', $vehicle->id)
            ->where('is_active', true)
            ->exists();
    }
}
