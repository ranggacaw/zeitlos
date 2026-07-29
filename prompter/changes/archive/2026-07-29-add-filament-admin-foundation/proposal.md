# Change: Add Filament admin foundation

## Why
Zeitlos already has football-domain data and custom Inertia admin pages, but admins need an easier CMS-style interface for day-to-day management. Filament is a good fit for the admin side because it can provide reliable CRUD, dashboards, filters, and admin-only workflows while leaving the public mobile PWA unchanged.

## What Changes
- Add Filament as the admin CMS foundation for Zeitlos.
- Configure a Zeitlos-branded, admin-only Filament panel at `/admin`.
- Add a Filament dashboard with player/match/live/recent/leaderboard overview data.
- Add basic Filament resources for `Player` and `FootballMatch` using the existing schema.
- Keep public Inertia/React PWA pages unchanged.
- Keep old custom admin implementation only as temporary compatibility until later roadmap increments replace all workflows.

## Design Reference
Feature UI map: `prompter/features/filament-cms-replacement/ui.md`

Increment 1 pages:
- `/admin`
- `/admin/players`
- `/admin/players/create`
- `/admin/players/:id/edit`
- `/admin/football-matches`
- `/admin/football-matches/create`
- `/admin/football-matches/:id/edit`

## Impact
- Affected specs: `admin-team-management`
- Affected code: `composer.json`, `composer.lock`, `app/Providers/Filament/AdminPanelProvider.php`, `app/Models/User.php`, `app/Filament/**`, `routes/web.php`, `tests/Feature/AdminTeamManagementTest.php`
- New dependency: Filament and its Livewire/admin-panel stack
