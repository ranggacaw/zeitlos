## 1. Legacy Route Retirement
- [x] 1.1 Remove legacy Inertia admin controller imports and named CRUD/mutation routes from `routes/web.php`.
- [x] 1.2 Add a lightweight legacy `GET /admin-legacy/{path?}` redirect to `/admin` in `routes/web.php` for bookmarked legacy admin URLs.
- [x] 1.3 Ensure `/dashboard` remains the Breeze authenticated dashboard and `/admin` remains the Filament CMS entry point -- `routes/web.php`, `app/Providers/Filament/AdminPanelProvider.php`.

## 2. Legacy Code Removal
- [x] 2.1 Delete legacy Inertia admin controllers from `app/Http/Controllers/Admin/**` once their routes are removed.
- [x] 2.2 Delete legacy Inertia admin pages from `resources/js/Pages/Admin/**` once Filament owns those workflows.
- [x] 2.3 Search for stale `admin.*` legacy route references and replace/remove them without touching public routes -- `resources/js/**`, `app/**`, `tests/**`.

## 3. Tests
- [x] 3.1 Replace legacy admin player/match/leaderboard/roster assertions with Filament workflow assertions where coverage is still needed -- `tests/Feature/AdminTeamManagementTest.php`.
- [x] 3.2 Replace legacy live scoring route assertions with Filament live scoring assertions -- `tests/Feature/AdminLiveMatchScoringTest.php`.
- [x] 3.3 Add coverage that legacy `GET /admin-legacy` entry URLs redirect to `/admin` and legacy named admin routes are no longer registered -- `tests/Feature/AdminTeamManagementTest.php`.
- [x] 3.4 Run `composer test` and `prompter validate replace-inertia-admin-with-filament --strict --no-interactive`.

## Post-Implementation
- [x] Update `AGENTS.md` in the project root to remove temporary legacy Inertia admin guidance and state that Filament is the admin CMS source of truth.
