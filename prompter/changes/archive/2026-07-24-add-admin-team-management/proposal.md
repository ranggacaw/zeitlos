# Change: Add admin team management

## Why
Zeitlos admins need an authenticated place to maintain the football domain data that currently powers public pages from seed data only. Without admin CRUD, players, matches, rosters, and WhatsApp lineup text cannot be managed from the app.

## What Changes
- Add admin-only routes and Inertia pages for managing players, matches, and match rosters.
- Add create, update, list, and delete workflows for players and matches using the existing `Player`, `FootballMatch`, and `MatchRoster` models.
- Add roster assignment management for existing players and guest/substitute names.
- Generate copyable WhatsApp roster text from match details and roster entries.
- Protect admin management behind authenticated admin users while preserving `/dashboard` for Breeze.

## Impact
- Affected specs: `admin-team-management`
- Affected code: `routes/web.php`, `app/Http/Controllers/Admin/*`, `app/Http/Middleware/*`, `bootstrap/app.php`, `resources/js/Pages/Admin/*`, `tests/Feature/AdminTeamManagementTest.php`
