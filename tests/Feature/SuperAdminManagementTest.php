<?php

namespace Tests\Feature;

use App\Filament\Resources\FootballMatches\Pages\ManageFootballMatchLiveScoring;
use App\Filament\Resources\FootballMatches\Pages\ManageFootballMatchRosters;
use App\Filament\Resources\FootballMatches\Tables\FootballMatchesTable;
use App\Filament\Resources\Users\Pages\CreateUser;
use App\Filament\Resources\Users\Pages\EditUser;
use App\Filament\Resources\Users\Pages\ListUsers;
use App\Filament\Resources\Users\UserResource;
use App\Models\FootballMatch;
use App\Models\MatchEvent;
use App\Models\MatchRoster;
use App\Models\Player;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class SuperAdminManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    public function test_user_roles_and_privilege_helpers(): void
    {
        $superadmin = User::factory()->superadmin()->create();
        $admin = User::factory()->admin()->create();
        $regularUser = User::factory()->create();

        $this->assertTrue($superadmin->isSuperAdmin());
        $this->assertTrue($superadmin->isAdmin());

        $this->assertFalse($admin->isSuperAdmin());
        $this->assertTrue($admin->isAdmin());

        $this->assertFalse($regularUser->isSuperAdmin());
        $this->assertFalse($regularUser->isAdmin());
    }

    public function test_superadmin_can_manage_roster_on_finished_match(): void
    {
        $superadmin = User::factory()->superadmin()->create();
        $admin = User::factory()->admin()->create();
        $match = FootballMatch::factory()->finished()->create();
        $player = Player::factory()->create(['name' => 'Super Player']);

        // Standard admin sees read-only subheading and hidden actions
        Livewire::actingAs($admin)
            ->test(ManageFootballMatchRosters::class, ['record' => $match->getRouteKey()])
            ->assertSee('Finalized · read-only')
            ->assertActionHidden('addRosterEntry')
            ->assertActionHidden('importWhatsAppTemplate');

        // Superadmin sees SuperAdmin edit mode and visible actions
        Livewire::actingAs($superadmin)
            ->test(ManageFootballMatchRosters::class, ['record' => $match->getRouteKey()])
            ->assertSee('Finalized · SuperAdmin edit mode')
            ->assertActionVisible('addRosterEntry')
            ->assertActionVisible('importWhatsAppTemplate')
            ->callAction('addRosterEntry', [
                'player_id' => $player->id,
                'guest_name' => null,
                'role' => MatchRoster::ROLE_PLAYER,
            ])
            ->assertHasNoActionErrors();

        $rosterEntry = MatchRoster::where('match_id', $match->id)->where('player_id', $player->id)->firstOrFail();

        // Superadmin can delete roster entry on finished match
        Livewire::actingAs($superadmin)
            ->test(ManageFootballMatchRosters::class, ['record' => $match->getRouteKey()])
            ->assertTableActionVisible('delete', $rosterEntry->getKey())
            ->callTableAction('delete', $rosterEntry->getKey())
            ->assertHasNoTableActionErrors();

        $this->assertDatabaseMissing('match_rosters', ['id' => $rosterEntry->getKey()]);
    }

    public function test_superadmin_can_manage_scoring_on_finished_match(): void
    {
        $superadmin = User::factory()->superadmin()->create();
        $admin = User::factory()->admin()->create();
        $match = FootballMatch::factory()->finished()->create([
            'zeitlos_score' => 2,
            'opponent_score' => 1,
        ]);
        $scorer = Player::factory()->create(['name' => 'Scoring Legend', 'is_active' => true]);

        // Standard admin sees read-only subheading and hidden scoring actions
        Livewire::actingAs($admin)
            ->test(ManageFootballMatchLiveScoring::class, ['record' => $match->getRouteKey()])
            ->assertSee('Finalized · read-only')
            ->assertActionHidden('recordGoal')
            ->assertActionHidden('recordOpponentGoal')
            ->assertActionHidden('finalizeMatch');

        // Superadmin sees SuperAdmin edit mode and can record Zeitlos goal
        Livewire::actingAs($superadmin)
            ->test(ManageFootballMatchLiveScoring::class, ['record' => $match->getRouteKey()])
            ->assertSee('Finalized · SuperAdmin edit mode')
            ->assertActionVisible('recordGoal')
            ->assertActionVisible('recordOpponentGoal')
            ->assertActionVisible('finalizeMatch')
            ->callAction('recordGoal', [
                'scorer_id' => $scorer->id,
                'minute' => 88,
            ])
            ->assertHasNoActionErrors();

        $event = MatchEvent::where('match_id', $match->id)->where('scorer_id', $scorer->id)->firstOrFail();

        // Superadmin can record opponent goal on finished match
        Livewire::actingAs($superadmin)
            ->test(ManageFootballMatchLiveScoring::class, ['record' => $match->getRouteKey()])
            ->callAction('recordOpponentGoal', [
                'minute' => 90,
            ])
            ->assertHasNoActionErrors();

        // Superadmin can delete goal on finished match
        Livewire::actingAs($superadmin)
            ->test(ManageFootballMatchLiveScoring::class, ['record' => $match->getRouteKey()])
            ->assertTableActionVisible('delete', $event->getKey())
            ->callTableAction('delete', $event->getKey())
            ->assertHasNoTableActionErrors();

        $this->assertDatabaseMissing('match_events', ['id' => $event->getKey()]);

        // Superadmin can re-finalize match score
        Livewire::actingAs($superadmin)
            ->test(ManageFootballMatchLiveScoring::class, ['record' => $match->getRouteKey()])
            ->callAction('finalizeMatch', [
                'zeitlos_score' => 5,
                'opponent_score' => 3,
            ])
            ->assertHasNoActionErrors();

        $match->refresh();
        $this->assertSame(5, $match->zeitlos_score);
        $this->assertSame(3, $match->opponent_score);
    }

    public function test_superadmin_can_delete_matches_including_finished_matches(): void
    {
        $superadmin = User::factory()->superadmin()->create();
        $match = FootballMatch::factory()->finished()->create();

        MatchRoster::create([
            'match_id' => $match->id,
            'guest_name' => 'Temporary Player',
            'role' => MatchRoster::ROLE_PLAYER,
        ]);

        $this->actingAs($superadmin);

        $match->delete();

        $this->assertDatabaseMissing('matches', ['id' => $match->id]);
    }

    public function test_user_management_resource_authorization(): void
    {
        $superadmin = User::factory()->superadmin()->create();
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();

        // Guest redirected
        $this->get('/admin/users')->assertRedirect('/admin/login');

        // Regular user forbidden
        $this->actingAs($user)->get('/admin/users')->assertForbidden();

        // Standard admin forbidden
        $this->actingAs($admin)->get('/admin/users')->assertForbidden();

        // SuperAdmin can access
        $this->actingAs($superadmin)->get('/admin/users')->assertOk();
    }

    public function test_superadmin_can_create_edit_and_delete_users_with_self_deletion_guard(): void
    {
        $superadmin = User::factory()->superadmin()->create(['email' => 'main-super@zeitlos.test']);

        $this->actingAs($superadmin);

        // List page loads successfully
        Livewire::test(ListUsers::class)
            ->assertSuccessful()
            ->assertSee('main-super@zeitlos.test');

        // Create new admin user
        Livewire::test(CreateUser::class)
            ->fillForm([
                'name' => 'New Staff Admin',
                'email' => 'staff@zeitlos.test',
                'role' => User::ROLE_ADMIN,
                'password' => 'secret1234',
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $createdUser = User::where('email', 'staff@zeitlos.test')->firstOrFail();
        $this->assertSame(User::ROLE_ADMIN, $createdUser->role);
        $this->assertSame('New Staff Admin', $createdUser->name);

        // Edit user role to superadmin
        Livewire::test(EditUser::class, ['record' => $createdUser->getRouteKey()])
            ->fillForm([
                'name' => 'Promoted SuperAdmin',
                'email' => 'staff@zeitlos.test',
                'role' => User::ROLE_SUPERADMIN,
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $createdUser->refresh();
        $this->assertSame(User::ROLE_SUPERADMIN, $createdUser->role);
        $this->assertSame('Promoted SuperAdmin', $createdUser->name);

        // Superadmin cannot delete themselves
        $this->assertFalse(UserResource::canDelete($superadmin));
        Livewire::test(EditUser::class, ['record' => $superadmin->getRouteKey()])
            ->assertActionHidden('delete');

        // Superadmin CAN delete other users
        $this->assertTrue(UserResource::canDelete($createdUser));
        Livewire::test(EditUser::class, ['record' => $createdUser->getRouteKey()])
            ->assertActionVisible('delete')
            ->callAction('delete')
            ->assertHasNoActionErrors();

        $this->assertDatabaseMissing('users', ['id' => $createdUser->id]);
    }
}
