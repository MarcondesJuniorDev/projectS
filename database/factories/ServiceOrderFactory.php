<?php

namespace Database\Factories;

use App\Models\ServiceOrder;
use Illuminate\Database\Eloquent\Factories\Factory;

class ServiceOrderFactory extends Factory
{
    protected $model = ServiceOrder::class;

    public function definition(): array
    {
        return [
            'os_number' => $this->faker->unique()->regexify('[A-Z0-9]{10}'),
            'client_id' => \App\Models\Client::factory(),
            'service_location_id' => \App\Models\ServiceLocation::factory(),
            'requester_name' => $this->faker->name,
            'requester_phone' => $this->faker->phoneNumber,
            'requester_email' => $this->faker->email,
            'problem_description' => $this->faker->optional()->paragraph,
            'priority' => $this->faker->randomElement(['baixa', 'media', 'alta', 'critica']),
            'status' => $this->faker->randomElement([
                'aberto',
                'em_analise',
                'aguardando_materiais',
                'em_andamento',
                'aguardando_aprovacao',
                'concluido',
                'cancelado',
                'reaberto'
            ]),
            'opened_at' => $this->faker->dateTime,
            'scheduled_at' => $this->faker->optional()->dateTime,
            'started_at' => $this->faker->optional()->dateTime,
            'completed_at' => $this->faker->optional()->dateTime,
            'assigned_technician_id' => \App\Models\Technician::factory(),
            'service_performed_description' => $this->faker->optional()->paragraph,
            'time_spent_hours' => $this->faker->optional()->randomFloat(2, 1, 8),
            'customer_signature_path' => $this->faker->optional()->filePath,
            'customer_feedback' => $this->faker->optional()->sentence,
            'customer_rating' => $this->faker->optional()->numberBetween(1, 5),
            'internal_notes' => $this->faker->optional()->paragraph,
        ];
    }
}
