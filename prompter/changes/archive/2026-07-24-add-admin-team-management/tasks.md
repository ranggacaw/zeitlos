## 1. Authorization and Routing
- [x] 1.1 Add admin-only middleware in `app/Http/Middleware/EnsureAdmin.php` that allows authenticated users where `User::isAdmin()` is true and denies others.
- [x] 1.2 Register the middleware alias in `bootstrap/app.php` following Laravel 12 middleware configuration.
- [x] 1.3 Add `/admin` route group in `routes/web.php` with `auth`, `verified`, and admin middleware, preserving `/dashboard` for Breeze.

## 2. Backend Admin Workflows
- [x] 2.1 Create `app/Http/Controllers/Admin/AdminDashboardController.php` to render a lightweight admin landing page with player and match counts.
- [x] 2.2 Create `app/Http/Controllers/Admin/PlayerController.php` for listing, creating, updating, and deleting players with validation for name, jersey number, position, active state, photo path, join date, and stat adjustments.
- [x] 2.3 Create `app/Http/Controllers/Admin/MatchController.php` for listing, creating, updating, and deleting matches with validation for schedule, venue, payment, announcement, status, and score fields.
- [x] 2.4 Create `app/Http/Controllers/Admin/MatchRosterController.php` to manage roster entries for a match, including existing players and guest/substitute names.
- [x] 2.5 Add deterministic WhatsApp roster text generation for a match from `FootballMatch` and grouped `MatchRoster` data.

## 3. Inertia Admin UI
- [x] 3.1 Create `resources/js/Pages/Admin/Dashboard.jsx` with links to players and matches.
- [x] 3.2 Create player admin pages under `resources/js/Pages/Admin/Players/` for index, create, and edit workflows using existing Breeze/Inertia form patterns.
- [x] 3.3 Create match admin pages under `resources/js/Pages/Admin/Matches/` for index, create, edit, and roster management workflows.
- [x] 3.4 Add copyable WhatsApp roster text display on the match roster management page.

## 4. Tests
- [x] 4.1 Add `tests/Feature/AdminTeamManagementTest.php` to verify guests cannot access admin routes and non-admin authenticated users are denied.
- [x] 4.2 Test an admin can create and update a player.
- [x] 4.3 Test an admin can create and update a match.
- [x] 4.4 Test an admin can add roster entries for an existing player and a guest name.
- [x] 4.5 Test WhatsApp roster text includes match details and grouped roster names.
- [x] 4.6 Run `composer test` and ensure the full suite passes.

## Post-Implementation
- [x] Update `AGENTS.md` in the project root if admin route/controller conventions need to be documented for future increments.
