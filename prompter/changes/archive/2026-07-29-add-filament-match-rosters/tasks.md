## 1. Filament Roster Page
- [x] 1.1 Add a Filament page route for `/admin/football-matches/{record}/rosters` under `FootballMatchResource` -- `app/Filament/Resources/FootballMatches/FootballMatchResource.php`, `app/Filament/Resources/FootballMatches/Pages/ManageFootballMatchRosters.php`
- [x] 1.2 Show match context, grouped roster entries, and the generated WhatsApp text on the roster page -- `app/Filament/Resources/FootballMatches/Pages/ManageFootballMatchRosters.php`, `resources/views/filament/**`

## 2. Roster Mutations
- [x] 2.1 Add a Filament action/form to create roster entries for either an existing player or a guest name, with role options for goalkeeper/player -- `app/Filament/Resources/FootballMatches/Pages/ManageFootballMatchRosters.php`
- [x] 2.2 Enforce exactly one roster identity (`player_id` or `guest_name`) and persist entries using the existing `MatchRoster` model -- `app/Filament/Resources/FootballMatches/Pages/ManageFootballMatchRosters.php`, `app/Models/MatchRoster.php`
- [x] 2.3 Add a remove action for roster entries with Filament confirmation and refresh the grouped roster view -- `app/Filament/Resources/FootballMatches/Pages/ManageFootballMatchRosters.php`

## 3. Navigation
- [x] 3.1 Add a `Manage roster` row action to the Filament match table -- `app/Filament/Resources/FootballMatches/Tables/FootballMatchesTable.php`
- [x] 3.2 Add a `Manage roster` header action to the Filament match edit page -- `app/Filament/Resources/FootballMatches/Pages/EditFootballMatch.php`
- [ ] 3.3 Implement UI per approved preview/page contract `prompter/features/filament-cms-replacement/ui.md` for `/admin/football-matches/:id/rosters` (manual)

## 4. Tests & Validation
- [x] 4.1 Add Filament/Livewire coverage for adding an existing player roster entry -- `tests/Feature/AdminTeamManagementTest.php`
- [x] 4.2 Add Filament/Livewire coverage for adding a guest roster entry and rejecting invalid identity combinations -- `tests/Feature/AdminTeamManagementTest.php`
- [x] 4.3 Add coverage that the Filament roster page exposes WhatsApp roster text generated from the existing service -- `tests/Feature/AdminTeamManagementTest.php`
- [x] 4.4 Run `composer test` and `prompter validate add-filament-match-rosters --strict --no-interactive`.

## Post-Implementation
- [x] Update `AGENTS.md` if the completed change alters lasting project conventions.
