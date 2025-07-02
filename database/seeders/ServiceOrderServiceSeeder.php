<?php

namespace Database\Seeders;

use App\Models\ServiceOrderService;
use Illuminate\Database\Seeder;

class ServiceOrderServiceSeeder extends Seeder
{
    public function run(): void
    {
        ServiceOrderService::factory()->count(10)->create();
    }
}
