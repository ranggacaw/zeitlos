<?php

namespace Tests\Feature;

use App\Filament\Resources\FootballMatches\Pages\CreateFootballMatch;
use App\Filament\Resources\FootballMatches\Pages\EditFootballMatch;
use App\Filament\Resources\FootballMatches\Pages\ManageFootballMatchLiveScoring;
use App\Filament\Resources\FootballMatches\Pages\ManageFootballMatchRosters;
use App\Filament\Resources\Players\Pages\CreatePlayer;
use App\Filament\Resources\Players\Pages\EditPlayer;
use App\Filament\Pages\Leaderboard;
use App\Models\FootballMatch;
use App\Models\MatchEvent;
use App\Models\MatchRoster;
use App\Models\Player;
use App\Models\User;
use App\Team\WhatsAppRosterText;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Illuminate\Support\Facades\Route;
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
    }

    public function test_non_admin_user_is_denied_admin_management(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get('/admin')
            ->assertForbidden();
    }

    public function test_admin_can_access_team_management_dashboard(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->get('/admin')
            ->assertOk()
            ->assertSee('Zeitlos CMS');
    }

    public function test_legacy_admin_entry_urls_redirect_to_filament_admin(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->get('/admin-legacy')
            ->assertRedirect('/admin');

        $this->actingAs($admin)
            ->get('/admin-legacy/matches/1/scoring')
            ->assertRedirect('/admin');
    }

    public function test_legacy_named_admin_routes_are_not_registered(): void
    {
        $this->assertFalse(Route::has('admin.dashboard'));
        $this->assertFalse(Route::has('admin.players.index'));
        $this->assertFalse(Route::has('admin.matches.index'));
        $this->assertFalse(Route::has('admin.leaderboard.index'));
        $this->assertFalse(Route::has('admin.matches.roster.index'));
        $this->assertFalse(Route::has('admin.matches.scoring.index'));
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

    public function test_admin_can_open_and_update_filament_leaderboard_corrections(): void
    {
        $admin = User::factory()->admin()->create();
        $match = FootballMatch::factory()->create();
        $player = Player::factory()->create([
            'name' => 'Filament Correction Player',
            'goals_adjustment' => 0,
            'assists_adjustment' => 0,
            'is_active' => true,
        ]);
        $assist = Player::factory()->create([
            'name' => 'Filament Assist Player',
            'is_active' => true,
        ]);

        MatchEvent::create([
            'match_id' => $match->id,
            'scorer_id' => $player->id,
            'assist_player_id' => $assist->id,
            'event_type' => MatchEvent::TYPE_GOAL,
        ]);

        $this->actingAs($admin)
            ->get('/admin/leaderboard')
            ->assertOk()
            ->assertSee('Leaderboard corrections')
            ->assertSee('Filament Correction Player')
            ->assertSee('Event goals')
            ->assertSee('Event assists');

        Livewire::actingAs($admin)
            ->test(Leaderboard::class)
            ->assertSuccessful()
            ->assertSee('Filament Correction Player')
            ->callTableAction('editAdjustments', $player->getKey(), [
                'goals_adjustment' => 3,
                'assists_adjustment' => 2,
            ])
            ->assertHasNoTableActionErrors();

        $player->refresh();
        $this->assertSame(3, $player->goals_adjustment);
        $this->assertSame(2, $player->assists_adjustment);

        $this->get(route('public.leaderboard'))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->where('leaders.0.name', 'Filament Correction Player')
                ->where('leaders.0.goals', 4)
                ->where('leaders.0.assists', 2)
                ->where('leaders.1.name', 'Filament Assist Player')
                ->where('leaders.1.goals', 0)
                ->where('leaders.1.assists', 1)
            );
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

    public function test_filament_roster_page_can_add_an_existing_player_entry(): void
    {
        $admin = User::factory()->admin()->create();
        $match = FootballMatch::factory()->create();
        $player = Player::factory()->create(['name' => 'Filament Player']);

        Livewire::actingAs($admin)
            ->test(ManageFootballMatchRosters::class, ['record' => $match->getRouteKey()])
            ->callAction('addRosterEntry', [
                'player_id' => $player->id,
                'guest_name' => null,
                'role' => MatchRoster::ROLE_PLAYER,
            ])
            ->assertHasNoActionErrors();

        $this->assertDatabaseHas('match_rosters', [
            'match_id' => $match->id,
            'player_id' => $player->id,
            'guest_name' => null,
            'role' => MatchRoster::ROLE_PLAYER,
        ]);
    }

    public function test_filament_roster_page_can_add_a_guest_entry_and_rejects_invalid_identity(): void
    {
        $admin = User::factory()->admin()->create();
        $match = FootballMatch::factory()->create();
        $player = Player::factory()->create();

        Livewire::actingAs($admin)
            ->test(ManageFootballMatchRosters::class, ['record' => $match->getRouteKey()])
            ->callAction('addRosterEntry', [
                'player_id' => null,
                'guest_name' => 'Guest Star',
                'role' => MatchRoster::ROLE_GOALKEEPER,
            ])
            ->assertHasNoActionErrors();

        $this->assertDatabaseHas('match_rosters', [
            'match_id' => $match->id,
            'player_id' => null,
            'guest_name' => 'Guest Star',
            'role' => MatchRoster::ROLE_GOALKEEPER,
        ]);

        Livewire::actingAs($admin)
            ->test(ManageFootballMatchRosters::class, ['record' => $match->getRouteKey()])
            ->callAction('addRosterEntry', [
                'player_id' => $player->id,
                'guest_name' => 'Both Provided',
                'role' => MatchRoster::ROLE_PLAYER,
            ])
            ->assertHasActionErrors(['player_id']);

        Livewire::actingAs($admin)
            ->test(ManageFootballMatchRosters::class, ['record' => $match->getRouteKey()])
            ->callAction('addRosterEntry', [
                'player_id' => null,
                'guest_name' => null,
                'role' => MatchRoster::ROLE_PLAYER,
            ])
            ->assertHasActionErrors(['guest_name']);

        $this->assertDatabaseCount('match_rosters', 1);
    }

    public function test_filament_roster_page_exposes_whatsapp_text_and_grouped_roster(): void
    {
        $admin = User::factory()->admin()->create();
        $match = FootballMatch::factory()->create([
            'opponent' => 'Riverside FC',
            'match_date' => '2026-08-01',
            'match_time' => '20:00',
            'venue' => 'Central Pitch',
        ]);
        $goalkeeper = Player::factory()->create(['name' => 'Anton Keller']);
        $squadPlayer = Player::factory()->create(['name' => 'Leo Fischer']);

        MatchRoster::create([
            'match_id' => $match->id,
            'player_id' => $goalkeeper->id,
            'role' => MatchRoster::ROLE_GOALKEEPER,
        ]);
        MatchRoster::create([
            'match_id' => $match->id,
            'player_id' => $squadPlayer->id,
            'role' => MatchRoster::ROLE_PLAYER,
        ]);

        Livewire::actingAs($admin)
            ->test(ManageFootballMatchRosters::class, ['record' => $match->getRouteKey()])
            ->assertSuccessful()
            ->assertSee('vs Riverside FC')
            ->assertSee('Anton Keller')
            ->assertSee('Leo Fischer')
            ->assertSee('Goalkeepers')
            ->assertSee('Squad')
            ->assertSee('Zeitlos vs Riverside FC')
            ->assertSee('Central Pitch')
            ->assertSee('WhatsApp message');
    }

    public function test_filament_roster_page_can_remove_a_roster_entry(): void
    {
        $admin = User::factory()->admin()->create();
        $match = FootballMatch::factory()->create();
        $player = Player::factory()->create(['name' => 'Removable Player']);

        $roster = MatchRoster::create([
            'match_id' => $match->id,
            'player_id' => $player->id,
            'role' => MatchRoster::ROLE_PLAYER,
        ]);

        Livewire::actingAs($admin)
            ->test(ManageFootballMatchRosters::class, ['record' => $match->getRouteKey()])
            ->callTableAction('delete', $roster->getKey())
            ->assertHasNoTableActionErrors();

        $this->assertDatabaseMissing('match_rosters', [
            'id' => $roster->getKey(),
        ]);
    }

    public function test_filament_live_scoring_can_mark_a_match_live(): void
    {
        $admin = User::factory()->admin()->create();
        $match = FootballMatch::factory()->create([
            'status' => FootballMatch::STATUS_SCHEDULED,
            'zeitlos_score' => null,
            'opponent_score' => null,
        ]);

        Livewire::actingAs($admin)
            ->test(ManageFootballMatchLiveScoring::class, ['record' => $match->getRouteKey()])
            ->assertSuccessful()
            ->assertSee('Scheduled')
            ->callAction('markLive')
            ->assertHasNoActionErrors();

        $match->refresh();
        $this->assertSame(FootballMatch::STATUS_LIVE, $match->status);
        $this->assertNull($match->zeitlos_score);
        $this->assertNull($match->opponent_score);
    }

    public function test_filament_live_scoring_can_record_and_delete_a_goal_event(): void
    {
        $admin = User::factory()->admin()->create();
        $match = FootballMatch::factory()->create();
        $scorer = Player::factory()->create(['name' => 'Goal Scorer']);
        $assist = Player::factory()->create(['name' => 'Assist Maker']);

        MatchRoster::create([
            'match_id' => $match->id,
            'player_id' => $scorer->id,
            'role' => MatchRoster::ROLE_PLAYER,
        ]);
        MatchRoster::create([
            'match_id' => $match->id,
            'player_id' => $assist->id,
            'role' => MatchRoster::ROLE_PLAYER,
        ]);

        Livewire::actingAs($admin)
            ->test(ManageFootballMatchLiveScoring::class, ['record' => $match->getRouteKey()])
            ->assertSee('Goal Scorer')
            ->callAction('recordGoal', [
                'scorer_id' => $scorer->id,
                'assist_player_id' => $assist->id,
                'minute' => 12,
            ])
            ->assertHasNoActionErrors();

        $event = MatchEvent::firstOrFail();
        $this->assertSame($match->id, $event->match_id);
        $this->assertSame($scorer->id, $event->scorer_id);
        $this->assertSame($assist->id, $event->assist_player_id);
        $this->assertSame(MatchEvent::TYPE_GOAL, $event->event_type);
        $this->assertSame(12, $event->minute);

        Livewire::actingAs($admin)
            ->test(ManageFootballMatchLiveScoring::class, ['record' => $match->getRouteKey()])
            ->assertSee('Goal Scorer')
            ->callTableAction('delete', $event->getKey())
            ->assertHasNoTableActionErrors();

        $this->assertDatabaseMissing('match_events', [
            'id' => $event->getKey(),
        ]);
    }

    public function test_filament_live_scoring_can_submit_final_score(): void
    {
        $admin = User::factory()->admin()->create();
        $match = FootballMatch::factory()->create([
            'status' => FootballMatch::STATUS_LIVE,
            'zeitlos_score' => null,
            'opponent_score' => null,
        ]);

        Livewire::actingAs($admin)
            ->test(ManageFootballMatchLiveScoring::class, ['record' => $match->getRouteKey()])
            ->callAction('finalizeMatch', [
                'zeitlos_score' => 4,
                'opponent_score' => 2,
            ])
            ->assertHasNoActionErrors();

        $match->refresh();
        $this->assertSame(FootballMatch::STATUS_FINISHED, $match->status);
        $this->assertSame(4, $match->zeitlos_score);
        $this->assertSame(2, $match->opponent_score);
    }

    public function test_filament_live_scoring_goal_updates_public_stats(): void
    {
        $admin = User::factory()->admin()->create();
        $match = FootballMatch::factory()->create();
        $scorer = Player::factory()->create(['name' => 'Public Scorer', 'is_active' => true]);
        $assist = Player::factory()->create(['name' => 'Public Assist', 'is_active' => true]);

        Livewire::actingAs($admin)
            ->test(ManageFootballMatchLiveScoring::class, ['record' => $match->getRouteKey()])
            ->callAction('recordGoal', [
                'scorer_id' => $scorer->id,
                'assist_player_id' => $assist->id,
                'minute' => null,
            ])
            ->assertHasNoActionErrors();

        $this->get(route('public.leaderboard'))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->where('leaders.0.name', 'Public Scorer')
                ->where('leaders.0.goals', 1)
                ->where('leaders.0.assists', 0)
                ->where('leaders.1.name', 'Public Assist')
                ->where('leaders.1.goals', 0)
                ->where('leaders.1.assists', 1)
            );
    }
}
