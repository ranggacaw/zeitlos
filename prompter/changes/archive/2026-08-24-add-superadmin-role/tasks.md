## 1. User Model & Authorization Foundation
- [x] 1.1 Add `ROLE_SUPERADMIN` constant and `isSuperAdmin()` helper to `User` model (`app/Models/User.php`).
- [x] 1.2 Update `User::isAdmin()` to return true for `superadmin` role.
- [x] 1.3 Add `superadmin()` state to `UserFactory` (`database/factories/UserFactory.php`) and seed default superadmin in `DatabaseSeeder.php`.

## 2. Match Management Permissions
- [x] 2.1 Update `FootballMatchesTable` action visibility for `manageRoster` and `liveScoring` on finished matches for SuperAdmin (`app/Filament/Resources/FootballMatches/Tables/FootballMatchesTable.php`).
- [x] 2.2 Update `ManageFootballMatchRosters` to allow roster add/delete/import actions when user is SuperAdmin on finished matches (`app/Filament/Resources/FootballMatches/Pages/ManageFootballMatchRosters.php`).
- [x] 2.3 Update `ManageFootballMatchLiveScoring` to allow goal add/delete and score finalization when user is SuperAdmin on finished matches (`app/Filament/Resources/FootballMatches/Pages/ManageFootballMatchLiveScoring.php`).
- [x] 2.4 Update subheading in `ManageFootballMatchRosters` and `ManageFootballMatchLiveScoring` to reflect SuperAdmin edit mode on finished matches.

## 3. Filament User Management Resource
- [x] 3.1 Create `UserResource` under `app/Filament/Resources/Users/UserResource.php` restricted to `isSuperAdmin()`.
- [x] 3.2 Create `UserForm` schema (`app/Filament/Resources/Users/Schemas/UserForm.php`) supporting name, email, role selection (`user`, `admin`, `superadmin`), and password.
- [x] 3.3 Create `UsersTable` (`app/Filament/Resources/Users/Tables/UsersTable.php`) with search, role badges, and edit/delete actions with self-deletion guards.
- [x] 3.4 Create `ListUsers`, `CreateUser`, and `EditUser` pages (`app/Filament/Resources/Users/Pages/`).

## 4. Testing & Verification
- [x] 4.1 Write feature tests for SuperAdmin match overrides (roster edits and live-scoring edits on finished matches).
- [x] 4.2 Write feature tests for User Management Filament resource authorization (Admin denied, SuperAdmin allowed, self-delete blocked).
- [x] 4.3 Run full test suite and verify all tests pass.

## Post-Implementation
- [x] Update `AGENTS.md` and project documentation to mention SuperAdmin role and Filament User Management.
