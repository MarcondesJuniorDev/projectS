<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'admin',
                'email' => 'admin@admin.com',
                'password' => Hash::make('12345678'),
                'role' => 'Admin',
            ],
            [
                'name' => 'gestor',
                'email' => 'manager@manager.com',
                'password' => Hash::make('12345678'),
                'role' => 'Manager',
            ],
            [
                'name' => 'usuario',
                'email' => 'user@user.com',
                'password' => Hash::make('12345678'),
                'role' => 'User',
            ],
        ];

        foreach ($users as $userData) {
            $user = User::firstOrCreate([
                'email' => $userData['email'],
            ], [
                'name' => $userData['name'],
                'password' => $userData['password'],
            ]);
            $role = Role::where('name', $userData['role'])->first();
            if ($role) {
                $user->roles()->syncWithoutDetaching([$role->id]);
            }
        }
    }
}
