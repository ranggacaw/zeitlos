## 1. Implementation

- [x] 1.1 Add shared trimmed, case-insensitive player lookup-or-create behavior that creates active players with a supplied default position without modifying matched player attributes.
- [x] 1.2 Update `WhatsAppMatchTemplateImport` so every parsed `LIST KIPER` and `LIST PLAYER` name resolves to a player record and imported roster entries persist `player_id` with `guest_name` cleared.
- [x] 1.3 Add an idempotent historical data migration that converts every guest roster entry, guest scorer name, and guest assist name into player references; use roster roles to default newly created roster players to `Goalkeeper` or `Player`, default event-only players to `Player`, and clear the migrated guest-name fields.
- [x] 1.4 Preserve the Filament manual guest roster entry and guest live-scoring paths for newly entered external substitutes.
- [x] 1.5 Update the public player detail page to render match appearances as an accessible vertical list whose starting, live, and finished entries link to `public.matches.live`, while scheduled entries remain non-links.

## 2. Verification

- [x] 2.1 Add or update PHPUnit importer coverage for whitespace-trimmed, case-insensitive matching; active player creation; role-derived positions; and imported roster IDs without guest names.
- [x] 2.2 Add PHPUnit migration coverage for all guest roster records and guest scorer and assist names, including idempotency, position defaults, field clearing, and former external substitutes.
- [x] 2.3 Update player-stat PHPUnit coverage to verify normalized historical roster and event references contribute to appearances, goals, and assists.
- [x] 2.4 Update public player-detail feature coverage to assert serialized appearance data and the status-specific live-route eligibility; verify markup behavior server-side without adding a frontend JavaScript test framework.
