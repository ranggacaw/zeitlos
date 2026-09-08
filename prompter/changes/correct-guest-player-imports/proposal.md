# Change: Preserve guest names in imported rosters

## Why
WhatsApp roster imports and a historical migration incorrectly created active Zeitlos player records for unmatched names. This duplicates players who are guests and incorrectly includes them in the team's player roster.

## What Changes
- Match imported roster names only against existing Zeitlos players, case-insensitively after trimming whitespace.
- Persist unmatched imported names as guest roster entries without creating player records.
- Let admins review selected accidental player records and restore their roster, scorer, and assist references to guest names before deleting them.
- Update the guest-record specifications and coverage to replace automatic normalization.

## Impact
- Affected specs: `admin-team-management`, `football-domain`
- Affected code: WhatsApp template import, player resource table, player/roster/event relations, database migration, feature tests
