# Change: Add automatic live match minutes

## Why
Manual goal-minute entry creates avoidable errors for admins who are managing the match in real time. The application can calculate authoritative elapsed minutes safely from server-side match timestamps.

## What Changes
- Persist the time a match becomes live and the time it is finalized.
- Automatically derive and store each goal event's minute from the persisted live-match clock.
- Remove editable minute fields from the Filament scoring actions.
- Preserve existing admin and SuperAdmin scoring permissions while preventing scoring before a live clock has started.

## Impact
- Affected specs: `live-match-scoring`, `admin-team-management`
- Affected code: `FootballMatch` persistence and domain helpers, Filament live scoring actions, and live scoring feature tests
