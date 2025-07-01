<?php

namespace Database\Factories;

use App\Models\Material;
use Illuminate\Database\Eloquent\Factories\Factory;

class MaterialFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = Material::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->word,
            'sku' => $this->faker->optional()->regexify('[A-Z0-9]{8}') ? $this->faker->unique()->regexify('[A-Z0-9]{8}') : null,
            'unit_of_measurement' => $this->faker->randomElement(['unit', 'box', 'roll', 'kg', 'liter', 'meter', 'piece', 'other']),
            'cost_price' => $this->faker->randomFloat(2, 1, 1000),
            'min_stock_level' => $this->faker->numberBetween(0, 10),
            'max_stock_level' => $this->faker->optional()->numberBetween(20, 100),
            'current_stock' => $this->faker->numberBetween(0, 50),
            'description' => $this->faker->optional()->sentence,
            'location_in_warehouse' => $this->faker->optional()->word,
        ];
    }
}
