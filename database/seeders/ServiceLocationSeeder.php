<?php

namespace Database\Seeders;

use App\Models\ServiceLocation;
use Illuminate\Database\Seeder;

class ServiceLocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ServiceLocation::factory()->count(20)->create();
    }
}
