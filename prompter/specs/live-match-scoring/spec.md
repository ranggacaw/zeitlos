# live-match-scoring Specification

## Purpose
TBD - created by archiving change add-live-match-scoring. Update Purpose after archive.
## Requirements
### Requirement: Admin Live Scoring Console
The system SHALL provide admins with a match-specific scoring console for recording match events, managing live status, and final scores.

#### Scenario: Admin opens scoring console
- **WHEN** an authenticated admin opens the scoring console for a match
- **THEN** the system shows the match details, selectable scoring players, existing events, and final score controls

#### Scenario: Non-admin cannot access scoring console
- **WHEN** a guest or non-admin user attempts to open the scoring console
- **THEN** the system denies access according to the admin route protections

### Requirement: Live Match Status Management
The system SHALL allow admins to mark a scheduled match as live before final score submission.

#### Scenario: Admin marks match as starting
- **WHEN** an admin marks a scheduled match as starting
- **THEN** the system stores starting status without changing final score fields

#### Scenario: Admin starts live match
- **WHEN** an admin marks a scheduled or starting match as live
- **THEN** the system stores live status without changing final score fields

#### Scenario: Non-admin cannot start live match
- **WHEN** a non-admin user attempts to mark a match as live
- **THEN** the system denies access according to the admin route protections

### Requirement: Goal Event Management
The system SHALL allow admins to record and delete goal events with a scorer, optional assist player, and optional minute.

#### Scenario: Admin records a goal
- **WHEN** an admin submits a valid scorer, optional assist player, and minute for a match
- **THEN** the system persists a Zeitlos goal event attached to that match and increments the Zeitlos score

#### Scenario: Admin records an opponent goal
- **WHEN** an admin records an opponent goal with an optional minute
- **THEN** the system persists an opponent goal event without a player scorer name and increments the opponent score

#### Scenario: Admin deletes a goal event
- **WHEN** an admin deletes an existing match goal event
- **THEN** the system removes the event from the match, decrements the matching team score, and derived player stats no longer include Zeitlos player goals from that event

### Requirement: Match Score Finalization
The system SHALL allow admins to store final Zeitlos and opponent scores and mark a match as finished.

#### Scenario: Admin finalizes a match score
- **WHEN** an admin submits valid final scores for a match
- **THEN** the system stores both scores and marks the match as finished

### Requirement: Scoring Stats Reflection
The system SHALL reflect recorded scoring events in player goal and assist totals used by public leaderboards and player pages.

#### Scenario: Public stats include recorded goal
- **WHEN** a goal event with an assist is recorded for active players
- **THEN** the public leaderboard and player totals include the scorer goal and assist player assist

### Requirement: Public Live Match Updates
The system SHALL broadcast public-safe match updates when admin live scoring changes match status, goal events, or final scores.

#### Scenario: Admin changes live scoring state
- **WHEN** an admin marks a match starting, starts it live, records or deletes a goal, or finalizes the score
- **THEN** the system dispatches a public match update containing only public match data

#### Scenario: Public live page receives updates
- **WHEN** a visitor is viewing a public live match page and a broadcast update arrives
- **THEN** the page updates the score, status, and goal timeline without requiring a manual refresh
