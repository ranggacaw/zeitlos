## ADDED Requirements
### Requirement: Admin Live Scoring Console
The system SHALL provide admins with a match-specific scoring console for recording match events and final scores.

#### Scenario: Admin opens scoring console
- **WHEN** an authenticated admin opens the scoring console for a match
- **THEN** the system shows the match details, selectable scoring players, existing events, and final score controls

#### Scenario: Non-admin cannot access scoring console
- **WHEN** a guest or non-admin user attempts to open the scoring console
- **THEN** the system denies access according to the admin route protections

### Requirement: Goal Event Management
The system SHALL allow admins to record and delete goal events with a scorer, optional assist player, and optional minute.

#### Scenario: Admin records a goal
- **WHEN** an admin submits a valid scorer, optional assist player, and minute for a match
- **THEN** the system persists a goal event attached to that match

#### Scenario: Admin deletes a goal event
- **WHEN** an admin deletes an existing match goal event
- **THEN** the system removes the event from the match and derived player stats no longer include it

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
