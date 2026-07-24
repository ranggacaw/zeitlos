<?php

namespace Tests\Feature;

use App\Models\FootballMatch;
use App\Models\MatchRoster;
use App\Models\Player;
use App\Models\User;
use App\Team\WhatsAppRosterText;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
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
        $this->get(route('admin.dashboard'))->assertRedirect(route('login'));
        $this->get(route('admin.players.index'))->assertRedirect(route('login'));
    }

    public function test_non_admin_user_is_denied_admin_management(): void
    {
        $user = User::factory()->create();

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
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Admin/Dashboard')
                ->where('playerCount', 0)
                ->where('matchCount', 0)
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
