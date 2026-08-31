## MODIFIED Requirements

### Requirement: Live Match Status Management
The system SHALL allow admins to mark a scheduled match as live before final score submission and SHALL record the server time at which its live clock starts.

#### Scenario: Admin marks match as starting
- **WHEN** an admin marks a scheduled match as starting
- **THEN** the system stores starting status without changing final score fields or starting the live clock

#### Scenario: Admin starts live match
- **WHEN** an admin marks a scheduled or starting match as live
- **THEN** the system stores live status, preserves final score fields, and records the server time as the live-clock start without replacing an existing start time

#### Scenario: Non-admin cannot start live match
- **WHEN** a non-admin user attempts to mark a match as live
- **THEN** the system denies access according to the admin route protections

### Requirement: Goal Event Management
The system SHALL allow admins to record and delete goal events with a scorer and optional assist player, and SHALL derive every newly recorded goal minute from the server-side live-match clock.

#### Scenario: Admin records a goal during a live match
- **WHEN** an admin submits a valid scorer and optional assist for a match with a started live clock
- **THEN** the system persists a Zeitlos goal event with a positive whole elapsed minute calculated from the persisted start time

#### Scenario: Admin records an opponent goal during a live match
- **WHEN** an admin records an opponent goal for a match with a started live clock
- **THEN** the system persists an opponent goal event without a player scorer name and with a positive whole elapsed minute calculated from the persisted start time

#### Scenario: Admin records a goal before the live clock starts
- **WHEN** an admin attempts to record either team's goal before the match has been started live
- **THEN** the system rejects the action and does not create an event

#### Scenario: Admin deletes a goal event
- **WHEN** an admin deletes an existing match goal event
- **THEN** the system removes the event from the match, decrements the matching team score, and derived player stats no longer include Zeitlos player goals from that event

### Requirement: Match Score Finalization
The system SHALL allow admins to store final Zeitlos and opponent scores, mark a match as finished, and record the server time at which its live clock stops.

#### Scenario: Admin finalizes a match score
- **WHEN** an admin submits valid final scores for a match
- **THEN** the system stores both scores, marks the match as finished, and records the server time as the live-clock finish without replacing an existing finish time
