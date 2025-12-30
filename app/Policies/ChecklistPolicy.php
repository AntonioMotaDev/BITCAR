<?php

namespace App\Policies;

use App\Models\Checklist;
use App\Models\User;

class ChecklistPolicy
{
    /**
     * Admin y supervisor pueden ver checklists
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'supervisor']);
    }

    /**
     * Admin y supervisor pueden ver cualquier checklist
     */
    public function view(User $user, Checklist $checklist): bool
    {
        return in_array($user->role, ['admin', 'supervisor']);
    }

    /**
     * Solo admin puede crear checklists
     */
    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Solo admin puede actualizar checklists
     */
    public function update(User $user, Checklist $checklist): bool
    {
        return $user->isAdmin();
    }

    /**
     * Solo admin puede eliminar checklists
     */
    public function delete(User $user, Checklist $checklist): bool
    {
        return $user->isAdmin();
    }
}
