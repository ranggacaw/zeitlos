# Change: Add live match scoring

## Why
Admins need to record match goals and finalize results during or after a Zeitlos match without editing raw domain data manually.

## What Changes
- Add admin-only live match console routes for a match.
- Allow admins to record and delete goal events with optional assist and minute values.
- Allow admins to finalize a match score and mark the match finished.
- Surface recorded events through existing public match serialization so leaderboard and player stats reflect live scoring data.

## Impact
- Affected specs: live-match-scoring
- Affected code: admin routes/controllers, `MatchEvent` persistence, admin Inertia pages, public stats tests
