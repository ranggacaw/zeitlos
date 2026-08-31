## 1. Persistence and domain clock
- [x] 1.1 Add nullable `live_started_at` and `live_finished_at` timestamps to the `matches` table in a new migration.
- [x] 1.2 Add timestamp casts and a server-side elapsed-minute helper to `app/Models/FootballMatch.php`.

## 2. Filament live scoring
- [x] 2.1 Start the persistent clock when `markLive` changes a match to live, without overwriting an existing start timestamp, in `app/Filament/Resources/FootballMatches/Pages/ManageFootballMatchLiveScoring.php`.
- [x] 2.2 Remove manual minute inputs and persist automatically calculated minutes for Zeitlos and opponent goal actions in `app/Filament/Resources/FootballMatches/Pages/ManageFootballMatchLiveScoring.php`.
- [x] 2.3 Freeze the persistent clock when finalizing a match, without overwriting the initial finish timestamp during authorized SuperAdmin re-finalization, in `app/Filament/Resources/FootballMatches/Pages/ManageFootballMatchLiveScoring.php`.
- [x] 2.4 Reject scoring without a started live clock while preserving authorized SuperAdmin edits to finished matches in `app/Filament/Resources/FootballMatches/Pages/ManageFootballMatchLiveScoring.php`.

## 3. Tests and specifications
- [x] 3.1 Add deterministic clock lifecycle and automatic-minute feature coverage in `tests/Feature/AdminLiveMatchScoringTest.php`.
- [x] 3.2 Update live scoring requirements and scenarios in `prompter/specs/live-match-scoring/spec.md` and `prompter/specs/admin-team-management/spec.md`.
- [x] 3.3 Run `php artisan test --filter=AdminLiveMatchScoringTest` and `prompter validate add-automatic-live-match-minutes --strict --no-interactive`.
