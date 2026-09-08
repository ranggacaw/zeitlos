## 1. Guest Import Behavior

- [x] 1.1 Change `PlayerResolver` to return only existing trimmed, case-insensitive player matches. (`app/Team/PlayerResolver.php`)
- [x] 1.2 Update WhatsApp roster imports to persist unmatched names as `guest_name` entries while retaining existing-player links. (`app/Team/WhatsAppMatchTemplateImport.php`)

## 2. Guest Recovery

- [x] 2.1 Add a migration that permits a match event scorer to be represented only by `guest_scorer_name`. (`database/migrations/*_make_match_event_scorer_nullable.php`)
- [x] 2.2 Retire the prior automatic guest-to-player migration so fresh installations preserve historical guests. (`database/migrations/2026_09_08_000001_normalize_imported_player_records.php`)
- [x] 2.3 Add a focused service that restores selected player-backed roster, scorer, and assist references to guest names and deletes only those selected players. (`app/Team/GuestPlayerRestorer.php`)
- [x] 2.4 Add a confirmed, admin-only Players table filter and bulk recovery action for selected accidental guest records. (`app/Filament/Resources/Players/Tables/PlayersTable.php`)

## 3. Verification

- [x] 3.1 Update WhatsApp template feature coverage for matching existing players and retaining unmatched goalkeeper and player names as guests. (`tests/Feature/AdminTeamManagementTest.php`)
- [x] 3.2 Replace historical-normalization coverage with recovery-service and selected Filament bulk-action coverage, including roster, scorer, assist, and real-player preservation cases. (`tests/Feature/NormalizeImportedPlayerRecordsMigrationTest.php`, `tests/Feature/AdminTeamManagementTest.php`)
- [x] 3.3 Run focused PHPUnit coverage and `prompter validate correct-guest-player-imports --strict --no-interactive`.
