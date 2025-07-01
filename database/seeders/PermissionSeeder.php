<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'access_admin',
            'user_create',
            'user_read',
            'user_update',
            'user_delete',
            'permission_create',
            'permission_read',
            'permission_update',
            'permission_delete',
            'role_create',
            'role_read',
            'role_update',
            'role_delete',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }
    }
}
