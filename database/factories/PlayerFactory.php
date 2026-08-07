<?php

namespace Database\Factories;

use App\Models\Player;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Player>
 */
class PlayerFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'jersey_number' => fake()->unique()->numberBetween(1, 99),
            'position' => fake()->randomElement(['Goalkeeper', 'Defender', 'Midfielder', 'Forward']),
            'is_active' => true,
            'photo_path' => 'players/'.fake()->uuid().'.jpg',
            'joined_at' => fake()->dateTimeBetween('-5 years', 'now'),
            'goals_adjustment' => 0,
            'assists_adjustment' => 0,
            'appearances_adjustment' => 0,
        ];
    }
}
