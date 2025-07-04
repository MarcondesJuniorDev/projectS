<?php

namespace App\Policies;

use App\Models\Technician;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TechnicianPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('technician_read');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Technician $technician): bool
    {
        return $user->hasPermissionTo('technician_read');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('technician_create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Technician $technician): bool
    {
        return $user->hasPermissionTo('technician_update');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Technician $technician): bool
    {
        return $user->hasPermissionTo('technician_delete');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Technician $technician): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Technician $technician): bool
    {
        return false;
    }
}
