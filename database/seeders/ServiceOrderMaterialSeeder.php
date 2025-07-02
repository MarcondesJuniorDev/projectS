<?php

namespace Database\Seeders;

use App\Models\ServiceOrderMaterial;
use Illuminate\Database\Seeder;

class ServiceOrderMaterialSeeder extends Seeder
{
    public function run(): void
    {
        ServiceOrderMaterial::factory()->count(10)->create();
    }
}
