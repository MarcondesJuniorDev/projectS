<?php

namespace App\Policies;

use App\Models\ServiceTemplate;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ServiceTemplatePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('service_template_read');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ServiceTemplate $serviceTemplate): bool
    {
        return $user->hasPermissionTo('service_template_read');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('service_template_create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ServiceTemplate $serviceTemplate): bool
    {
        return $user->hasPermissionTo('service_template_update');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ServiceTemplate $serviceTemplate): bool
    {
        return $user->hasPermissionTo('service_template_delete');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ServiceTemplate $serviceTemplate): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ServiceTemplate $serviceTemplate): bool
    {
        return false;
    }
}
