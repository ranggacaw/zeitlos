<?php

namespace Tests\Feature;

use App\Filament\Resources\FootballMatches\Pages\CreateFootballMatch;
use App\Filament\Resources\FootballMatches\Pages\EditFootballMatch;
use App\Filament\Resources\Players\Pages\CreatePlayer;
use App\Filament\Resources\Players\Pages\EditPlayer;
use App\Models\FootballMatch;
use App\Models\MatchEvent;
use App\Models\MatchRoster;
use App\Models\Player;
use App\Models\User;
use App\Team\WhatsAppRosterText;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Livewire\Livewire;
use Tests\TestCase;

class AdminTeamManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    public function test_guest_is_redirected_from_admin_management(): void
    {
        $this->get('/admin')->assertRedirect('/admin/login');
        $this->get(route('admin.dashboard'))->assertRedirect(route('login'));
        $this->get(route('admin.players.index'))->assertRedirect(route('login'));
    }

    public function test_non_admin_user_is_denied_admin_management(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get('/admin')
            ->assertForbidden();

        $this->actingAs($user)
            ->get(route('admin.dashboard'))
            ->assertForbidden();

        $this->actingAs($user)
            ->get(route('admin.players.index'))
            ->assertForbidden();
    }

    public function test_admin_can_access_team_management_dashboard(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->get('/admin')
            ->assertOk()
            ->assertSee('Zeitlos CMS');

        $this->actingAs($admin)
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Admin/Dashboard')
                ->where('playerCount', 0)
                ->where('activePlayerCount', 0)
                ->where('matchCount', 0)
                ->where('liveMatch', null)
                ->where('nextMatch', null)
                ->where('recentResult', null)
                ->has('topScorers', 0)
                ->has('topAssists', 0)
                ->has('recentMatches', 0)
            );
    }

    public function test_admin_dashboard_exposes_cms_overview_data(): void
    {
        $admin = User::factory()->admin()->create();
        $player = Player::factory()->create(['name' => 'Scorer Star', 'is_active' => true]);
        FootballMatch::factory()->create([
            'opponent' => 'Live FC',
            'status' => FootballMatch::STATUS_LIVE,
        ]);
        FootballMatch::factory()->create([
            'opponent' => 'Next FC',
            'status' => FootballMatch::STATUS_SCHEDULED,
            'match_date' => now()->addDay(),
        ]);
        FootballMatch::factory()->finished()->create(['opponent' => 'Past FC']);

        MatchEvent::create([
            'match_id' => FootballMatch::first()->id,
            'scorer_id' => $player->id,
            'event_type' => MatchEvent::TYPE_GOAL,
        ]);

        $this->actingAs($admin)
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Admin/Dashboard')
                ->where('playerCount', 1)
                ->where('activePlayerCount', 1)
                ->where('matchCount', 3)
                ->where('liveMatch.opponent', 'Live FC')
                ->where('nextMatch.opponent', 'Next FC')
                ->where('recentResult.opponent', 'Past FC')
                ->where('topScorers.0.name', 'Scorer Star')
                ->where('topScorers.0.goals', 1)
                ->has('recentMatches', 3)
            );
    }

    public function test_admin_can_create_and_update_a_player(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->post(route('admin.players.store'), [
                'name' => 'Max Mustermann',
                'jersey_number' => 10,
                'position' => 'Midfielder',
                'is_active' => true,
                'joined_at' => '2026-01-01',
                'goals_adjustment' => 1,
                'assists_adjustment' => 2,
            ])
            ->assertRedirect(route('admin.players.index'));

        $player = Player::where('name', 'Max Mustermann')->firstOrFail();
        $this->assertSame(10, $player->jersey_number);
        $this->assertSame(1, $player->goals_adjustment);

        $this->actingAs($admin)
            ->patch(route('admin.players.update', $player), [
                'name' => 'Maxi Mustermann',
                'jersey_number' => 11,
                'position' => 'Forward',
                'is_active' => false,
                'joined_at' => '2026-01-01',
                'goals_adjustment' => 3,
                'assists_adjustment' => 4,
            ])
            ->assertRedirect(route('admin.players.index'));

        $player->refresh();
        $this->assertSame('Maxi Mustermann', $player->name);
        $this->assertSame(11, $player->jersey_number);
        $this->assertFalse($player->is_active);
    }

    public function test_admin_can_create_and_update_a_player_through_filament(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin);

        Livewire::test(CreatePlayer::class)
            ->fillForm([
                'name' => 'Filament Player',
                'jersey_number' => 8,
                'position' => 'Midfielder',
                'is_active' => true,
                'photo_path' => 'players/filament.jpg',
                'joined_at' => '2026-02-01',
                'goals_adjustment' => 2,
                'assists_adjustment' => 3,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $player = Player::where('name', 'Filament Player')->firstOrFail();
        $this->assertSame(8, $player->jersey_number);

        Livewire::test(EditPlayer::class, ['record' => $player->getRouteKey()])
            ->fillForm([
                'name' => 'Updated Filament Player',
                'jersey_number' => 9,
                'position' => 'Forward',
                'is_active' => false,
                'photo_path' => 'players/updated.jpg',
                'joined_at' => '2026-02-01',
                'goals_adjustment' => 4,
                'assists_adjustment' => 5,
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $player->refresh();
        $this->assertSame('Updated Filament Player', $player->name);
        $this->assertSame(9, $player->jersey_number);
        $this->assertFalse($player->is_active);
    }

    public function test_admin_can_create_and_update_a_match(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->post(route('admin.matches.store'), [
                'opponent' => 'Riverside FC',
                'match_date' => '2026-08-01',
                'match_time' => '20:00',
                'venue' => 'Central Pitch',
                'status' => 'scheduled',
            ])
            ->assertRedirect(route('admin.matches.index'));

        $match = FootballMatch::where('opponent', 'Riverside FC')->firstOrFail();
        $this->assertSame('Central Pitch', $match->venue);

        $this->actingAs($admin)
            ->patch(route('admin.matches.update', $match), [
                'opponent' => 'Riverside FC',
                'match_date' => '2026-08-01',
                'match_time' => '21:00',
                'venue' => 'North Field',
                'status' => 'finished',
                'zeitlos_score' => 2,
                'opponent_score' => 1,
            ])
            ->assertRedirect(route('admin.matches.index'));

        $match->refresh();
        $this->assertSame('North Field', $match->venue);
        $this->assertSame('finished', $match->status);
        $this->assertSame(2, $match->zeitlos_score);
    }

    public function test_admin_can_create_and_update_a_match_through_filament(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin);

        Livewire::test(CreateFootballMatch::class)
            ->fillForm([
                'opponent' => 'Filament FC',
                'match_date' => '2026-08-15',
                'match_time' => '20:00',
                'venue' => 'CMS Arena',
                'maps_url' => 'https://maps.example.com/cms-arena',
                'payment_label' => 'Pitch share',
                'payment_amount' => 12.50,
                'whatsapp_announcement' => 'Kickoff at 20:00.',
                'status' => FootballMatch::STATUS_SCHEDULED,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $match = FootballMatch::where('opponent', 'Filament FC')->firstOrFail();
        $this->assertSame('CMS Arena', $match->venue);

        Livewire::test(EditFootballMatch::class, ['record' => $match->getRouteKey()])
            ->fillForm([
                'opponent' => 'Filament FC',
                'match_date' => '2026-08-15',
                'match_time' => '21:00',
                'venue' => 'Updated Arena',
                'maps_url' => 'https://maps.example.com/updated-arena',
                'payment_label' => 'Pitch share',
                'payment_amount' => 14.00,
                'whatsapp_announcement' => 'Kickoff moved to 21:00.',
                'status' => FootballMatch::STATUS_FINISHED,
                'zeitlos_score' => 3,
                'opponent_score' => 2,
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $match->refresh();
        $this->assertSame('Updated Arena', $match->venue);
        $this->assertSame(FootballMatch::STATUS_FINISHED, $match->status);
        $this->assertSame(3, $match->zeitlos_score);
    }

    public function test_admin_can_open_and_update_leaderboard_corrections(): void
    {
        $admin = User::factory()->admin()->create();
        $player = Player::factory()->create([
            'name' => 'Correction Player',
            'goals_adjustment' => 0,
            'assists_adjustment' => 0,
            'is_active' => true,
        ]);

        $this->actingAs($admin)
            ->get(route('admin.leaderboard.index'))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Admin/Leaderboard/Index')
                ->has('players', 1)
                ->where('players.0.name', 'Correction Player')
                ->where('players.0.goals', 0)
                ->where('players.0.assists', 0)
            );

        $this->actingAs($admin)
            ->patch(route('admin.leaderboard.update', $player), [
                'goals_adjustment' => 4,
                'assists_adjustment' => 2,
            ])
            ->assertRedirect(route('admin.leaderboard.index'));

        $player->refresh();
        $this->assertSame(4, $player->goals_adjustment);
        $this->assertSame(2, $player->assists_adjustment);

        $this->get(route('public.leaderboard'))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->where('leaders.0.name', 'Correction Player')
                ->where('leaders.0.goals', 4)
                ->where('leaders.0.assists', 2)
            );
    }

    public function test_admin_can_add_roster_entries_for_player_and_guest(): void
    {
        $admin = User::factory()->admin()->create();
        $match = FootballMatch::factory()->create();
        $player = Player::factory()->create();

        $this->actingAs($admin)
            ->post(route('admin.matches.roster.store', $match), [
                'player_id' => $player->id,
                'role' => MatchRoster::ROLE_PLAYER,
            ])
            ->assertRedirect(route('admin.matches.roster.index', $match));

        $this->actingAs($admin)
            ->post(route('admin.matches.roster.store', $match), [
                'guest_name' => 'Guest Substitute',
                'role' => MatchRoster::ROLE_GOALKEEPER,
            ])
            ->assertRedirect(route('admin.matches.roster.index', $match));

        $this->assertDatabaseHas('match_rosters', [
            'match_id' => $match->id,
            'player_id' => $player->id,
            'guest_name' => null,
            'role' => MatchRoster::ROLE_PLAYER,
        ]);

        $this->assertDatabaseHas('match_rosters', [
            'match_id' => $match->id,
            'player_id' => null,
            'guest_name' => 'Guest Substitute',
            'role' => MatchRoster::ROLE_GOALKEEPER,
        ]);
    }

    public function test_roster_entry_requires_either_player_or_guest_name(): void
    {
        $admin = User::factory()->admin()->create();
        $match = FootballMatch::factory()->create();
        $player = Player::factory()->create();

        $this->actingAs($admin)
            ->post(route('admin.matches.roster.store', $match), [
                'player_id' => $player->id,
                'guest_name' => 'Both Provided',
                'role' => MatchRoster::ROLE_PLAYER,
            ])
            ->assertSessionHasErrors('player_id');

        $this->actingAs($admin)
            ->post(route('admin.matches.roster.store', $match), [
                'role' => MatchRoster::ROLE_PLAYER,
            ])
            ->assertSessionHasErrors('guest_name');

        $this->assertDatabaseCount('match_rosters', 0);
    }

    public function test_whatsapp_roster_text_includes_match_details_and_grouped_names(): void
    {
        $match = FootballMatch::factory()->create([
            'opponent' => 'Riverside FC',
            'match_date' => '2026-08-01',
            'match_time' => '20:00',
            'venue' => 'Central Pitch',
            'whatsapp_announcement' => 'Please arrive 30 minutes early.',
        ]);

        $goalkeeper = Player::factory()->create(['name' => 'Anton Keller']);
        $squadPlayer = Player::factory()->create(['name' => 'Leo Fischer']);

        $roster = collect([
            MatchRoster::create([
                'match_id' => $match->id,
                'player_id' => $goalkeeper->id,
                'role' => MatchRoster::ROLE_GOALKEEPER,
            ]),
            MatchRoster::create([
                'match_id' => $match->id,
                'player_id' => $squadPlayer->id,
                'role' => MatchRoster::ROLE_PLAYER,
            ]),
            MatchRoster::create([
                'match_id' => $match->id,
                'guest_name' => 'Guest Sub',
                'role' => MatchRoster::ROLE_PLAYER,
            ]),
        ]);

        $text = app(WhatsAppRosterText::class)->build($match, $roster);

        $this->assertStringContainsString('Zeitlos vs Riverside FC', $text);
        $this->assertStringContainsString('Central Pitch', $text);
        $this->assertStringContainsString('20:00', $text);
        $this->assertStringContainsString('Please arrive 30 minutes early.', $text);
        $this->assertStringContainsString('Goalkeepers', $text);
        $this->assertStringContainsString('Anton Keller', $text);
        $this->assertStringContainsString('Squad', $text);
        $this->assertStringContainsString('Leo Fischer', $text);
        $this->assertStringContainsString('Guest Sub', $text);
    }

    public function test_roster_page_exposes_whatsapp_text(): void
    {
        $admin = User::factory()->admin()->create();
        $match = FootballMatch::factory()->create(['opponent' => 'Riverside FC']);
        $player = Player::factory()->create(['name' => 'Leo Fischer']);

        MatchRoster::create([
            'match_id' => $match->id,
            'player_id' => $player->id,
            'role' => MatchRoster::ROLE_PLAYER,
        ]);

        $this->actingAs($admin)
            ->get(route('admin.matches.roster.index', $match))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Admin/Matches/Roster')
                ->has('rosterEntries', 1)
                ->where('rosterEntries.0.name', 'Leo Fischer')
                ->has('whatsappText')
                ->where('whatsappText', fn (string $text) => str_contains($text, 'Leo Fischer') && str_contains($text, 'Riverside FC'))
            );
    }
}
