## Context
The domain foundation already includes `matches`, `players`, `match_rosters`, and `match_events`. Admin management already exposes player, match, and roster CRUD behind the `auth`, `verified`, and `admin` route group.

## Goals / Non-Goals
- Goals: provide an admin live scoring console, persist goal events, finalize score/status, and keep public leaderboard/player totals derived from persisted events.
- Non-Goals: real-time broadcasting, push notifications, offline scoring, or advanced event types beyond goals with optional assists.

## Decisions
- Add focused admin controllers instead of expanding `MatchController`, keeping event recording and score finalization isolated.
- Reuse the existing `match_events` table and `MatchEvent::TYPE_GOAL` rather than introducing new schema for this increment.
- Use roster players as the primary player picker for scoring, with active players available as a fallback if needed by the implementation.

## Risks / Trade-offs
- Live scoring is request/response Inertia, not websocket-based; admins refresh/navigate normally instead of receiving real-time updates.
- Deleting an event immediately changes public leaderboard totals because player stats are derived from `match_events`.
