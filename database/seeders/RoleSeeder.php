<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            'User',
            'Manager',
            'Admin',
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }

        // Relacionar permissões às roles
        $userPermissions = [
            'access_admin',
        ];
        $managerPermissions = array_merge($userPermissions, [
            'user_create', 'user_read', 'user_update', 'user_delete',
        ]);
        $adminPermissions = Permission::pluck('name')->toArray();

        Role::where('name', 'User')->first()?->permissions()->sync(
            Permission::whereIn('name', $userPermissions)->pluck('id')
        );
        Role::where('name', 'Manager')->first()?->permissions()->sync(
            Permission::whereIn('name', $managerPermissions)->pluck('id')
        );
        Role::where('name', 'Admin')->first()?->permissions()->sync(
            Permission::whereIn('name', $adminPermissions)->pluck('id')
        );
    }
}
