<?php

namespace App\Providers;

use App\Models\Role;
use App\Models\User;
use App\Models\Permission;
use App\Policies\RolePolicy;
use App\Policies\UserPolicy;
use App\Policies\PermissionPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * As políticas de autorização do aplicativo.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
        User::class => UserPolicy::class,
        Permission::class => PermissionPolicy::class,
        Role::class => RolePolicy::class,
    ];

    /**
     * Registre quaisquer serviços de autenticação/autorização.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Outras definições de Gate ou lógica de autorização
    }
}
