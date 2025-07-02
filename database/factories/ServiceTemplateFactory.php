<?php

namespace Database\Factories;

use App\Models\ServiceTemplate;
use Illuminate\Database\Eloquent\Factories\Factory;

class ServiceTemplateFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = ServiceTemplate::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->words(3, true),
            'description' => $this->faker->optional()->sentence,
            'standard_price' => $this->faker->randomFloat(2, 50, 1000),
            'estimated_time_hours' => $this->faker->optional()->randomFloat(2, 1, 8),
            'requires_materials' => $this->faker->boolean,
        ];
    }
}
