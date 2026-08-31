## Context
The Filament live scoring console currently accepts an optional goal minute supplied by the administrator. A match has no persisted start or end time, so the application cannot derive a trusted elapsed minute.

## Goals / Non-Goals
- Goals: calculate goal minutes only from server time, prevent accidental clock resets, and retain stable historical event minutes.
- Non-Goals: pause/resume controls, halftime handling, added time, and manual minute overrides.

## Decisions
- Decision: Add nullable `live_started_at` and `live_finished_at` timestamps to `matches`.
- Decision: Calculate an event minute in `FootballMatch` from the persisted start and the server's current time, storing a minimum value of one.
- Decision: Use `live_finished_at` as the upper bound for authorized post-finalization SuperAdmin corrections, so those events do not receive the current wall-clock minute.
- Decision: Set each timestamp only once. Starting an already-live match and re-finalizing a finished match cannot replace the original interval.
- Decision: Do not allow a goal to be recorded when there is no started live clock.

## Risks / Trade-offs
- Existing historical matches have no timestamps and remain readable, but cannot use the automatic clock until started through the new workflow.
- Real football timing conventions can require stoppage-time or pause handling; this change deliberately stores simple elapsed whole minutes.

## Migration Plan
1. Add nullable timestamps, which is backward compatible with existing match records.
2. Deploy the application code that starts, stops, and consumes the persisted clock.
3. Roll back by removing the new code first, then revert the migration if needed. Existing goal events remain unchanged.
