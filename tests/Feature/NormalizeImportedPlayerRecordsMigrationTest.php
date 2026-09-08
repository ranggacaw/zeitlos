<?php

namespace Tests\Feature;

use App\Models\FootballMatch;
use App\Models\MatchEvent;
use App\Models\MatchRoster;
use App\Models\Player;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NormalizeImportedPlayerRecordsMigrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_normalizes_historical_guest_records_idempotently(): void
    {
        $match = FootballMatch::factory()->create();
        $existing = Player::factory()->create([
            'name' => '  Existing Player  ',
            'position' => 'Defender',
        ]);
        $placeholder = Player::factory()->create();

        $existingRoster = MatchRoster::factory()->guest()->create([
            'match_id' => $match->id,
            'guest_name' => ' existing player ',
            'role' => MatchRoster::ROLE_GOALKEEPER,
        ]);
        $goalkeeperRoster = MatchRoster::factory()->guest()->create([
            'match_id' => $match->id,
            'guest_name' => ' New Keeper ',
            'role' => MatchRoster::ROLE_GOALKEEPER,
        ]);
        $playerRoster = MatchRoster::factory()->guest()->create([
            'match_id' => $match->id,
            'guest_name' => 'New Player',
            'role' => MatchRoster::ROLE_PLAYER,
        ]);
        $event = MatchEvent::factory()->create([
            'match_id' => $match->id,
            'scorer_id' => $placeholder->id,
            'guest_scorer_name' => ' Event Scorer ',
            'guest_assist_name' => 'Event Assist',
        ]);
        $opponentEvent = MatchEvent::factory()->create([
            'match_id' => $match->id,
            'scorer_id' => $placeholder->id,
            'team' => MatchEvent::TEAM_OPPONENT,
            'guest_scorer_name' => 'Opponent Scorer',
        ]);

        $migration = require database_path('migrations/2026_09_08_000001_normalize_imported_player_records.php');
        $migration->up();
        $migration->up();

        $existingRoster->refresh();
        $goalkeeperRoster->refresh();
        $playerRoster->refresh();
        $event->refresh();
        $opponentEvent->refresh();

        $this->assertSame($existing->id, $existingRoster->player_id);
        $this->assertNull($existingRoster->guest_name);
        $this->assertSame('Goalkeeper', $goalkeeperRoster->player->position);
        $this->assertSame('Player', $playerRoster->player->position);
        $this->assertNull($goalkeeperRoster->guest_name);
        $this->assertNull($playerRoster->guest_name);
        $this->assertSame('Event Scorer', $event->scorer->name);
        $this->assertSame('Event Assist', $event->assistPlayer->name);
        $this->assertNull($event->guest_scorer_name);
        $this->assertNull($event->guest_assist_name);
        $this->assertSame(MatchEvent::TEAM_OPPONENT, $opponentEvent->team);
        $this->assertSame('Opponent Scorer', $opponentEvent->scorer->name);
        $this->assertNull($opponentEvent->guest_scorer_name);
        $this->assertSame(1, $goalkeeperRoster->player->appearancesCount());
        $this->assertSame(1, $event->scorer->goalsCount());
        $this->assertSame(1, $event->assistPlayer->assistsCount());
        $this->assertSame(7, Player::count());
        $this->assertSame(3, MatchRoster::count());
        $this->assertSame(2, MatchEvent::count());
    }
}
