<?php

namespace Database\Factories;

use App\Models\FootballMatch;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<FootballMatch>
 */
class FootballMatchFactory extends Factory
{
    protected $model = FootballMatch::class;

    public function definition(): array
    {
        return [
            'opponent' => fake()->company().' FC',
            'match_date' => fake()->dateTimeBetween('now', '+2 months'),
            'match_time' => '20:00',
            'venue' => fake()->streetAddress(),
            'maps_url' => 'https://maps.example.com/'.fake()->slug(),
            'ticket_price' => fake()->randomFloat(2, 0, 25),
            'dress_code' => 'Home kit',
            'facilities' => 'Changing rooms and showers',
            'notes' => fake()->sentence(),
            'payment_label' => 'Pitch fee',
            'payment_amount' => fake()->randomFloat(2, 5, 20),
            'payment_due_at' => now()->addWeek(),
            'payment_instructions' => 'Pay before kickoff.',
            'whatsapp_announcement' => fake()->paragraph(),
            'status' => FootballMatch::STATUS_SCHEDULED,
            'zeitlos_score' => null,
            'opponent_score' => null,
        ];
    }

    public function finished(): static
    {
        return $this->state(fn (array $attributes) => [
            'match_date' => now()->subWeek(),
            'status' => FootballMatch::STATUS_FINISHED,
            'zeitlos_score' => 3,
            'opponent_score' => 1,
        ]);
    }

    public function starting(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => FootballMatch::STATUS_STARTING,
        ]);
    }

    public function live(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => FootballMatch::STATUS_LIVE,
        ]);
    }
}
