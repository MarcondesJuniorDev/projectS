<?php

namespace Database\Seeders;

use App\Models\ServiceTemplate;
use Illuminate\Database\Seeder;

class ServiceTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ServiceTemplate::factory()->count(10)->create();
    }
}
