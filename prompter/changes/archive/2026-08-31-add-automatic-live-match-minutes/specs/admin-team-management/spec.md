## MODIFIED Requirements

### Requirement: Filament Live Scoring Workflow
The system SHALL provide a Filament-native workflow for admins to manage live scoring for a specific match using the existing match status, score, and goal event models, with event minutes calculated from a server-side live clock.

#### Scenario: Admin opens Filament live scoring
- **WHEN** an authenticated admin opens the Filament live scoring page for a match
- **THEN** the system shows match context, current status and score, selectable scoring players, and existing goal events

#### Scenario: Admin marks a match live in Filament
- **WHEN** an admin marks a scheduled match live from the Filament live scoring workflow
- **THEN** the match status is persisted as live, its server-side live clock is started, and final score fields are unchanged

#### Scenario: Admin records a goal in Filament
- **WHEN** an admin submits a scorer and optional assist from the Filament live scoring workflow after the live clock has started
- **THEN** the system persists a goal event for that match using the existing `MatchEvent` model with an automatically calculated elapsed minute

#### Scenario: Admin removes a goal in Filament
- **WHEN** an admin removes a goal event from the Filament live scoring workflow
- **THEN** the event is deleted and derived player statistics no longer include it

#### Scenario: Admin finalizes a match in Filament
- **WHEN** an admin submits valid final Zeitlos and opponent scores from the Filament live scoring workflow
- **THEN** the system stores both scores, marks the match as finished, and freezes the server-side live clock

#### Scenario: Filament scoring updates public stats
- **WHEN** an admin records a goal with an assist through the Filament live scoring workflow
- **THEN** the public leaderboard and player totals include the scorer goal and assist player assist
