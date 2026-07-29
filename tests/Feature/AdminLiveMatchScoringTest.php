<?php

namespace Tests\Feature;

use App\Filament\Resources\FootballMatches\Pages\ManageFootballMatchLiveScoring;
use App\Models\FootballMatch;
use App\Models\MatchEvent;
use App\Models\MatchRoster;
use App\Models\Player;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class AdminLiveMatchScoringTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    public function test_guest_is_redirected_from_scoring_console(): void
    {
        $match = FootballMatch::factory()->create();

        $this->get("/admin/football-matches/{$match->getKey()}/live-scoring")
            ->assertRedirect('/admin/login');
    }

    public function test_non_admin_user_is_denied_scoring_console(): void
    {
        $user = User::factory()->create();
        $match = FootballMatch::factory()->create();

        $this->actingAs($user)
            ->get("/admin/football-matches/{$match->getKey()}/live-scoring")
            ->assertForbidden();
    }

    public function test_admin_can_open_scoring_console_with_players_and_events(): void
    {
        $admin = User::factory()->admin()->create();
        $match = FootballMatch::factory()->create();
        $scorer = Player::factory()->create(['name' => 'Leo Fischer']);
        $assistant = Player::factory()->create(['name' => 'Max Schmidt']);

        MatchRoster::create([
            'match_id' => $match->id,
            'player_id' => $scorer->id,
            'role' => MatchRoster::ROLE_PLAYER,
        ]);

        MatchEvent::create([
            'match_id' => $match->id,
            'scorer_id' => $scorer->id,
            'assist_player_id' => $assistant->id,
            'event_type' => MatchEvent::TYPE_GOAL,
            'minute' => 23,
        ]);

        Livewire::actingAs($admin)
            ->test(ManageFootballMatchLiveScoring::class, ['record' => $match->getRouteKey()])
            ->assertSuccessful()
            ->assertSee('Leo Fischer')
            ->assertSee('Max Schmidt')
            ->assertSee("23'");
    }

    public function test_scoring_console_falls_back_to_active_players_when_roster_is_empty(): void
    {
        $admin = User::factory()->admin()->create();
        $match = FootballMatch::factory()->create();
        Player::factory()->create(['name' => 'Active Striker', 'is_active' => true]);
        Player::factory()->create(['name' => 'Inactive Sub', 'is_active' => false]);

        Livewire::actingAs($admin)
            ->test(ManageFootballMatchLiveScoring::class, ['record' => $match->getRouteKey()])
            ->assertSuccessful()
            ->assertSee('Active Striker')
            ->assertDontSee('Inactive Sub');
    }

    public function test_admin_can_record_a_goal_event(): void
    {
        $admin = User::factory()->admin()->create();
        $match = FootballMatch::factory()->create();
        $scorer = Player::factory()->create();
        $assistant = Player::factory()->create();

        Livewire::actingAs($admin)
            ->test(ManageFootballMatchLiveScoring::class, ['record' => $match->getRouteKey()])
            ->callAction('recordGoal', [
                'scorer_id' => $scorer->id,
                'assist_player_id' => $assistant->id,
                'minute' => 42,
            ])
            ->assertHasNoActionErrors();

        $this->assertDatabaseHas('match_events', [
            'match_id' => $match->id,
            'scorer_id' => $scorer->id,
            'assist_player_id' => $assistant->id,
            'event_type' => MatchEvent::TYPE_GOAL,
            'minute' => 42,
        ]);
    }

    public function test_admin_can_mark_a_match_live(): void
    {
        $admin = User::factory()->admin()->create();
        $match = FootballMatch::factory()->create([
            'status' => FootballMatch::STATUS_SCHEDULED,
        ]);

        Livewire::actingAs($admin)
            ->test(ManageFootballMatchLiveScoring::class, ['record' => $match->getRouteKey()])
            ->callAction('markLive')
            ->assertHasNoActionErrors();

        $match->refresh();
        $this->assertSame(FootballMatch::STATUS_LIVE, $match->status);
        $this->assertNull($match->zeitlos_score);
        $this->assertNull($match->opponent_score);
    }

    public function test_recording_a_goal_requires_a_valid_scorer(): void
    {
        $admin = User::factory()->admin()->create();
        $match = FootballMatch::factory()->create();

        Livewire::actingAs($admin)
            ->test(ManageFootballMatchLiveScoring::class, ['record' => $match->getRouteKey()])
            ->callAction('recordGoal', [
                'scorer_id' => 999,
                'minute' => 10,
            ])
            ->assertHasActionErrors(['scorer_id']);

        $this->assertDatabaseCount('match_events', 0);
    }

    public function test_admin_can_delete_a_goal_event(): void
    {
        $admin = User::factory()->admin()->create();
        $match = FootballMatch::factory()->create();
        $scorer = Player::factory()->create();

        $event = MatchEvent::create([
            'match_id' => $match->id,
            'scorer_id' => $scorer->id,
            'event_type' => MatchEvent::TYPE_GOAL,
            'minute' => 10,
        ]);

        Livewire::actingAs($admin)
            ->test(ManageFootballMatchLiveScoring::class, ['record' => $match->getRouteKey()])
            ->callTableAction('delete', $event->getKey())
            ->assertHasNoTableActionErrors();

        $this->assertDatabaseMissing('match_events', ['id' => $event->id]);
    }

    public function test_admin_can_finalize_match_score(): void
    {
        $admin = User::factory()->admin()->create();
        $match = FootballMatch::factory()->create();

        Livewire::actingAs($admin)
            ->test(ManageFootballMatchLiveScoring::class, ['record' => $match->getRouteKey()])
            ->callAction('finalizeMatch', [
                'zeitlos_score' => 2,
                'opponent_score' => 1,
            ])
            ->assertHasNoActionErrors();

        $match->refresh();
        $this->assertSame(FootballMatch::STATUS_FINISHED, $match->status);
        $this->assertSame(2, $match->zeitlos_score);
        $this->assertSame(1, $match->opponent_score);
    }

    public function test_finalizing_requires_both_scores(): void
    {
        $admin = User::factory()->admin()->create();
        $match = FootballMatch::factory()->create();

        Livewire::actingAs($admin)
            ->test(ManageFootballMatchLiveScoring::class, ['record' => $match->getRouteKey()])
            ->callAction('finalizeMatch', [
                'zeitlos_score' => 2,
            ])
            ->assertHasActionErrors(['opponent_score']);

        $this->assertSame(FootballMatch::STATUS_SCHEDULED, $match->fresh()->status);
    }

    public function test_public_leaderboard_reflects_recorded_goal_and_assist(): void
    {
        $match = FootballMatch::factory()->finished()->create();
        $scorer = Player::factory()->create(['name' => 'Scorer Star', 'is_active' => true]);
        $assistant = Player::factory()->create(['name' => 'Assist Star', 'is_active' => true]);

        MatchEvent::create([
            'match_id' => $match->id,
            'scorer_id' => $scorer->id,
            'assist_player_id' => $assistant->id,
            'event_type' => MatchEvent::TYPE_GOAL,
            'minute' => 5,
        ]);

        $this->get(route('public.leaderboard'))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->where('leaders.0.name', 'Scorer Star')
                ->where('leaders.0.goals', 1)
                ->where('leaders.1.name', 'Assist Star')
                ->where('leaders.1.assists', 1)
            );

        $this->get(route('public.players.show', $scorer))
            ->assertOk()
            ->assertInertia(fn ($page) => $page->where('player.goals', 1));
    }

    public function test_deleting_a_goal_event_updates_public_player_totals(): void
    {
        $admin = User::factory()->admin()->create();
        $match = FootballMatch::factory()->finished()->create();
        $scorer = Player::factory()->create(['name' => 'Scorer Star', 'is_active' => true]);

        $event = MatchEvent::create([
            'match_id' => $match->id,
            'scorer_id' => $scorer->id,
            'event_type' => MatchEvent::TYPE_GOAL,
            'minute' => 12,
        ]);

        $this->assertSame(1, $scorer->fresh()->goalsCount());

        Livewire::actingAs($admin)
            ->test(ManageFootballMatchLiveScoring::class, ['record' => $match->getRouteKey()])
            ->callTableAction('delete', $event->getKey())
            ->assertHasNoTableActionErrors();

        $this->assertSame(0, $scorer->fresh()->goalsCount());

        $this->get(route('public.leaderboard'))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->where('leaders.0.goals', 0)
            );
    }
}
