## 1. Filament Leaderboard Corrections
- [x] 1.1 Add a Filament leaderboard page at `/admin/leaderboard` that lists players with event-derived goals/assists, adjustment fields, and adjusted totals -- `app/Filament/Pages/Leaderboard.php`
- [x] 1.2 Persist goal and assist adjustments from the Filament leaderboard workflow using existing `Player` fields -- `app/Filament/Pages/Leaderboard.php`, `app/Models/Player.php`
- [x] 1.3 Keep the existing legacy Inertia leaderboard routes intact for now -- `routes/web.php`, `app/Http/Controllers/Admin/LeaderboardController.php`

## 2. Admin Navigation and Labels
- [x] 2.1 Add clear Filament navigation labels/groups/sort order for dashboard, players, matches, rosters/live scoring entry points, and leaderboard -- `app/Filament/Pages/Dashboard.php`, `app/Filament/Pages/Leaderboard.php`, `app/Filament/Resources/Players/PlayerResource.php`, `app/Filament/Resources/FootballMatches/FootballMatchResource.php`
- [x] 2.2 Add or refine quick actions from dashboard/overview widgets to Players, Matches, and Leaderboard -- `app/Filament/Widgets/AdminOverview.php`, `resources/views/filament/widgets/admin-overview.blade.php`
- [x] 2.3 Ensure match list row actions remain easy to find for roster management and live scoring -- `app/Filament/Resources/FootballMatches/Tables/FootballMatchesTable.php`

## 3. Table Filters and Dark Polish
- [x] 3.1 Add practical filters/search/toggle defaults to player and match Filament tables where missing -- `app/Filament/Resources/Players/Tables/PlayersTable.php`, `app/Filament/Resources/FootballMatches/Tables/FootballMatchesTable.php`
- [x] 3.2 Refine dark-friendly styling for custom Filament widgets/pages without introducing a custom admin shell -- `resources/views/filament/**/*.blade.php`, `app/Providers/Filament/AdminPanelProvider.php`

## 4. Tests and Validation
- [x] 4.1 Add feature coverage for opening and updating leaderboard corrections through Filament -- `tests/Feature/AdminTeamManagementTest.php`
- [x] 4.2 Add assertions that public leaderboard totals reflect Filament correction changes -- `tests/Feature/AdminTeamManagementTest.php`
- [x] 4.3 Add coverage for Filament navigation/labels or page reachability where practical -- `tests/Feature/AdminTeamManagementTest.php`
- [x] 4.4 Run `composer test` and `prompter validate add-filament-leaderboard-polish --strict --no-interactive`.

## Post-Implementation
- [x] Update `AGENTS.md` in the project root if the admin CMS conventions change.
