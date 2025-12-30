<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Vehicle;

class VehiclePolicy
{
    /**
     * Admin y supervisor pueden ver todos
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'supervisor']);
    }

    /**
     * Admin y supervisor pueden ver cualquier vehículo
     * Operador solo puede ver su vehículo asignado
     */
    public function view(User $user, Vehicle $vehicle): bool
    {
        if (in_array($user->role, ['admin', 'supervisor'])) {
            return true;
        }

        // Operador solo ve su vehículo asignado
        return $user->activeAssignment?->vehicle_id === $vehicle->id;
    }

    /**
     * Solo admin puede crear vehículos
     */
    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Solo admin puede actualizar vehículos
     */
    public function update(User $user, Vehicle $vehicle): bool
    {
        return $user->isAdmin();
    }

    /**
     * Solo admin puede eliminar vehículos
     */
    public function delete(User $user, Vehicle $vehicle): bool
    {
        return $user->isAdmin();
    }
}
