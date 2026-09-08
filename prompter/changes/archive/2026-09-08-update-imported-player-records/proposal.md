# Change: Normalize imported and historical player records

## Why
WhatsApp imports and historical guest records fragment player appearances and scoring statistics across free-text names. Resolving those names to active player records makes roster, scoring, and public player history consistent while retaining manual guest support.

## What Changes
- Resolve every WhatsApp roster name after trimming whitespace and matching case-insensitively; reuse an existing player or create an active player and store its `player_id` without a `guest_name`.
- Assign newly created imported players the `Goalkeeper` position for `LIST KIPER` entries and the `Player` position for `LIST PLAYER` entries.
- Add an idempotent historical data migration that resolves or creates players for all existing guest roster entries and guest scorer and assist names, updates related IDs, and clears the migrated guest-name fields.
- Preserve manually entered guest roster entries and their live-scoring behavior.
- Present public player appearances as an accessible vertical list, linking starting, live, and finished matches to the public live-match route while leaving scheduled matches as non-links.

## Impact
- Affected specs: `admin-team-management`, `football-domain`, `public-team-pages`
- Affected code: `App\Team\WhatsAppMatchTemplateImport`, player/roster/event models and migration, `PublicTeamController`, `resources/js/Pages/Public/PlayerShow.jsx`, and related PHPUnit feature and unit tests
