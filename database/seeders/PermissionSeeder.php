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
            'client_create',
            'client_read',
            'client_update',
            'client_delete',
            'location_create',
            'location_read',
            'location_update',
            'location_delete',
            'technician_create',
            'technician_read',
            'technician_update',
            'technician_delete',
            'supplier_create',
            'supplier_read',
            'supplier_update',
            'supplier_delete',
            'material_create',
            'material_read',
            'material_update',
            'material_delete',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }
    }
}
