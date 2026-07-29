# Implementation Plan: Filament CMS Replacement - Increment 4

## Feature Overview

Continue replacing the custom Inertia admin CMS by moving leaderboard corrections into Filament and polishing the Filament admin experience before legacy route retirement.

---

## Scope

### In Scope
- [ ] Add a Filament leaderboard correction page at `/admin/leaderboard`.
- [ ] Let admins review event-derived goals/assists and update player stat adjustment fields in Filament.
- [ ] Improve Filament navigation labels, grouping, table filters, and workflow quick actions.
- [ ] Refine dark-friendly custom Filament surfaces where needed.
- [ ] Keep legacy Inertia admin routes/pages until Increment 5.

### Out of Scope
- Removing or redirecting legacy Inertia admin routes -- Increment 5.
- New database schema -- existing `Player` adjustment fields already support corrections.
- Redesigning the public PWA -- this increment is admin-only.

---

## Increment Roadmap

| Increment | Scope | Proposed `change-id` | Depends on | Status |
|-----------|-------|----------------------|-----------|--------|
| 1 | Install Filament, configure admin-only panel at `/admin`, add Zeitlos-themed dashboard, and add basic Player + Match resources | `add-filament-admin-foundation` | — | archived |
| 2 | Add match roster management inside Filament, including existing players, guest names, goalkeeper/player grouping, and copyable WhatsApp text | `add-filament-match-rosters` | Increment 1 | archived |
| 3 | Add Filament live scoring workflow: start live, record/delete goals with assists, finalize score, and keep stats reflected publicly | `add-filament-live-scoring` | Increment 2 | archived |
| 4 | Add leaderboard correction workflow and admin usability polish: grouped navigation, labels, filters, quick actions, dark Zeitlos theme refinements | `add-filament-leaderboard-polish` | Increment 3 | scaffolded |
| 5 | Retire or redirect the old custom Inertia admin routes/pages/controllers and update specs/tests to make Filament the admin source of truth | `replace-inertia-admin-with-filament` | Increment 4 | not created |

### Next Increment to Run

**Next up:** `replace-inertia-admin-with-filament` — Increment 5, after Increment 4 is implemented and archived.

---

## Codebase Analysis

### Tech Stack Detected

| Layer | Technology |
|-------|------------|
| Backend | Laravel 12, PHP 8.2 |
| Frontend | Inertia 2, React 18, Tailwind for public/legacy admin; Filament/Livewire for CMS |
| Auth | Laravel Breeze plus Filament admin panel access via `User::canAccessPanel()` |
| Database | Laravel migrations targeting MySQL-compatible schema |
| Tests | PHPUnit feature tests with Livewire helpers |

### Affected Files & Modules

| File / Module | Change Type | Description |
|---------------|-------------|-------------|
| `app/Filament/Pages/Leaderboard.php` | New | Filament page for leaderboard corrections |
| `app/Filament/Pages/Dashboard.php` | Modify | Navigation labels/sort and dashboard integration as needed |
| `app/Filament/Widgets/AdminOverview.php` | Modify | Quick actions or data needed for admin workflow links |
| `resources/views/filament/widgets/admin-overview.blade.php` | Modify | Dark-friendly quick links and visual polish |
| `app/Filament/Resources/Players/PlayerResource.php` | Modify | Navigation labels/groups/sort |
| `app/Filament/Resources/Players/Tables/PlayersTable.php` | Modify | Filters/search/toggle defaults for player management |
| `app/Filament/Resources/FootballMatches/FootballMatchResource.php` | Modify | Navigation labels/groups/sort |
| `app/Filament/Resources/FootballMatches/Tables/FootballMatchesTable.php` | Modify | Match filters and row action usability |
| `tests/Feature/AdminTeamManagementTest.php` | Modify | Filament leaderboard and polish/page reachability coverage |
| `prompter/specs/admin-team-management/spec.md` | Modify | Add Filament leaderboard correction and usability requirements |

### Existing Patterns to Follow
- Filament resources live under `app/Filament/Resources/<Domain>/` with form/table classes split into `Schemas` and `Tables`.
- Custom Filament pages already exist for match roster and live scoring workflows under `app/Filament/Resources/FootballMatches/Pages`.
- Player totals are derived from `MatchEvent` plus `Player::goals_adjustment` and `Player::assists_adjustment`.
- Legacy Inertia admin routes are still temporary and should not be removed until Increment 5.
- Feature tests use PHPUnit with `Livewire::actingAs(...)->test(...)` for Filament workflows.

---

## Data Model Changes

No database changes planned for Increment 4.

---

## Implementation Tasks

### Phase 1: Filament Leaderboard Corrections
- [ ] 1.1 Add a Filament leaderboard page at `/admin/leaderboard` that lists players with event-derived goals/assists, adjustment fields, and adjusted totals -- `app/Filament/Pages/Leaderboard.php`
- [ ] 1.2 Persist goal and assist adjustments from the Filament leaderboard workflow using existing `Player` fields -- `app/Filament/Pages/Leaderboard.php`, `app/Models/Player.php`
- [ ] 1.3 Keep the existing legacy Inertia leaderboard routes intact for now -- `routes/web.php`, `app/Http/Controllers/Admin/LeaderboardController.php`

### Phase 2: Admin Navigation and Labels
- [ ] 2.1 Add clear Filament navigation labels/groups/sort order for dashboard, players, matches, rosters/live scoring entry points, and leaderboard -- `app/Filament/Pages/Dashboard.php`, `app/Filament/Pages/Leaderboard.php`, `app/Filament/Resources/Players/PlayerResource.php`, `app/Filament/Resources/FootballMatches/FootballMatchResource.php`
- [ ] 2.2 Add or refine quick actions from dashboard/overview widgets to Players, Matches, and Leaderboard -- `app/Filament/Widgets/AdminOverview.php`, `resources/views/filament/widgets/admin-overview.blade.php`
- [ ] 2.3 Ensure match list row actions remain easy to find for roster management and live scoring -- `app/Filament/Resources/FootballMatches/Tables/FootballMatchesTable.php`

### Phase 3: Table Filters and Dark Polish
- [ ] 3.1 Add practical filters/search/toggle defaults to player and match Filament tables where missing -- `app/Filament/Resources/Players/Tables/PlayersTable.php`, `app/Filament/Resources/FootballMatches/Tables/FootballMatchesTable.php`
- [ ] 3.2 Refine dark-friendly styling for custom Filament widgets/pages without introducing a custom admin shell -- `resources/views/filament/**/*.blade.php`, `app/Providers/Filament/AdminPanelProvider.php`

### Phase 4: Tests and Validation
- [ ] 4.1 Add feature coverage for opening and updating leaderboard corrections through Filament -- `tests/Feature/AdminTeamManagementTest.php`
- [ ] 4.2 Add assertions that public leaderboard totals reflect Filament correction changes -- `tests/Feature/AdminTeamManagementTest.php`
- [ ] 4.3 Add coverage for Filament navigation/labels or page reachability where practical -- `tests/Feature/AdminTeamManagementTest.php`
- [ ] 4.4 Run `composer test` and `prompter validate add-filament-leaderboard-polish --strict --no-interactive`.

---

## Dependencies & Risks

| Risk | Impact | Mitigation |
|------|--------|------------|
| Filament page/table APIs differ from legacy Inertia patterns | Medium | Follow existing Filament roster/live scoring page patterns and Livewire tests |
| Leaderboard totals can drift if event-derived values are duplicated | Medium | Store only adjustment fields; continue deriving event totals from `MatchEvent` relationships |
| Over-polishing could delay final legacy retirement | Medium | Limit polish to navigation, labels, filters, quick actions, and readable dark surfaces |

---

## Notes
- Increment 4 makes Filament feature-complete for admin workflows but intentionally leaves legacy routes in place.
- Increment 5 will retire or redirect the old Inertia admin implementation once this proposal is implemented and archived.
