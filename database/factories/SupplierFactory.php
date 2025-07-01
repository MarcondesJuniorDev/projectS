<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Supplier>
 */
class SupplierFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'company_name' => $this->faker->unique()->company,
            'corporate_name' => $this->faker->optional()->companySuffix,
            'cnpj' => $this->faker->optional()->regexify('[0-9]{2}\.[0-9]{3}\.[0-9]{3}/[0-9]{4}-[0-9]{2}'),
            'state_registration' => $this->faker->optional()->regexify('[0-9]{9}'),
            'address' => $this->faker->optional()->streetAddress,
            'city' => $this->faker->optional()->city,
            'state' => $this->faker->optional()->stateAbbr,
            'zip_code' => $this->faker->optional()->postcode,
            'phone' => $this->faker->optional()->phoneNumber,
            'email' => $this->faker->boolean ? $this->faker->unique()->safeEmail : null,
            'contact_person_name' => $this->faker->optional()->name,
            'contact_person_role' => $this->faker->optional()->jobTitle,
            'contact_person_phone' => $this->faker->optional()->phoneNumber,
            'products_services_offered' => $this->faker->optional()->sentence,
            'payment_terms' => $this->faker->optional()->paragraph,
            'notes' => $this->faker->optional()->text(200),
        ];
    }
}
