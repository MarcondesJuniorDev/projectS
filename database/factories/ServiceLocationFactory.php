<?php

namespace Database\Factories;

use App\Models\ServiceLocation;
use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;

class ServiceLocationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = ServiceLocation::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->company,
            'address' => $this->faker->streetAddress,
            'city' => $this->faker->city,
            'state' => $this->faker->stateAbbr,
            'zip_code' => $this->faker->postcode,
            'phone' => $this->faker->phoneNumber,
            'email' => $this->faker->unique()->safeEmail,
            'contact_person_name' => $this->faker->name,
            'contact_person_phone' => $this->faker->phoneNumber,
            'contact_person_email' => $this->faker->unique()->safeEmail,
            'reference_point' => $this->faker->sentence,
            'notes' => $this->faker->paragraph,
            'client_id' => Client::factory(),
        ];
    }
}
