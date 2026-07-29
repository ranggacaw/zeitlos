# Change: Replace Inertia admin with Filament

## Why
Filament now covers the admin dashboard, players, matches, rosters, live scoring, and leaderboard correction workflows. The temporary Inertia admin surface should be retired so `/admin` is the single CMS source of truth and the old `/admin-legacy` code no longer needs maintenance.

## What Changes
- Remove the legacy Inertia admin route group, named routes, controllers, and React admin pages for players, matches, rosters, scoring, leaderboard, and the old dashboard.
- Redirect legacy `GET /admin-legacy...` entry URLs to `/admin` so bookmarked admin pages land in the Filament CMS instead of breaking abruptly.
- Update feature tests to assert Filament workflows remain reachable and legacy admin route names/controllers/pages are no longer the admin contract.
- Update project guidance/specs so future admin CMS work targets Filament resources/pages under `/admin`.

## Impact
- Affected specs: `admin-team-management`
- Affected code: `routes/web.php`, `app/Http/Controllers/Admin/**`, `resources/js/Pages/Admin/**`, `tests/Feature/AdminTeamManagementTest.php`, `tests/Feature/AdminLiveMatchScoringTest.php`, `AGENTS.md`
