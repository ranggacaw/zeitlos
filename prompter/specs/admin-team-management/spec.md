# admin-team-management Specification

## Purpose
Authenticated admin workflows for managing Zeitlos football domain data: players, matches, match rosters (players and guests), and copyable WhatsApp roster text, protected behind an admin-only route group.
## Requirements
### Requirement: Admin Access Control
The system SHALL restrict team management pages and mutations to authenticated admin users.

#### Scenario: Guest is redirected from admin management
- **WHEN** a guest opens an admin team management route
- **THEN** the system redirects the guest to authentication

#### Scenario: Non-admin user is denied admin management
- **WHEN** an authenticated non-admin user opens an admin team management route
- **THEN** the system denies access

#### Scenario: Admin user accesses team management
- **WHEN** an authenticated admin user opens an admin team management route
- **THEN** the system renders the requested admin management page

### Requirement: Player Management
The system SHALL allow admins to list, create, update, and delete football player records with roster identity, status, photo, join date, and stat correction fields.

#### Scenario: Admin creates a player
- **WHEN** an admin submits valid player details
- **THEN** the player is persisted and appears in admin player management

#### Scenario: Admin updates a player
- **WHEN** an admin changes an existing player's details
- **THEN** the player's persisted roster and stat correction fields are updated

### Requirement: Match Management
The system SHALL allow admins to list, create, update, and delete match records with schedule, venue, payment, announcement, status, and score fields.

#### Scenario: Admin creates a match
- **WHEN** an admin submits valid match details
- **THEN** the match is persisted with its schedule and public information fields

#### Scenario: Admin updates a match
- **WHEN** an admin changes an existing match's details
- **THEN** the match's persisted schedule, payment, announcement, status, and score fields are updated

### Requirement: Match Roster Management
The system SHALL allow admins to manage match roster entries for existing players and guest/substitute names.

#### Scenario: Admin assigns an existing player to a match roster
- **WHEN** an admin adds a roster entry that references an existing player
- **THEN** the roster entry is persisted for that match and player

#### Scenario: Admin assigns a guest to a match roster
- **WHEN** an admin adds a roster entry with a guest name and no player
- **THEN** the roster entry is persisted for that match with the guest name

### Requirement: WhatsApp Roster Text
The system SHALL provide admins with copyable WhatsApp roster text generated from a match's details and grouped roster entries.

#### Scenario: Admin views WhatsApp roster text
- **WHEN** an admin opens roster management for a match with roster entries
- **THEN** the page includes copyable WhatsApp text containing match details and grouped roster names

