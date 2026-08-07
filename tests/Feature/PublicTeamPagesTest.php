<?php

namespace Tests\Feature;

use App\Models\FootballMatch;
use App\Models\MatchEvent;
use App\Models\Player;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class PublicTeamPagesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    public function test_public_dashboard_renders_seeded_team_summaries(): void
    {
        $this->seed(DatabaseSeeder::class);

        $this->get(route('public.home'))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Welcome')
                ->where('activeMatch', null)
                ->where('upcomingMatch.opponent', 'Riverside FC')
                ->where('recentResult.opponent', 'Old Town United')
                ->has('players', 17)
                ->has('leaders.0', fn (Assert $page) => $page
                    ->where('name', 'Dennis')
                    ->where('goals', 2)
                    ->etc()
                )
            );
    }

    public function test_admins_opening_public_home_are_redirected_to_admin(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->get(route('public.home'))
            ->assertRedirect('/admin');
    }

    public function test_public_top_bar_login_links_to_filament_admin_login(): void
    {
        $layout = file_get_contents(resource_path('js/Layouts/PublicLayout.jsx'));

        $this->assertStringContainsString('<a', $layout);
        $this->assertStringContainsString('href="/admin/login"', $layout);
        $this->assertStringNotContainsString("href={route('login')}", $layout);
    }

    public function test_public_schedule_renders_upcoming_and_finished_matches(): void
    {
        $this->seed(DatabaseSeeder::class);

        $this->get(route('public.schedule'))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Public/Schedule')
                ->has('activeMatches', 0)
                ->where('upcomingMatches.0.opponent', 'Riverside FC')
                ->where('upcomingMatches.0.roster.player.0.name', 'Hendry')
                ->where('finishedMatches.0.zeitlos_score', 3)
                ->where('finishedMatches.0.events.0.scorer', 'Dennis')
            );
    }

    public function test_public_roster_renders_active_players(): void
    {
        $this->seed(DatabaseSeeder::class);

        $this->get(route('public.roster'))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Public/Roster')
                ->has('players', 17)
                ->where('players.0.name', 'Gilang')
                ->where('players.0.jersey_number', 1)
                ->where('players.0.position', 'GK')
            );
    }

    public function test_public_leaderboard_renders_stat_totals(): void
    {
        $this->seed(DatabaseSeeder::class);

        $this->get(route('public.leaderboard'))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Public/Leaderboard')
                ->where('leaders.0.name', 'Dennis')
                ->where('leaders.0.goals', 2)
                ->where('leaders.0.assists', 0)
            );
    }

    public function test_public_leaderboard_defaults_to_goals_ordering(): void
    {
        Player::factory()->create([
            'name' => 'Goal Leader',
            'goals_adjustment' => 4,
            'assists_adjustment' => 1,
        ]);
        Player::factory()->create([
            'name' => 'Assist Leader',
            'goals_adjustment' => 1,
            'assists_adjustment' => 5,
        ]);

        $this->get(route('public.leaderboard'))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Public/Leaderboard')
                ->where('selectedStat', 'goals')
                ->where('leaders.0.name', 'Goal Leader')
                ->where('leaders.1.name', 'Assist Leader')
            );
    }

    public function test_public_leaderboard_can_order_by_assists(): void
    {
        Player::factory()->create([
            'name' => 'Goal Leader',
            'goals_adjustment' => 4,
            'assists_adjustment' => 1,
        ]);
        Player::factory()->create([
            'name' => 'Assist Leader',
            'goals_adjustment' => 1,
            'assists_adjustment' => 5,
        ]);

        $this->get(route('public.leaderboard', ['stat' => 'assists']))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Public/Leaderboard')
                ->where('selectedStat', 'assists')
                ->where('leaders.0.name', 'Assist Leader')
                ->where('leaders.1.name', 'Goal Leader')
            );
    }

    public function test_public_leaderboard_ignores_invalid_stat_filter(): void
    {
        Player::factory()->create([
            'name' => 'Goal Leader',
            'goals_adjustment' => 4,
            'assists_adjustment' => 1,
        ]);
        Player::factory()->create([
            'name' => 'Assist Leader',
            'goals_adjustment' => 1,
            'assists_adjustment' => 5,
        ]);

        $this->get(route('public.leaderboard', ['stat' => 'cards']))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Public/Leaderboard')
                ->where('selectedStat', 'goals')
                ->where('leaders.0.name', 'Goal Leader')
                ->where('leaders.1.name', 'Assist Leader')
            );
    }

    public function test_public_player_detail_renders_player_stats_and_matches(): void
    {
        $this->seed(DatabaseSeeder::class);

        $player = Player::where('name', 'Dennis')->firstOrFail();

        $this->get(route('public.players.show', $player))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Public/PlayerShow')
                ->where('player.name', 'Dennis')
                ->where('player.goals', 2)
                ->has('matches', 2)
            );
    }

    public function test_public_pages_render_empty_props_without_domain_data(): void
    {
        $this->get(route('public.home'))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Welcome')
                ->where('activeMatch', null)
                ->where('upcomingMatch', null)
                ->where('recentResult', null)
                ->has('players', 0)
                ->has('leaders', 0)
            );

        $this->get(route('public.schedule'))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Public/Schedule')
                ->has('activeMatches', 0)
                ->has('upcomingMatches', 0)
                ->has('finishedMatches', 0)
            );
    }

    public function test_public_dashboard_and_schedule_surface_active_match(): void
    {
        $match = FootballMatch::factory()->starting()->create(['opponent' => 'Live Rivals']);

        $this->get(route('public.home'))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Welcome')
                ->where('activeMatch.opponent', 'Live Rivals')
                ->where('activeMatch.status', FootballMatch::STATUS_STARTING)
            );

        $this->get(route('public.schedule'))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Public/Schedule')
                ->where('activeMatches.0.id', $match->id)
                ->where('activeMatches.0.status', FootballMatch::STATUS_STARTING)
            );
    }

    public function test_public_live_match_page_renders_score_events_and_finished_state(): void
    {
        $match = FootballMatch::factory()->live()->create([
            'opponent' => 'Stream FC',
            'zeitlos_score' => 1,
            'opponent_score' => 0,
        ]);
        $scorer = Player::factory()->create(['name' => 'Live Scorer']);

        MatchEvent::create([
            'match_id' => $match->id,
            'scorer_id' => $scorer->id,
            'event_type' => MatchEvent::TYPE_GOAL,
            'team' => MatchEvent::TEAM_ZEITLOS,
            'minute' => 12,
        ]);

        $this->get(route('public.matches.live', $match))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Public/MatchLive')
                ->where('match.opponent', 'Stream FC')
                ->where('match.status', FootballMatch::STATUS_LIVE)
                ->where('match.zeitlos_score', 1)
                ->where('match.events.0.scorer', 'Live Scorer')
            );

        $match->update(['status' => FootballMatch::STATUS_FINISHED]);

        $this->get(route('public.matches.live', $match))->assertOk();
    }

    public function test_scheduled_match_live_page_is_not_public(): void
    {
        $match = FootballMatch::factory()->create();

        $this->get(route('public.matches.live', $match))->assertNotFound();
    }

    public function test_inactive_player_detail_is_not_public(): void
    {
        $player = Player::factory()->create(['is_active' => false]);

        $this->get(route('public.players.show', $player))->assertNotFound();
    }

    public function test_dashboard_route_has_been_retired(): void
    {
        $this->assertFalse(Route::has('dashboard'));

        $this->get('/dashboard')->assertNotFound();
    }
}
