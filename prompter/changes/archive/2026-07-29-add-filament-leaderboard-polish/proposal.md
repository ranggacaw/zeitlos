# Change: Add Filament leaderboard polish

## Why
Filament now covers foundational player, match, roster, and live scoring workflows, but leaderboard corrections still rely on the legacy Inertia admin page. Moving corrections into Filament and polishing admin navigation makes Filament the practical day-to-day CMS before the final legacy-route retirement.

## What Changes
- Add a Filament leaderboard correction page at `/admin/leaderboard` for reviewing event-derived goals/assists and updating player adjustment fields.
- Add Filament navigation from the sidebar and dashboard/overview surfaces to leaderboard correction workflows.
- Polish Filament resource labels, navigation groups, table filters, and quick actions for Player, Match, Roster, Live Scoring, and Leaderboard workflows.
- Refine existing dark-friendly admin styling where needed without replacing Filament's shell.
- Keep legacy Inertia admin leaderboard routes in place until the final replacement increment.

## Design Reference
Feature UI map: `prompter/features/filament-cms-replacement/ui.md`

Increment 4 pages:
- `/admin/leaderboard`

## Impact
- Affected specs: `admin-team-management`
- Affected code: `app/Filament/Pages/**`, `app/Filament/Resources/**`, `app/Providers/Filament/AdminPanelProvider.php`, `resources/views/filament/**`, `tests/Feature/AdminTeamManagementTest.php`
