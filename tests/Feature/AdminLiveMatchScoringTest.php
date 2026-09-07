<?php

namespace Tests\Feature;

use App\Events\PublicMatchUpdated;
use App\Filament\Resources\FootballMatches\Pages\ManageFootballMatchLiveScoring;
use App\Models\FootballMatch;
use App\Models\MatchEvent;
use App\Models\MatchRoster;
use App\Models\Player;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Event;
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

    protected function tearDown(): void
    {
        Carbon::setTestNow();

        parent::tearDown();
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
        Carbon::setTestNow('2026-08-31 20:42:00');

        $admin = User::factory()->admin()->create();
        $match = FootballMatch::factory()->live()->create([
            'live_started_at' => now()->subMinutes(42),
        ]);
        $scorer = Player::factory()->create();
        $assistant = Player::factory()->create();

        Livewire::actingAs($admin)
            ->test(ManageFootballMatchLiveScoring::class, ['record' => $match->getRouteKey()])
            ->callAction('recordGoal', [
                'scorer_id' => $scorer->id,
                'assist_player_id' => $assistant->id,
            ])
            ->assertHasNoActionErrors();

        $this->assertDatabaseHas('match_events', [
            'match_id' => $match->id,
            'scorer_id' => $scorer->id,
            'assist_player_id' => $assistant->id,
            'event_type' => MatchEvent::TYPE_GOAL,
            'team' => MatchEvent::TEAM_ZEITLOS,
            'minute' => 42,
        ]);
        $this->assertSame(1, $match->fresh()->zeitlos_score);
    }

    public function test_admin_can_record_a_goal_and_assist_for_guest_roster_players(): void
    {
        Carbon::setTestNow('2026-08-31 20:42:00');

        $admin = User::factory()->admin()->create();
        $match = FootballMatch::factory()->live()->create([
            'live_started_at' => now()->subMinutes(42),
        ]);
        $scorer = MatchRoster::factory()->create([
            'match_id' => $match->id,
            'player_id' => null,
            'guest_name' => 'Guest Scorer',
        ]);
        $assistant = MatchRoster::factory()->create([
            'match_id' => $match->id,
            'player_id' => null,
            'guest_name' => 'Guest Assistant',
        ]);

        Livewire::actingAs($admin)
            ->test(ManageFootballMatchLiveScoring::class, ['record' => $match->getRouteKey()])
            ->callAction('recordGoal', [
                'scorer_id' => 'guest:'.$scorer->id,
                'assist_player_id' => 'guest:'.$assistant->id,
            ])
            ->assertHasNoActionErrors();

        $this->assertDatabaseHas('match_events', [
            'match_id' => $match->id,
            'scorer_id' => null,
            'guest_scorer_name' => 'Guest Scorer',
            'assist_player_id' => null,
            'guest_assist_name' => 'Guest Assistant',
            'event_type' => MatchEvent::TYPE_GOAL,
            'team' => MatchEvent::TEAM_ZEITLOS,
            'minute' => 42,
        ]);

        $this->get(route('public.matches.live', $match))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->where('match.events.0.scorer', 'Guest Scorer')
                ->where('match.events.0.assist', 'Guest Assistant')
            );
    }

    public function test_admin_can_record_an_opponent_goal_without_a_scorer_name(): void
    {
        Carbon::setTestNow('2026-08-31 20:31:00');

        $admin = User::factory()->admin()->create();
        $match = FootballMatch::factory()->live()->create([
            'zeitlos_score' => 0,
            'opponent_score' => 0,
            'live_started_at' => now()->subMinutes(31),
        ]);

        Livewire::actingAs($admin)
            ->test(ManageFootballMatchLiveScoring::class, ['record' => $match->getRouteKey()])
            ->callAction('recordOpponentGoal')
            ->assertHasNoActionErrors();

        $match->refresh();
        $this->assertSame(0, $match->zeitlos_score);
        $this->assertSame(1, $match->opponent_score);
        $this->assertDatabaseHas('match_events', [
            'match_id' => $match->id,
            'scorer_id' => null,
            'event_type' => MatchEvent::TYPE_GOAL,
            'team' => MatchEvent::TEAM_OPPONENT,
            'minute' => 31,
        ]);

        $this->get(route('public.matches.live', $match))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->where('match.zeitlos_score', 0)
                ->where('match.opponent_score', 1)
                ->where('match.events.0.team', MatchEvent::TEAM_OPPONENT)
                ->where('match.events.0.scorer', 'Enemy team')
            );
    }

    public function test_public_live_score_uses_goal_events_when_stored_live_score_is_stale(): void
    {
        $admin = User::factory()->admin()->create();
        $match = FootballMatch::factory()->live()->create([
            'zeitlos_score' => 2,
            'opponent_score' => 1,
        ]);
        $scorer = Player::factory()->create(['name' => 'Late Scorer']);

        foreach ([3, 5, 6] as $minute) {
            MatchEvent::create([
                'match_id' => $match->id,
                'scorer_id' => $scorer->id,
                'event_type' => MatchEvent::TYPE_GOAL,
                'team' => MatchEvent::TEAM_ZEITLOS,
                'minute' => $minute,
            ]);
        }

        MatchEvent::create([
            'match_id' => $match->id,
            'scorer_id' => null,
            'event_type' => MatchEvent::TYPE_GOAL,
            'team' => MatchEvent::TEAM_OPPONENT,
            'minute' => 4,
        ]);

        Livewire::actingAs($admin)
            ->test(ManageFootballMatchLiveScoring::class, ['record' => $match->getRouteKey()])
            ->assertSee('Zeitlos 3 : 1');

        $this->get(route('public.matches.live', $match))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->where('match.zeitlos_score', 3)
                ->where('match.opponent_score', 1)
            );
    }

    public function test_admin_can_mark_a_match_live(): void
    {
        Carbon::setTestNow('2026-08-31 20:00:00');

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
        $this->assertTrue($match->live_started_at->equalTo(now()));
        $this->assertNull($match->zeitlos_score);
        $this->assertNull($match->opponent_score);
    }

    public function test_starting_a_live_match_preserves_its_existing_clock_start(): void
    {
        Carbon::setTestNow('2026-08-31 20:00:00');

        $admin = User::factory()->admin()->create();
        $clockStart = now()->subMinutes(5);
        $match = FootballMatch::factory()->starting()->create([
            'live_started_at' => $clockStart,
        ]);

        Livewire::actingAs($admin)
            ->test(ManageFootballMatchLiveScoring::class, ['record' => $match->getRouteKey()])
            ->callAction('markLive')
            ->assertHasNoActionErrors();

        $this->assertTrue($match->fresh()->live_started_at->equalTo($clockStart));
    }

    public function test_admin_can_mark_a_match_starting(): void
    {
        $admin = User::factory()->admin()->create();
        $match = FootballMatch::factory()->create([
            'status' => FootballMatch::STATUS_SCHEDULED,
        ]);

        Livewire::actingAs($admin)
            ->test(ManageFootballMatchLiveScoring::class, ['record' => $match->getRouteKey()])
            ->callAction('markStarting')
            ->assertHasNoActionErrors();

        $this->assertSame(FootballMatch::STATUS_STARTING, $match->fresh()->status);
        $this->assertNull($match->fresh()->live_started_at);
    }

    public function test_live_scoring_changes_dispatch_public_match_updates(): void
    {
        Event::fake([PublicMatchUpdated::class]);

        $admin = User::factory()->admin()->create();
        $match = FootballMatch::factory()->create();
        $scorer = Player::factory()->create();

        Livewire::actingAs($admin)
            ->test(ManageFootballMatchLiveScoring::class, ['record' => $match->getRouteKey()])
            ->callAction('markStarting')
            ->callAction('markLive')
            ->callAction('recordGoal', [
                'scorer_id' => $scorer->id,
            ])
            ->assertHasNoActionErrors();

        Event::assertDispatched(PublicMatchUpdated::class, 3);
    }

    public function test_recording_a_goal_requires_a_started_live_clock(): void
    {
        $admin = User::factory()->admin()->create();
        $match = FootballMatch::factory()->create();
        $scorer = Player::factory()->create();

        Livewire::actingAs($admin)
            ->test(ManageFootballMatchLiveScoring::class, ['record' => $match->getRouteKey()])
            ->callAction('recordGoal', [
                'scorer_id' => $scorer->id,
            ])
            ->assertHasActionErrors();

        $this->assertDatabaseCount('match_events', 0);
    }

    public function test_recording_a_goal_requires_a_valid_scorer(): void
    {
        $admin = User::factory()->admin()->create();
        $match = FootballMatch::factory()->live()->create([
            'live_started_at' => now(),
        ]);

        Livewire::actingAs($admin)
            ->test(ManageFootballMatchLiveScoring::class, ['record' => $match->getRouteKey()])
            ->callAction('recordGoal', [
                'scorer_id' => 999,
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
        $this->assertSame(0, $match->fresh()->zeitlos_score);
    }

    public function test_admin_can_finalize_match_score(): void
    {
        Carbon::setTestNow('2026-08-31 21:30:00');

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
        $this->assertTrue($match->live_finished_at->equalTo(now()));
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
        $match = FootballMatch::factory()->live()->create();
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

    public function test_finished_match_live_scoring_is_read_only(): void
    {
        $admin = User::factory()->admin()->create();
        $match = FootballMatch::factory()->finished()->create();
        $scorer = Player::factory()->create(['name' => 'Finished Scorer', 'is_active' => true]);

        $event = MatchEvent::create([
            'match_id' => $match->id,
            'scorer_id' => $scorer->id,
            'event_type' => MatchEvent::TYPE_GOAL,
            'minute' => 9,
        ]);

        Livewire::actingAs($admin)
            ->test(ManageFootballMatchLiveScoring::class, ['record' => $match->getRouteKey()])
            ->assertActionHidden('recordGoal')
            ->assertActionHidden('recordOpponentGoal')
            ->assertActionHidden('finalizeMatch')
            ->assertTableActionHidden('delete', $event->getKey());
    }

    public function test_superadmin_goal_correction_uses_the_frozen_clock(): void
    {
        Carbon::setTestNow('2026-08-31 22:00:00');

        $superadmin = User::factory()->superadmin()->create();
        $match = FootballMatch::factory()->finished()->create([
            'live_started_at' => now()->subHours(2),
            'live_finished_at' => now()->subMinutes(75),
        ]);

        Livewire::actingAs($superadmin)
            ->test(ManageFootballMatchLiveScoring::class, ['record' => $match->getRouteKey()])
            ->callAction('recordOpponentGoal')
            ->assertHasNoActionErrors();

        $this->assertDatabaseHas('match_events', [
            'match_id' => $match->id,
            'team' => MatchEvent::TEAM_OPPONENT,
            'minute' => 45,
        ]);
    }

    public function test_superadmin_refinalization_preserves_the_original_clock_finish(): void
    {
        Carbon::setTestNow('2026-08-31 22:00:00');

        $superadmin = User::factory()->superadmin()->create();
        $clockFinish = now()->subMinutes(75);
        $match = FootballMatch::factory()->finished()->create([
            'live_started_at' => now()->subHours(2),
            'live_finished_at' => $clockFinish,
        ]);

        Livewire::actingAs($superadmin)
            ->test(ManageFootballMatchLiveScoring::class, ['record' => $match->getRouteKey()])
            ->callAction('finalizeMatch', [
                'zeitlos_score' => 4,
                'opponent_score' => 2,
            ])
            ->assertHasNoActionErrors();

        $this->assertTrue($match->fresh()->live_finished_at->equalTo($clockFinish));
    }
}
