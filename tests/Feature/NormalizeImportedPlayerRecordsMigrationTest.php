<?php

namespace Tests\Feature;

use App\Models\FootballMatch;
use App\Models\MatchEvent;
use App\Models\MatchRoster;
use App\Models\Player;
use App\Team\GuestPlayerRestorer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NormalizeImportedPlayerRecordsMigrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_restores_selected_player_references_to_guest_names(): void
    {
        $match = FootballMatch::factory()->create();
        $guest = Player::factory()->create(['name' => '  Guest Player  ']);
        $zeitlosPlayer = Player::factory()->create(['name' => 'Zeitlos Player']);
        $roster = MatchRoster::factory()->create([
            'match_id' => $match->id,
            'player_id' => $guest->id,
            'role' => MatchRoster::ROLE_GOALKEEPER,
        ]);
        $scoredEvent = MatchEvent::factory()->create([
            'match_id' => $match->id,
            'scorer_id' => $guest->id,
        ]);
        $assistedEvent = MatchEvent::factory()->create([
            'match_id' => $match->id,
            'scorer_id' => $zeitlosPlayer->id,
            'assist_player_id' => $guest->id,
        ]);

        app(GuestPlayerRestorer::class)->restore([$guest]);

        $roster->refresh();
        $scoredEvent->refresh();
        $assistedEvent->refresh();

        $this->assertNull($roster->player_id);
        $this->assertSame('Guest Player', $roster->guest_name);
        $this->assertNull($scoredEvent->scorer_id);
        $this->assertSame('Guest Player', $scoredEvent->guest_scorer_name);
        $this->assertNull($assistedEvent->assist_player_id);
        $this->assertSame('Guest Player', $assistedEvent->guest_assist_name);
        $this->assertDatabaseMissing('players', ['id' => $guest->id]);
        $this->assertDatabaseHas('players', ['id' => $zeitlosPlayer->id]);
        $this->assertSame($zeitlosPlayer->id, $assistedEvent->scorer_id);
    }
}
