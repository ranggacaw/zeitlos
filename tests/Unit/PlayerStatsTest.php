<?php

namespace Tests\Unit;

use App\Models\FootballMatch;
use App\Models\MatchEvent;
use App\Models\Player;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PlayerStatsTest extends TestCase
{
    use RefreshDatabase;

    public function test_player_goal_and_assist_totals_are_derived_from_goal_events(): void
    {
        $match = FootballMatch::factory()->finished()->create();
        $scorer = Player::factory()->create();
        $assistant = Player::factory()->create();

        MatchEvent::factory()->create([
            'match_id' => $match->id,
            'scorer_id' => $scorer->id,
            'assist_player_id' => $assistant->id,
        ]);
        MatchEvent::factory()->create([
            'match_id' => $match->id,
            'scorer_id' => $scorer->id,
            'assist_player_id' => $assistant->id,
        ]);

        $this->assertSame(2, $scorer->goalsCount());
        $this->assertSame(2, $assistant->assistsCount());
    }

    public function test_player_stat_adjustments_are_added_to_derived_totals(): void
    {
        $match = FootballMatch::factory()->finished()->create();
        $player = Player::factory()->create([
            'goals_adjustment' => 1,
            'assists_adjustment' => 2,
        ]);
        $teammate = Player::factory()->create();

        MatchEvent::factory()->create([
            'match_id' => $match->id,
            'scorer_id' => $player->id,
            'assist_player_id' => $player->id,
        ]);
        MatchEvent::factory()->create([
            'match_id' => $match->id,
            'scorer_id' => $teammate->id,
            'assist_player_id' => $player->id,
        ]);

        $this->assertSame(2, $player->goalsCount());
        $this->assertSame(4, $player->assistsCount());
    }
}
