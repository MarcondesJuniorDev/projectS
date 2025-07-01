<?php

namespace Database\Factories;

use App\Models\Technician;
use Illuminate\Database\Eloquent\Factories\Factory;

class TechnicianFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = Technician::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'full_name' => $this->faker->name,
            'cpf' => $this->faker->unique()->regexify('[0-9]{3}\.[0-9]{3}\.[0-9]{3}-[0-9]{2}'),
            'rg' => $this->faker->optional()->regexify('[0-9]{7}-[0-9]'),
            'phone' => $this->faker->optional()->phoneNumber,
            'email' => $this->faker->optional()->unique()->safeEmail ?? $this->faker->unique()->safeEmail,
            'specialities' => $this->faker->optional()->randomElements([
                'Electrical', 'Plumbing', 'HVAC', 'Carpentry', 'General Maintenance'
            ], $this->faker->numberBetween(1, 3)),
            'status' => $this->faker->randomElement(['active', 'inactive', 'on_leave']),
            'notes' => $this->faker->optional()->text(200),
        ];
    }
}
