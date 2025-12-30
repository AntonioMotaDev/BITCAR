<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Solo admin puede ver usuarios
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Admin puede ver cualquier usuario
     * Usuario puede verse a sí mismo
     */
    public function view(User $user, User $model): bool
    {
        return $user->isAdmin() || $user->id === $model->id;
    }

    /**
     * Solo admin puede crear usuarios
     */
    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Admin puede actualizar cualquier usuario
     * Usuario puede actualizarse a sí mismo (excepto rol)
     */
    public function update(User $user, User $model): bool
    {
        return $user->isAdmin() || $user->id === $model->id;
    }

    /**
     * Solo admin puede eliminar usuarios
     */
    public function delete(User $user, User $model): bool
    {
        return $user->isAdmin() && $user->id !== $model->id;
    }
}
