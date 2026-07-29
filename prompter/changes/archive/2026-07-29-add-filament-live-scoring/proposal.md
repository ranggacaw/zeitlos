# Change: Add Filament live scoring

## Why
Admins can now manage matches and match rosters in Filament, but live scoring still depends on the legacy Inertia admin workflow. Moving live scoring into Filament continues the CMS replacement while preserving the existing `MatchEvent`, score, and public stat behavior.

## What Changes
- Add a Filament live scoring page for each `FootballMatch`.
- Allow admins to mark a match live from the Filament workflow.
- Allow admins to record goal events with scorer, optional assist, and optional minute.
- Allow admins to remove goal events with Filament confirmation.
- Allow admins to finalize Zeitlos/opponent scores and mark the match finished.
- Add navigation from the Filament match list and match edit page to live scoring.
- Keep the legacy Inertia scoring routes in place until the final replacement increment.

## Design Reference
Feature UI map: `prompter/features/filament-cms-replacement/ui.md`

Increment 3 pages:
- `/admin/football-matches/:id/live-scoring`

## Impact
- Affected specs: `admin-team-management`
- Affected code: `app/Filament/Resources/FootballMatches/**`, `app/Models/FootballMatch.php`, `app/Models/MatchEvent.php`, `tests/Feature/AdminTeamManagementTest.php`
