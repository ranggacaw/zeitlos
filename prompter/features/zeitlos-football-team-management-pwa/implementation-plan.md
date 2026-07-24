# Implementation Plan: Zeitlos Football Team Management PWA

## Feature Overview

Build Zeitlos as a mobile-first Laravel + Inertia React PWA for football team schedules, players, rosters, live scoring, and stats. This plan details Increment 1 only: the domain/data foundation the rest of the app will use.

---

## Scope

### In Scope
- [ ] Add admin role support to Breeze users.
- [ ] Add core football domain schema: players, matches, match rosters, match events.
- [ ] Add Eloquent models, factories, relationships, and simple stat helpers/scopes.
- [ ] Seed one admin account, sample players, one upcoming match, one past match, rosters, and events.
- [ ] Add baseline tests for schema/model relationships, role flags, seeding, and leaderboard aggregation assumptions.

### Out of Scope
- Public React pages - Increment 2.
- Admin CRUD forms and WhatsApp copy button - Increment 3.
- Live scoring UI/actions and editable corrections - Increment 4.
- PWA manifest/service worker/install prompt - Increment 5.
- Full mobile visual styling pass - Increment 6.

---

## Increment Roadmap

| Increment | Scope | Proposed `change-id` | Depends on | Status |
|-----------|-------|----------------------|-----------|--------|
| 1 | Core domain schema, models, admin role, seed data, and baseline domain tests | `add-football-domain-foundation` | - | scaffolded |
| 2 | Public read-only app shell, dashboard, player details, schedule, roster, and leaderboard using seeded/domain data | `add-public-team-pages` | Increment 1 | not created |
| 3 | Admin CRUD for players, matches, and match rosters, including WhatsApp roster text generation | `add-admin-team-management` | Increment 2 | not created |
| 4 | Admin live match console, match events, score finalization, and leaderboard/stat corrections | `add-live-match-scoring` | Increment 3 | not created |
| 5 | PWA manifest/service worker/offline fallback/install prompt/iOS safe-area metadata | `add-zeitlos-pwa` | Increment 4 | not created |
| 6 | Mobile-first visual styling pass with bottom tabs, dark sporty theme, touch targets, and responsive polish | `update-mobile-app-design` | Increment 5 | not created |

### Next Increment to Run

**Next up:** `add-football-domain-foundation` - Increment 1.

After Increment 1 is implemented and archived, run:

```bash
feature-planner zeitlos-football-team-management-pwa continue
```

---

## Codebase Analysis

### Tech Stack Detected

| Layer | Technology |
|-------|------------|
| Backend | Laravel 12, PHP 8.2+, Breeze auth |
| Frontend | Inertia React 18, Vite 7 |
| Styling | Tailwind CSS 3 |
| Database | SQLite scaffold currently; schema remains compatible with MySQL target |
| Tests | PHPUnit feature/unit tests |

### Affected Files & Modules

| File / Module | Change Type | Description |
|---------------|-------------|-------------|
| `database/migrations/*_add_role_to_users_table.php` | New | Add `role` string/enum-like column defaulting to `guest` or `member`, with admin support. |
| `database/migrations/*_create_players_table.php` | New | Store player name, photo path/url, jersey number, position, active status, optional join date, and manual stat corrections if needed. |
| `database/migrations/*_create_matches_table.php` | New | Store opponent, match date/time, venue/maps/payment/roster announcement fields, status, and scores. |
| `database/migrations/*_create_match_rosters_table.php` | New | Store match player/guest names and roster role (`goalkeeper`/`player`). |
| `database/migrations/*_create_match_events_table.php` | New | Store match events for goals/assists with player, optional assister/minute where appropriate. |
| `app/Models/User.php` | Modify | Include role in fillable/casts and add `isAdmin()` helper. |
| `app/Models/Player.php` | New | Player relationships and goals/assists aggregate helpers. |
| `app/Models/FootballMatch.php` | New | Match relationships, status constants/scopes, score helpers. |
| `app/Models/MatchRoster.php` | New | Roster entry relationships and role constants. |
| `app/Models/MatchEvent.php` | New | Event relationships and event type constants. |
| `database/factories/*.php` | New/Modify | Add factories for users, players, matches, rosters, and events. |
| `database/seeders/DatabaseSeeder.php` | Modify | Seed admin user, demo players, upcoming/past matches, roster entries, and sample events. |
| `tests/Feature/ZeitlosDomainTest.php` | New | Verify admin role, seeded demo data, relationships, statuses, and stat aggregation. |
| `tests/Unit/PlayerStatsTest.php` | New | Verify player goals/assists derive from event records and corrections rules if implemented. |

### Existing Patterns to Follow
- Use standard Laravel migrations/models/factories under existing app/database paths.
- Keep model logic simple on Eloquent models; no repository layer exists.
- Use PHPUnit under `tests/Feature` and `tests/Unit` like the scaffold.
- Preserve Breeze auth routes/pages; remove public registration later only when admin auth flow is addressed.

---

## Data Model Changes

### New Tables / Collections
- **players**: `id`, `name`, `photo`, `jersey_number`, `position`, `is_active`, `joined_at`, optional `goals_adjustment`, optional `assists_adjustment`, timestamps.
- **matches**: `id`, `opponent_name`, `match_date`, `start_time`, `end_time`, `venue_name`, `venue_maps_url`, `ticket_price`, `dress_code`, `facilities`, `notes`, `dp_amount`, `dana_number`, `dana_name`, `bca_number`, `bca_name`, `payment_proof_number`, `status`, `zeitlos_score`, `opponent_score`, timestamps.
- **match_rosters**: `id`, `match_id`, nullable `player_id`, nullable `guest_name`, `role`, timestamps.
- **match_events**: `id`, `match_id`, `player_id`, nullable `assist_player_id`, `event_type`, nullable `minute`, timestamps.

### Modified Tables / Collections
- **users**: Add `role` with admin support so authenticated admin-only routes can be protected in later increments.

### New Relationships
- `Player` has many roster entries and goal/assist events.
- `FootballMatch` has many roster entries and events.
- `MatchRoster` belongs to a match and optionally a player.
- `MatchEvent` belongs to a match, scorer player, and optional assister player.

---

## Implementation Tasks

### Phase 1: Database
- [ ] 1.1 Add `role` column migration for `users` - `database/migrations/*_add_role_to_users_table.php`.
- [ ] 1.2 Create `players` migration with identity, jersey, position, active state, photo, join date, and optional stat correction columns - `database/migrations/*_create_players_table.php`.
- [ ] 1.3 Create `matches` migration with schedule, venue/maps, payment, announcement, status, and score fields - `database/migrations/*_create_matches_table.php`.
- [ ] 1.4 Create `match_rosters` migration with nullable `player_id`, nullable `guest_name`, `role`, and match FK - `database/migrations/*_create_match_rosters_table.php`.
- [ ] 1.5 Create `match_events` migration with match FK, scorer FK, optional assist player FK, event type, and minute - `database/migrations/*_create_match_events_table.php`.

### Phase 2: Models & Factories
- [ ] 2.1 Update `User` fillable/casts and add `isAdmin()` - `app/Models/User.php`.
- [ ] 2.2 Add `Player` model with relationships and `goals`/`assists` aggregate helpers - `app/Models/Player.php`.
- [ ] 2.3 Add `FootballMatch` model with status constants/scopes and roster/event relationships - `app/Models/FootballMatch.php`.
- [ ] 2.4 Add `MatchRoster` and `MatchEvent` models with constants, fillable fields, casts, and relationships - `app/Models/MatchRoster.php`, `app/Models/MatchEvent.php`.
- [ ] 2.5 Add/update factories for users, players, matches, rosters, and events - `database/factories/*.php`.

### Phase 3: Seed Data
- [ ] 3.1 Seed a known admin user and remove reliance on public registration for demo access - `database/seeders/DatabaseSeeder.php`.
- [ ] 3.2 Seed demo active players with positions/photos/jersey numbers - `database/seeders/DatabaseSeeder.php`.
- [ ] 3.3 Seed one upcoming match with WhatsApp announcement/payment fields and roster entries - `database/seeders/DatabaseSeeder.php`.
- [ ] 3.4 Seed one finished match with score and goal/assist events for leaderboard data - `database/seeders/DatabaseSeeder.php`.

### Phase 4: Tests / Validation
- [ ] 4.1 Add feature tests for domain seed data, user admin role, and model relationships - `tests/Feature/ZeitlosDomainTest.php`.
- [ ] 4.2 Add unit tests for player goal/assist aggregate behavior - `tests/Unit/PlayerStatsTest.php`.
- [ ] 4.3 Run migrations and full test suite - `php artisan test`.

---

## Dependencies & Risks

| Risk | Impact | Mitigation |
|------|--------|------------|
| SQLite dev vs MySQL target | Medium | Use portable Laravel column types and avoid DB-specific enum behavior. |
| `matches` model name conflicts with PHP/reserved wording | Low | Use `FootballMatch` class mapped to `matches` table. |
| Stats correction model can become ambiguous | Medium | Store explicit adjustment columns now only if needed; derived event totals remain source of truth for live scoring. |
| Breeze registration still exists | Low | Leave auth routes untouched in Increment 1; admin access and registration policy can be tightened when admin UI/auth flow is planned. |

---

## Notes
- This increment intentionally creates no new React pages; later UI increments can rely on real database structures instead of mocks.
- The schema covers every dynamic field required by `zeitlos.md`, including WhatsApp roster/payment/maps data.
- The full brief remains represented across the roadmap, not dropped into out-of-scope limbo.
