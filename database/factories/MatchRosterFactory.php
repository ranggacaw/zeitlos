<?php

namespace Database\Factories;

use App\Models\FootballMatch;
use App\Models\MatchRoster;
use App\Models\Player;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<MatchRoster>
 */
class MatchRosterFactory extends Factory
{
    public function definition(): array
    {
        return [
            'match_id' => FootballMatch::factory(),
            'player_id' => Player::factory(),
            'guest_name' => null,
            'role' => MatchRoster::ROLE_PLAYER,
        ];
    }

    public function guest(): static
    {
        return $this->state(fn (array $attributes) => [
            'player_id' => null,
            'guest_name' => fake()->name(),
        ]);
    }
}
