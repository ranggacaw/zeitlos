# Change: Add Public Team Pages

## Why
Zeitlos needs a public read-only team app so players and visitors can view roster, player details, match schedule, and leaderboard data without admin access. The football domain foundation is now archived, so the public Inertia pages can be built against durable seeded/domain data.

## What Changes
- Add public Laravel routes/controllers that query active players, scheduled/finished matches, roster entries, and player goal/assist totals.
- Replace the starter welcome experience with a public Zeitlos dashboard app shell.
- Add public React pages for dashboard, player details, schedule, roster, and leaderboard.
- Add feature tests that verify public pages render and expose expected domain data.

## Impact
- Affected specs: public-team-pages
- Affected code: `routes/web.php`, `app/Http/Controllers`, `resources/js/Pages`, `resources/js/Layouts`, `tests/Feature`
- Depends on archived change: `add-football-domain-foundation`
