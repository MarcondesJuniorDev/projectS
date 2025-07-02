<?php

namespace Database\Factories;

use App\Models\ServiceOrderService;
use Illuminate\Database\Eloquent\Factories\Factory;

class ServiceOrderServiceFactory extends Factory
{
    protected $model = ServiceOrderService::class;

    public function definition(): array
    {
        return [
            'service_order_id' => \App\Models\ServiceOrder::factory(),
            'service_template_id' => \App\Models\ServiceTemplate::factory(),
            'description' => $this->faker->sentence,
            'price' => $this->faker->randomFloat(2, 50, 1000),
            'time_spent_hours' => $this->faker->optional()->randomFloat(2, 1, 8),
        ];
    }
}
