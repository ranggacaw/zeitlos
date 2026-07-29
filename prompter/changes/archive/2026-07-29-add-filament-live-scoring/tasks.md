## 1. Filament Live Scoring Page
- [x] 1.1 Add a Filament page route for `/admin/football-matches/{record}/live-scoring` under `FootballMatchResource` -- `app/Filament/Resources/FootballMatches/FootballMatchResource.php`, `app/Filament/Resources/FootballMatches/Pages/ManageFootballMatchLiveScoring.php`
- [x] 1.2 Show match context, current status/score, available scoring players, and existing goal events on the live scoring page -- `app/Filament/Resources/FootballMatches/Pages/ManageFootballMatchLiveScoring.php`, `resources/views/filament/**`
- [x] 1.3 Implement UI per approved page contract `prompter/features/filament-cms-replacement/ui.md` for `/admin/football-matches/:id/live-scoring` (manual)

## 2. Live Status and Goal Events
- [x] 2.1 Add a Filament action to mark a scheduled match as live without changing score fields -- `app/Filament/Resources/FootballMatches/Pages/ManageFootballMatchLiveScoring.php`, `app/Models/FootballMatch.php`
- [x] 2.2 Add a Filament action/form to record a goal with scorer, optional assist player, and optional minute using existing `MatchEvent` records -- `app/Filament/Resources/FootballMatches/Pages/ManageFootballMatchLiveScoring.php`, `app/Models/MatchEvent.php`
- [x] 2.3 Use roster-linked players as scoring options when available, falling back to active players when the match roster has no linked players -- `app/Filament/Resources/FootballMatches/Pages/ManageFootballMatchLiveScoring.php`
- [x] 2.4 Add a remove action for goal events with Filament confirmation and refresh the event timeline -- `app/Filament/Resources/FootballMatches/Pages/ManageFootballMatchLiveScoring.php`

## 3. Final Score
- [x] 3.1 Add a Filament action/form to submit final Zeitlos and opponent scores, validate non-negative integers, and mark the match finished -- `app/Filament/Resources/FootballMatches/Pages/ManageFootballMatchLiveScoring.php`, `app/Models/FootballMatch.php`
- [x] 3.2 Ensure finalized scores remain visible on the Filament match edit/list workflows through existing fields -- `app/Filament/Resources/FootballMatches/Schemas/FootballMatchForm.php`, `app/Filament/Resources/FootballMatches/Tables/FootballMatchesTable.php`

## 4. Navigation
- [x] 4.1 Add a `Live scoring` row action to the Filament match table -- `app/Filament/Resources/FootballMatches/Tables/FootballMatchesTable.php`
- [x] 4.2 Add a `Live scoring` header action to the Filament match edit page -- `app/Filament/Resources/FootballMatches/Pages/EditFootballMatch.php`

## 5. Tests & Validation
- [x] 5.1 Add Filament/Livewire coverage for marking a match live -- `tests/Feature/AdminTeamManagementTest.php`
- [x] 5.2 Add Filament/Livewire coverage for recording and deleting a goal event -- `tests/Feature/AdminTeamManagementTest.php`
- [x] 5.3 Add Filament/Livewire coverage for final score submission and finished status -- `tests/Feature/AdminTeamManagementTest.php`
- [x] 5.4 Add coverage that recorded Filament goal events still update public leaderboard/player stats -- `tests/Feature/AdminTeamManagementTest.php`, `tests/Feature/PublicTeamPagesTest.php`
- [x] 5.5 Run `composer test` and `prompter validate add-filament-live-scoring --strict --no-interactive`.

## Post-Implementation
- [x] Update `AGENTS.md` if the completed change alters lasting project conventions.
