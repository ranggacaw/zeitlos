<?php

namespace Tests\Feature;

use App\Models\FootballMatch;
use App\Models\MatchEvent;
use App\Models\MatchRoster;
use App\Models\Player;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ZeitlosDomainTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_admin_role_is_identified(): void
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();

        $this->assertTrue($admin->isAdmin());
        $this->assertFalse($user->isAdmin());
    }

    public function test_domain_seed_data_creates_admin_players_matches_rosters_and_events(): void
    {
        $this->seed(DatabaseSeeder::class);

        $this->assertTrue(User::where('email', 'admin@zeitlos.test')->firstOrFail()->isAdmin());
        $this->assertGreaterThanOrEqual(6, Player::where('is_active', true)->count());
        $this->assertDatabaseHas('matches', [
            'opponent' => 'Riverside FC',
            'status' => FootballMatch::STATUS_SCHEDULED,
        ]);
        $this->assertDatabaseHas('matches', [
            'opponent' => 'Old Town United',
            'status' => FootballMatch::STATUS_FINISHED,
            'zeitlos_score' => 3,
            'opponent_score' => 1,
        ]);
        $this->assertGreaterThan(0, MatchRoster::count());
        $this->assertSame(3, MatchEvent::where('event_type', MatchEvent::TYPE_GOAL)->count());
    }

    public function test_model_relationships_connect_matches_rosters_players_and_events(): void
    {
        $match = FootballMatch::factory()->finished()->create();
        $scorer = Player::factory()->create();
        $assistant = Player::factory()->create();

        $roster = MatchRoster::factory()->create([
            'match_id' => $match->id,
            'player_id' => $scorer->id,
        ]);

        $event = MatchEvent::factory()->create([
            'match_id' => $match->id,
            'scorer_id' => $scorer->id,
            'assist_player_id' => $assistant->id,
        ]);

        $this->assertTrue($match->rosterEntries->contains($roster));
        $this->assertTrue($match->events->contains($event));
        $this->assertTrue($scorer->rosterEntries->contains($roster));
        $this->assertTrue($scorer->scoredEvents->contains($event));
        $this->assertTrue($assistant->assistedEvents->contains($event));
        $this->assertTrue($roster->match->is($match));
        $this->assertTrue($event->scorer->is($scorer));
    }
}
