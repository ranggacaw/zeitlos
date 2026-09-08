# football-domain Specification

## Purpose
TBD - created by archiving change add-football-domain-foundation. Update Purpose after archive.
## Requirements
### Requirement: Admin Role Support
The system SHALL persist a role for each user and SHALL support identifying an authenticated admin user for later admin-only Zeitlos workflows.

#### Scenario: Admin user is identified
- **WHEN** a user record has the admin role
- **THEN** the application can identify that user as an admin

#### Scenario: Non-admin user is not identified as admin
- **WHEN** a user record does not have the admin role
- **THEN** the application does not identify that user as an admin

### Requirement: Player Roster Data
The system SHALL persist football player records with name, optional photo, jersey number, position, active state, optional join date, and stat correction fields needed by leaderboard calculations.

#### Scenario: Active player is stored
- **WHEN** a player is created with roster details
- **THEN** the player record includes identity, jersey, position, active state, and optional photo information

### Requirement: Match Schedule Data
The system SHALL persist match records with opponent, date, time, venue, maps URL, ticket, dress code, facilities, notes, payment details, status, and score fields required by public schedule, roster, live scoring, and WhatsApp announcement workflows. Match status SHALL support scheduled, live, and finished values.

#### Scenario: Upcoming match includes announcement fields
- **WHEN** an upcoming match is stored
- **THEN** the match includes venue, maps, time, date, price, dress code, facilities, notes, and payment information

#### Scenario: Finished match includes score fields
- **WHEN** a match is finished
- **THEN** the match can store Zeitlos and opponent final scores

#### Scenario: Live match status is stored
- **WHEN** an admin marks a match as live
- **THEN** the match status can be persisted without requiring final scores

### Requirement: Match Roster Data
The system SHALL persist match roster entries for goalkeepers and players, allowing each entry to reference an existing player or store a guest/substitute name.

#### Scenario: Existing player is assigned to a match roster
- **WHEN** a roster entry references an existing player
- **THEN** the entry is associated with the selected match and player

#### Scenario: Guest player is assigned to a match roster
- **WHEN** a roster entry uses a free-text guest name
- **THEN** the entry is associated with the selected match and stores the guest name

### Requirement: Match Event Stats Foundation
The system SHALL persist match events for goals and assists so player goal and assist totals can be derived from recorded match play.

#### Scenario: Goal event contributes to player goals
- **WHEN** a goal event is recorded for a player
- **THEN** that player's goal total includes the event

#### Scenario: Assist event contributes to player assists
- **WHEN** an assist is recorded for a player
- **THEN** that player's assist total includes the event

### Requirement: Demo Data Foundation
The system SHALL seed data sufficient to demonstrate the Zeitlos domain foundation, including an admin user, players, one upcoming match, one finished match, roster entries, and scoring events.

#### Scenario: Demo seed data exists after seeding
- **WHEN** the database seeder runs
- **THEN** the database contains a demo admin, players, matches, roster entries, and match events

### Requirement: Historical Guest Record Normalization
The system SHALL provide an idempotent historical data migration that processes every match roster entry with a guest name and every match event guest scorer and guest assist name. For each non-empty historical name, the migration SHALL trim whitespace and match players case-insensitively, reuse a matching player when available, or create an active player when no matching player exists. The migration SHALL store the applicable roster, scorer, or assist player ID and clear the corresponding guest-name field. Newly created players originating from goalkeeper roster entries SHALL have position `Goalkeeper`; those from player roster entries and event-only names SHALL have position `Player`. The migration SHALL include former external substitutes.

#### Scenario: Historical guest roster entry is normalized
- **WHEN** a historical guest roster entry has a non-empty guest name
- **THEN** the migration references a trimmed case-insensitively matched or newly created active player, clears the guest name, and uses the roster role to default a newly created player's position

#### Scenario: Historical guest scorer and assist are normalized
- **WHEN** a historical event has a guest scorer name, guest assist name, or both
- **THEN** the migration references a trimmed case-insensitively matched or newly created active player for each populated field, clears the corresponding guest-name fields, and defaults event-only new players to position `Player`

#### Scenario: Historical normalization is rerun
- **WHEN** the historical normalization runs more than once against the same data
- **THEN** it does not create duplicate players or duplicate roster or event records and retains the resolved references

