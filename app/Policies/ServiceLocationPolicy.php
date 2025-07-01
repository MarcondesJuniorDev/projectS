<?php

namespace App\Policies;

use App\Models\ServiceLocation;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ServiceLocationPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('location_read');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ServiceLocation $serviceLocation): bool
    {
        return $user->hasPermissionTo('location_read');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('location_create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ServiceLocation $serviceLocation): bool
    {
        return $user->hasPermissionTo('location_update');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ServiceLocation $serviceLocation): bool
    {
        return $user->hasPermissionTo('location_delete');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ServiceLocation $serviceLocation): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ServiceLocation $serviceLocation): bool
    {
        return false;
    }
}
