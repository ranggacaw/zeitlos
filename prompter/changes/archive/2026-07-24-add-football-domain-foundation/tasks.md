## 1. Database
- [x] 1.1 Add `role` column migration for `users` - `database/migrations/*_add_role_to_users_table.php`.
- [x] 1.2 Create `players` migration with identity, jersey, position, active state, photo, join date, and optional stat correction columns - `database/migrations/*_create_players_table.php`.
- [x] 1.3 Create `matches` migration with schedule, venue/maps, payment, announcement, status, and score fields - `database/migrations/*_create_matches_table.php`.
- [x] 1.4 Create `match_rosters` migration with nullable `player_id`, nullable `guest_name`, `role`, and match FK - `database/migrations/*_create_match_rosters_table.php`.
- [x] 1.5 Create `match_events` migration with match FK, scorer FK, optional assist player FK, event type, and minute - `database/migrations/*_create_match_events_table.php`.

## 2. Models & Factories
- [x] 2.1 Update `User` fillable/casts and add `isAdmin()` - `app/Models/User.php`.
- [x] 2.2 Add `Player` model with relationships and goals/assists aggregate helpers - `app/Models/Player.php`.
- [x] 2.3 Add `FootballMatch` model with status constants/scopes and roster/event relationships - `app/Models/FootballMatch.php`.
- [x] 2.4 Add `MatchRoster` and `MatchEvent` models with constants, fillable fields, casts, and relationships - `app/Models/MatchRoster.php`, `app/Models/MatchEvent.php`.
- [x] 2.5 Add/update factories for users, players, matches, rosters, and events - `database/factories/*.php`.

## 3. Seed Data
- [x] 3.1 Seed a known admin user - `database/seeders/DatabaseSeeder.php`.
- [x] 3.2 Seed demo active players with positions/photos/jersey numbers - `database/seeders/DatabaseSeeder.php`.
- [x] 3.3 Seed one upcoming match with WhatsApp announcement/payment fields and roster entries - `database/seeders/DatabaseSeeder.php`.
- [x] 3.4 Seed one finished match with score and goal/assist events for leaderboard data - `database/seeders/DatabaseSeeder.php`.

## 4. Tests & Validation
- [x] 4.1 Add feature tests for domain seed data, user admin role, and model relationships - `tests/Feature/ZeitlosDomainTest.php`.
- [x] 4.2 Add unit tests for player goal/assist aggregate behavior - `tests/Unit/PlayerStatsTest.php`.
- [x] 4.3 Run migrations and full test suite - `php artisan test`.

## Post-Implementation
- [x] Update project guidance/spec notes if implementation changes the documented domain assumptions. No updates needed; implementation follows the accepted assumptions.
