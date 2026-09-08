## Context
The 2026-09-08 imported-player change created active `Player` records for every unknown roster and historical guest name. Guest data must instead remain in the existing guest-name fields.

## Goals / Non-Goals
- Goals: Link imported names to existing players only; keep unmatched names as guests; provide explicit, safe recovery for selected mistakenly created records.
- Non-Goals: Automatically identify or delete existing player records; change normal manual roster entry behavior.

## Decisions
- Decision: `PlayerResolver` will find an existing player only. The importer will create either a player-backed roster record or a guest-backed roster record from that result.
- Decision: Recovery is a confirmed, selected Filament bulk action on the existing Players resource. It restores every selected record's roster, scorer, and assist references to that record's name before deletion.
- Decision: A corrective schema migration will allow `match_events.scorer_id` to be null so a guest scorer can be represented without a placeholder player.

## Risks / Trade-offs
- An admin can select a real player during recovery. The action will be explicit, show a destructive confirmation, and apply only to selected records.
- Guest scorers are excluded from player-derived totals after recovery, which reflects that they are not Zeitlos players.

## Migration Plan
1. Deploy the nullable guest-scorer schema change and updated importer.
2. In Players, filter and select the accidental guest records for review.
3. Run the confirmed recovery action to restore their guest names and delete only those selected records.

## Open Questions
- None.
