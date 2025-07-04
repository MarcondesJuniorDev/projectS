<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            PermissionSeeder::class,
            RoleSeeder::class,
            UserSeeder::class,
            ClientSeeder::class,
            ServiceLocationSeeder::class,
            TechnicianSeeder::class,
            SupplierSeeder::class,
            MaterialSeeder::class,
            ServiceTemplateSeeder::class,
            ServiceOrderSeeder::class,
        ]);
    }
}
