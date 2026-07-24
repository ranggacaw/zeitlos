<?php

namespace Tests\Feature;

use App\Models\Player;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
                ->where('upcomingMatch.opponent', 'Riverside FC')
                ->where('recentResult.opponent', 'Old Town United')
                ->has('players', 6)
                ->has('leaders.0', fn (Assert $page) => $page
                    ->where('name', 'Leo Fischer')
                    ->where('goals', 2)
                    ->etc()
                )
            );
    }

    public function test_public_schedule_renders_upcoming_and_finished_matches(): void
    {
        $this->seed(DatabaseSeeder::class);

        $this->get(route('public.schedule'))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Public/Schedule')
                ->where('upcomingMatches.0.opponent', 'Riverside FC')
                ->where('upcomingMatches.0.roster.player.0.name', 'Milan Becker')
                ->where('finishedMatches.0.zeitlos_score', 3)
                ->where('finishedMatches.0.events.0.scorer', 'Leo Fischer')
            );
    }

    public function test_public_roster_renders_active_players(): void
    {
        $this->seed(DatabaseSeeder::class);

        $this->get(route('public.roster'))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Public/Roster')
                ->has('players', 6)
                ->where('players.0.name', 'Anton Keller')
                ->where('players.0.jersey_number', 1)
                ->where('players.0.position', 'Goalkeeper')
            );
    }

    public function test_public_leaderboard_renders_stat_totals(): void
    {
        $this->seed(DatabaseSeeder::class);

        $this->get(route('public.leaderboard'))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Public/Leaderboard')
                ->where('leaders.0.name', 'Leo Fischer')
                ->where('leaders.0.goals', 2)
                ->where('leaders.0.assists', 1)
            );
    }

    public function test_public_player_detail_renders_player_stats_and_matches(): void
    {
        $this->seed(DatabaseSeeder::class);

        $player = Player::where('name', 'Leo Fischer')->firstOrFail();

        $this->get(route('public.players.show', $player))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Public/PlayerShow')
                ->where('player.name', 'Leo Fischer')
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
                ->where('upcomingMatch', null)
                ->where('recentResult', null)
                ->has('players', 0)
                ->has('leaders', 0)
            );

        $this->get(route('public.schedule'))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Public/Schedule')
                ->has('upcomingMatches', 0)
                ->has('finishedMatches', 0)
            );
    }

    public function test_inactive_player_detail_is_not_public(): void
    {
        $player = Player::factory()->create(['is_active' => false]);

        $this->get(route('public.players.show', $player))->assertNotFound();
    }

    public function test_authenticated_dashboard_route_remains_protected(): void
    {
        $this->get(route('dashboard'))->assertRedirect(route('login'));
        $this->assertSame('/dashboard', parse_url(route('dashboard'), PHP_URL_PATH));
    }
}
