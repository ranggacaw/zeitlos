<?php

namespace Tests\Feature;

use App\Models\FootballMatch;
use App\Models\MatchEvent;
use App\Models\MatchRoster;
use App\Models\Player;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
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

        $this->get(route('admin.matches.scoring.index', $match))->assertRedirect(route('login'));
    }

    public function test_non_admin_user_is_denied_scoring_console(): void
    {
        $user = User::factory()->create();
        $match = FootballMatch::factory()->create();

        $this->actingAs($user)
            ->get(route('admin.matches.scoring.index', $match))
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

        $this->actingAs($admin)
            ->get(route('admin.matches.scoring.index', $match))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Admin/Matches/Scoring')
                ->where('match.id', $match->id)
                ->has('scoringPlayers', 1)
                ->where('scoringPlayers.0.name', 'Leo Fischer')
                ->has('events', 1)
                ->where('events.0.scorer', 'Leo Fischer')
                ->where('events.0.assist', 'Max Schmidt')
                ->where('events.0.minute', 23)
            );
    }

    public function test_scoring_console_falls_back_to_active_players_when_roster_is_empty(): void
    {
        $admin = User::factory()->admin()->create();
        $match = FootballMatch::factory()->create();
        Player::factory()->create(['name' => 'Active Striker', 'is_active' => true]);
        Player::factory()->create(['name' => 'Inactive Sub', 'is_active' => false]);

        $this->actingAs($admin)
            ->get(route('admin.matches.scoring.index', $match))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->has('scoringPlayers', 1)
                ->where('scoringPlayers.0.name', 'Active Striker')
            );
    }

    public function test_admin_can_record_a_goal_event(): void
    {
        $admin = User::factory()->admin()->create();
        $match = FootballMatch::factory()->create();
        $scorer = Player::factory()->create();
        $assistant = Player::factory()->create();

        $this->actingAs($admin)
            ->post(route('admin.matches.scoring.events.store', $match), [
                'scorer_id' => $scorer->id,
                'assist_player_id' => $assistant->id,
                'minute' => 42,
            ])
            ->assertRedirect(route('admin.matches.scoring.index', $match));

        $this->assertDatabaseHas('match_events', [
            'match_id' => $match->id,
            'scorer_id' => $scorer->id,
            'assist_player_id' => $assistant->id,
            'event_type' => MatchEvent::TYPE_GOAL,
            'minute' => 42,
        ]);
    }

    public function test_recording_a_goal_requires_a_valid_scorer(): void
    {
        $admin = User::factory()->admin()->create();
        $match = FootballMatch::factory()->create();

        $this->actingAs($admin)
            ->post(route('admin.matches.scoring.events.store', $match), [
                'scorer_id' => 999,
                'minute' => 10,
            ])
            ->assertSessionHasErrors('scorer_id');

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

        $this->actingAs($admin)
            ->delete(route('admin.matches.scoring.events.destroy', [$match, $event]))
            ->assertRedirect(route('admin.matches.scoring.index', $match));

        $this->assertDatabaseMissing('match_events', ['id' => $event->id]);
    }

    public function test_admin_can_finalize_match_score(): void
    {
        $admin = User::factory()->admin()->create();
        $match = FootballMatch::factory()->create();

        $this->actingAs($admin)
            ->post(route('admin.matches.scoring.final-score.store', $match), [
                'zeitlos_score' => 2,
                'opponent_score' => 1,
            ])
            ->assertRedirect(route('admin.matches.scoring.index', $match));

        $match->refresh();
        $this->assertSame('finished', $match->status);
        $this->assertSame(2, $match->zeitlos_score);
        $this->assertSame(1, $match->opponent_score);
    }

    public function test_finalizing_requires_both_scores(): void
    {
        $admin = User::factory()->admin()->create();
        $match = FootballMatch::factory()->create();

        $this->actingAs($admin)
            ->post(route('admin.matches.scoring.final-score.store', $match), [
                'zeitlos_score' => 2,
            ])
            ->assertSessionHasErrors('opponent_score');

        $this->assertSame('scheduled', $match->fresh()->status);
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
            ->assertInertia(fn (Assert $page) => $page
                ->where('leaders.0.name', 'Scorer Star')
                ->where('leaders.0.goals', 1)
                ->where('leaders.1.name', 'Assist Star')
                ->where('leaders.1.assists', 1)
            );

        $this->get(route('public.players.show', $scorer))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page->where('player.goals', 1));
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

        $this->actingAs($admin)
            ->delete(route('admin.matches.scoring.events.destroy', [$match, $event]));

        $this->assertSame(0, $scorer->fresh()->goalsCount());

        $this->get(route('public.leaderboard'))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->where('leaders.0.goals', 0)
            );
    }
}
