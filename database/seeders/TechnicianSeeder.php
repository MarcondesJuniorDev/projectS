<?php

namespace Database\Seeders;

use App\Models\Technician;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TechnicianSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Technician::factory()->count(20)->create();
    }
}
