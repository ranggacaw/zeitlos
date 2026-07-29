# Implementation Plan: Filament CMS Replacement

## Feature Overview

Replace the custom Inertia admin CMS with a Filament-powered admin panel that is easier for Zeitlos admins to use while preserving the existing public mobile PWA. This roadmap keeps the migration safe by shipping Filament in increments, starting with the admin foundation and basic Player/Match management.

---

## Scope

### In Scope
- [ ] Install and configure Filament for admin-only CMS access.
- [ ] Create a Zeitlos-branded Filament panel at `/admin`.
- [ ] Add an admin dashboard with core team/match overview cards.
- [ ] Add basic Filament resources for players and matches.
- [ ] Keep public Inertia/React routes untouched.

### Out of Scope
- Match roster management -- Increment 2.
- Live scoring actions -- Increment 3.
- Leaderboard correction/polish -- Increment 4.
- Removing old custom Inertia admin code -- Increment 5, after Filament covers all workflows.

---

## Increment Roadmap

| Increment | Scope | Proposed `change-id` | Depends on | Status |
|-----------|-------|----------------------|-----------|--------|
| 1 | Install Filament, configure admin-only panel at `/admin`, add Zeitlos-themed dashboard, and add basic Player + Match resources | `add-filament-admin-foundation` | — | not created |
| 2 | Add match roster management inside Filament, including existing players, guest names, goalkeeper/player grouping, and copyable WhatsApp text | `add-filament-match-rosters` | Increment 1 | not created |
| 3 | Add Filament live scoring workflow: start live, record/delete goals with assists, finalize score, and keep stats reflected publicly | `add-filament-live-scoring` | Increment 2 | not created |
| 4 | Add leaderboard correction workflow and admin usability polish: grouped navigation, labels, filters, quick actions, dark Zeitlos theme refinements | `add-filament-leaderboard-polish` | Increment 3 | not created |
| 5 | Retire or redirect the old custom Inertia admin routes/pages/controllers and update specs/tests to make Filament the admin source of truth | `replace-inertia-admin-with-filament` | Increment 4 | not created |

### Next Increment to Run

**Next up:** `add-filament-admin-foundation` — Increment 1.

After Increment 1 is implemented and archived, run:

```bash
feature-planner filament-cms-replacement continue
```

---

## Codebase Analysis

### Tech Stack Detected

| Layer | Technology |
|-------|------------|
| Backend | Laravel 12, PHP 8.2 |
| Frontend | Inertia 2, React 18, Tailwind |
| Auth | Laravel Breeze with `admin` middleware |
| Database | Laravel migrations targeting MySQL-compatible schema |
| Admin today | Custom Inertia routes/controllers under `/admin` |
| Tests | PHPUnit feature/unit tests |

### Affected Files & Modules

| File / Module | Change Type | Description |
|---------------|-------------|-------------|
| `composer.json` / `composer.lock` | Modify | Add Filament dependency |
| `app/Providers/Filament/AdminPanelProvider.php` | New | Configure `/admin` panel, auth middleware, branding, navigation |
| `app/Models/User.php` | Modify | Add/confirm Filament panel access only for `User::isAdmin()` |
| `app/Filament/Pages/Dashboard.php` | New | Admin dashboard with player/match/live/recent/top stats |
| `app/Filament/Resources/PlayerResource.php` | New | Player CRUD with roster identity, active status, photo path, stat adjustments |
| `app/Filament/Resources/FootballMatchResource.php` | New | Match CRUD with schedule, venue, payment, announcement, status, score fields |
| `app/Filament/Resources/*/Pages/*.php` | New | Filament list/create/edit pages |
| `routes/web.php` | Modify | Keep public routes; avoid route collision with Filament `/admin` during Increment 1 |
| `tests/Feature/AdminTeamManagementTest.php` | Modify/Add | Add Filament access and resource workflow coverage |
| `prompter/specs/admin-team-management/spec.md` | Modify | Describe Filament CMS foundation/admin access behavior |

### Existing Patterns to Follow
- Admin-only behavior must remain protected by `auth`, `verified`, and the existing admin authorization semantics.
- Existing models and fillable fields should be reused; no schema changes in Increment 1.
- Player stats remain derived from `MatchEvent` plus adjustment fields.
- Public routes named `public.*` and `/dashboard` Breeze route must remain untouched.
- Tests should verify behavior through Laravel/PHPUnit and not rely on Vite.

---

## Data Model Changes

No database changes planned for Increment 1.

---

## Implementation Tasks

### Phase 1: Filament Setup
- [ ] 1.1 Install Filament with Composer and publish/register the admin panel provider -- `composer.json`, `composer.lock`, `app/Providers/Filament/AdminPanelProvider.php`
- [ ] 1.2 Configure the panel path as `/admin`, app name as `Zeitlos CMS`, login/auth handling, and dark-friendly branding -- `app/Providers/Filament/AdminPanelProvider.php`
- [ ] 1.3 Gate Filament panel access to admin users only via `User::canAccessPanel()` or equivalent -- `app/Models/User.php`

### Phase 2: Admin Dashboard
- [ ] 2.1 Create a Filament dashboard showing player count, active player count, match count, live/next match, recent result, top scorers, and top assists -- `app/Filament/Pages/Dashboard.php`
- [ ] 2.2 Reuse existing model queries from current admin dashboard behavior where practical -- `app/Http/Controllers/Admin/AdminDashboardController.php`, `app/Filament/Pages/Dashboard.php`

### Phase 3: Player Resource
- [ ] 3.1 Create `PlayerResource` with fields for name, jersey number, position, active status, photo path, joined date, goals adjustment, assists adjustment -- `app/Filament/Resources/PlayerResource.php`
- [ ] 3.2 Add searchable/sortable table columns for name, jersey number, position, active status, and adjusted stats -- `app/Filament/Resources/PlayerResource.php`
- [ ] 3.3 Add create/edit/list pages following Filament conventions -- `app/Filament/Resources/PlayerResource/Pages/*.php`

### Phase 4: Match Resource
- [ ] 4.1 Create `FootballMatchResource` with grouped form sections for schedule, venue/maps, payment, WhatsApp announcement, status, and score -- `app/Filament/Resources/FootballMatchResource.php`
- [ ] 4.2 Add list filters/tabs for scheduled, live, and finished matches -- `app/Filament/Resources/FootballMatchResource.php`
- [ ] 4.3 Add create/edit/list pages following Filament conventions -- `app/Filament/Resources/FootballMatchResource/Pages/*.php`

### Phase 5: Routing Compatibility
- [ ] 5.1 Resolve `/admin` route ownership so Filament can serve the admin panel without breaking public pages or Breeze `/dashboard` -- `routes/web.php`, `app/Providers/Filament/AdminPanelProvider.php`
- [ ] 5.2 Keep old custom admin routes only if needed under a temporary compatibility path until Increment 5 retires them -- `routes/web.php`

### Phase 6: Tests & Validation
- [ ] 6.1 Add/adjust tests proving guests cannot access Filament admin and non-admin users are denied -- `tests/Feature/AdminTeamManagementTest.php`
- [ ] 6.2 Add/adjust tests proving admins can access the Filament dashboard -- `tests/Feature/AdminTeamManagementTest.php`
- [ ] 6.3 Add resource workflow tests for creating/updating players and matches through Filament/Livewire test helpers where available -- `tests/Feature/AdminTeamManagementTest.php`
- [ ] 6.4 Run `composer test` and `prompter validate add-filament-admin-foundation --strict --no-interactive`.

---

## Dependencies & Risks

| Risk | Impact | Mitigation |
|------|--------|------------|
| Filament route collision with existing `/admin` Inertia routes | High | In Increment 1, explicitly decide route ownership and keep temporary compatibility only if needed |
| Filament adds Livewire conventions to a mostly Inertia app | Medium | Keep Filament isolated to admin panel; public PWA stays Inertia/React |
| Tests currently assert Inertia admin pages | Medium | Update only Increment 1 tests now; full old admin removal waits until Increment 5 |
| Dark theme matching may need asset/theme customization | Medium | Start with panel branding and dark mode in Increment 1; deeper polish in Increment 4 |

---

## Notes
- Filament is possible and appropriate here because Zeitlos admin workflows are mostly CRUD plus a few workflow pages/actions.
- The custom live scoring UX may still need a custom Filament page in Increment 3 rather than a plain resource, because it must be fast and mistake-resistant.
- Increment 1 should not remove old admin implementation until Filament has feature parity.
