<?php

namespace Database\Factories;

use App\Models\ServiceOrderMaterial;
use Illuminate\Database\Eloquent\Factories\Factory;

class ServiceOrderMaterialFactory extends Factory
{
    protected $model = ServiceOrderMaterial::class;

    public function definition(): array
    {
        return [
            'service_order_id' => \App\Models\ServiceOrder::factory(),
            'material_id' => \App\Models\Material::factory(),
            'quantity_used' => $this->faker->numberBetween(1, 100),
            'cost_price_at_use' => $this->faker->randomFloat(2, 1, 1000),
        ];
    }
}
