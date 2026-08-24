# Change: Add SuperAdmin Role with Privileged Actions and User Management

## Why
Currently, all management capabilities are bound to the `admin` role, and certain workflows (such as modifying rosters, recording/deleting goals, or managing match details) are strictly blocked once a match is marked as `finished`. A higher-level `superadmin` role is needed to perform administrative overrides (including modifying finished matches, deleting matches, and managing user accounts/roles in Filament) while keeping standard admins within normal operational boundaries.

## What Changes
- Introduce `superadmin` role (`User::ROLE_SUPERADMIN = 'superadmin'`) and `User::isSuperAdmin()` helper.
- Update `User::isAdmin()` and panel access checks so SuperAdmins inherit full access to admin middleware, panel authentication, and existing management tools.
- Add SuperAdmin override capabilities for finished matches:
  - Manage roster entries (add, delete, WhatsApp import) on finished matches.
  - Manage live scoring (record Zeitlos goals, opponent goals, delete goals, re-finalize scores) on finished matches.
  - Delete matches (including finished matches).
- Add SuperAdmin-only User Management resource in Filament (`App\Filament\Resources\Users\UserResource`) to list, create, update (roles: user, admin, superadmin), and delete users with self-deletion guards.
- Add `UserFactory::superadmin()` state and seed default SuperAdmin credentials in `DatabaseSeeder`.

## Impact
- Affected specs: `admin-team-management`
- Affected code:
  - `app/Models/User.php`
  - `database/factories/UserFactory.php`
  - `database/seeders/DatabaseSeeder.php`
  - `app/Filament/Resources/FootballMatches/Tables/FootballMatchesTable.php`
  - `app/Filament/Resources/FootballMatches/Pages/ManageFootballMatchRosters.php`
  - `app/Filament/Resources/FootballMatches/Pages/ManageFootballMatchLiveScoring.php`
  - `app/Filament/Resources/Users/UserResource.php` (and associated schemas/tables/pages)
  - `tests/Feature/...`
