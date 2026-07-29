## 1. Filament Setup
- [x] 1.1 Install Filament with Composer and register/publish the admin panel provider -- `composer.json`, `composer.lock`, `app/Providers/Filament/AdminPanelProvider.php`
- [x] 1.2 Configure the panel path as `/admin`, app name as `Zeitlos CMS`, login/auth handling, and dark-friendly branding -- `app/Providers/Filament/AdminPanelProvider.php`
- [x] 1.3 Gate Filament panel access to admin users only via `User::canAccessPanel()` or equivalent -- `app/Models/User.php`

## 2. Admin Dashboard
- [x] 2.1 Create a Filament dashboard showing player count, active player count, match count, live/next match, recent result, top scorers, and top assists -- `app/Filament/Pages/Dashboard.php`
- [x] 2.2 Reuse existing model queries from current admin dashboard behavior where practical -- `app/Http/Controllers/Admin/AdminDashboardController.php`, `app/Filament/Pages/Dashboard.php`

## 3. Player Resource
- [x] 3.1 Create `PlayerResource` with fields for name, jersey number, position, active status, photo path, joined date, goals adjustment, assists adjustment -- `app/Filament/Resources/PlayerResource.php`
- [x] 3.2 Add searchable/sortable table columns for name, jersey number, position, active status, and adjusted stats -- `app/Filament/Resources/PlayerResource.php`
- [x] 3.3 Add create/edit/list pages following Filament conventions -- `app/Filament/Resources/PlayerResource/Pages/*.php`

## 4. Match Resource
- [x] 4.1 Create `FootballMatchResource` with grouped form sections for schedule, venue/maps, payment, WhatsApp announcement, status, and score -- `app/Filament/Resources/FootballMatchResource.php`
- [x] 4.2 Add list filters/tabs for scheduled, live, and finished matches -- `app/Filament/Resources/FootballMatchResource.php`
- [x] 4.3 Add create/edit/list pages following Filament conventions -- `app/Filament/Resources/FootballMatchResource/Pages/*.php`

## 5. Routing Compatibility
- [x] 5.1 Resolve `/admin` route ownership so Filament can serve the admin panel without breaking public pages or Breeze `/dashboard` -- `routes/web.php`, `app/Providers/Filament/AdminPanelProvider.php`
- [x] 5.2 Keep old custom admin routes only if needed under a temporary compatibility path until Increment 5 retires them -- `routes/web.php`

## 6. Tests / Validation
- [x] 6.1 Add/adjust tests proving guests cannot access Filament admin and non-admin users are denied -- `tests/Feature/AdminTeamManagementTest.php`
- [x] 6.2 Add/adjust tests proving admins can access the Filament dashboard -- `tests/Feature/AdminTeamManagementTest.php`
- [x] 6.3 Add resource workflow tests for creating/updating players and matches through Filament/Livewire test helpers where available -- `tests/Feature/AdminTeamManagementTest.php`
- [x] 6.4 Verify the Filament panel uses recognizable Zeitlos admin branding and a dark-friendly theme (manual)
- [x] 6.5 Run `composer test` and `prompter validate add-filament-admin-foundation --strict --no-interactive`

## Post-Implementation
- [x] Update `AGENTS.md` if this increment changes durable project conventions for admin routes or Filament usage
