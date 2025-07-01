<?php

namespace Database\Factories;

use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClientFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = Client::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->company,
            'corporate_name' => $this->faker->companySuffix,
            'cnpj' => $this->faker->regexify('[0-9]{2}\.[0-9]{3}\.[0-9]{3}/[0-9]{4}-[0-9]{2}'),
            'state_registration' => $this->faker->regexify('[0-9]{9}'),
            'address' => $this->faker->streetAddress,
            'city' => $this->faker->city,
            'state' => $this->faker->stateAbbr,
            'zip_code' => $this->faker->postcode,
            'phone' => $this->faker->phoneNumber,
            'email' => $this->faker->unique()->safeEmail,
            'contact_person_name' => $this->faker->name,
            'contact_person_phone' => $this->faker->phoneNumber,
            'contact_person_email' => $this->faker->unique()->safeEmail,
            'notes' => $this->faker->sentence,
        ];
    }
}
