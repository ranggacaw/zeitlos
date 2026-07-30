<?php

namespace Database\Factories;

use App\Models\FootballMatch;
use App\Models\MatchEvent;
use App\Models\Player;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<MatchEvent>
 */
class MatchEventFactory extends Factory
{
    public function definition(): array
    {
        return [
            'match_id' => FootballMatch::factory()->finished(),
            'scorer_id' => Player::factory(),
            'assist_player_id' => null,
            'event_type' => MatchEvent::TYPE_GOAL,
            'team' => MatchEvent::TEAM_ZEITLOS,
            'minute' => fake()->numberBetween(1, 90),
        ];
    }
}
