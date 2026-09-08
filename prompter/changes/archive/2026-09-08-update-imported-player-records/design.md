## Context

WhatsApp roster imports currently preserve unknown names as guest roster entries. Existing guest roster entries and guest scorer or assist names similarly do not contribute to a player record's appearances or statistics. The change must normalize this historical data without removing the supported manual-guest workflow.

## Goals / Non-Goals

- Goals:
  - Resolve names by trimmed, case-insensitive player name matching.
  - Create active players only when no matching player exists.
  - Backfill all historical guest roster, scorer, and assist references without duplicating players or records on repeated execution.
  - Retain manually entered guest roster and scoring behavior for future use.
- Non-Goals:
  - Change existing player names, jersey numbers, photos, corrections, or positions.
  - Convert opponent events into player-scored events.
  - Add a frontend JavaScript test framework.

## Decisions

- Decision: Centralize the trimmed, case-insensitive lookup-or-create behavior so the WhatsApp importer and historical migration apply identical identity rules.
  - New player positions are `Goalkeeper` for goalkeeper roster roles and `Player` for player roster roles. Event-only names use `Player`.
  - Existing matched player records retain their existing attributes, including position.
  - Rationale: matching one identity rule avoids duplicates while role-derived defaults provide useful roster data only for newly created records.
- Decision: Normalize historical records in an idempotent data migration.
  - Process every roster entry with a guest name, then every event guest scorer and guest assist name; set the related foreign key and clear the guest-name field after resolution.
  - Rationale: a migration makes existing appearances and stats correct immediately while ensuring re-execution does not produce duplicate players or overwrite already-normalized records.
- Decision: Keep free-text guest fields and the existing manual guest workflow.
  - Rationale: the requested normalization applies to WhatsApp imports and historical repair, not to intentional manual external substitutes.

## Risks / Trade-offs

- Different people with equal names after trimming and case folding will resolve to the first existing matching player. This follows the approved identity rule; administrators can correct player records afterward if needed.
- Historical guest event names can create players that have no roster role. Those player records default to `Player`.
- The migration intentionally normalizes former external substitutes within the confirmed scope, even though new manually entered guests remain possible.

## Migration Plan

1. Add the reusable player-resolution behavior and update the importer to store resolved player IDs only.
2. Run the idempotent historical migration after the existing guest-name columns are available.
3. Verify migrated roster and Zeitlos goal-event references contribute to derived appearances, goals, and assists.
4. Roll back application behavior by deploying the prior release if necessary; preserve migrated player references and do not restore guest text automatically, because restoration cannot reliably distinguish prior guest records from their resolved player identities.

## Open Questions

None.
