# admin-team-management Specification

## Purpose
Authenticated admin CMS workflows for managing Zeitlos football domain data: players, matches, match rosters (players and guests), leaderboard corrections, dashboard overview data, and copyable WhatsApp roster text, protected behind an admin-only route group.
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

### Requirement: Admin CMS Dashboard
The system SHALL provide admins with a CMS dashboard that summarizes team management status and links to primary management workflows.

#### Scenario: Admin views CMS overview
- **WHEN** an admin opens the admin dashboard
- **THEN** the page includes player and match counts, live or next match context, recent result context, top scorer and assist summaries, and quick links to management pages

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

### Requirement: Leaderboard Correction Management
The system SHALL allow admins to view event-derived player scoring totals and update stat correction fields from a dedicated leaderboard CMS page.

#### Scenario: Admin corrects leaderboard stats
- **WHEN** an admin updates a player's goal or assist adjustment from leaderboard management
- **THEN** the correction is persisted and public leaderboard totals reflect the adjusted values

### Requirement: Filament Admin Foundation
The system SHALL provide an admin-only Filament CMS panel for foundational team management workflows while preserving the public Inertia PWA routes.

#### Scenario: Admin opens Filament CMS panel
- **WHEN** an authenticated admin opens `/admin`
- **THEN** the system renders the Filament CMS panel with Zeitlos admin branding and dashboard navigation

#### Scenario: Guest cannot open Filament CMS panel
- **WHEN** a guest opens `/admin`
- **THEN** the system redirects the guest to authentication

#### Scenario: Non-admin cannot open Filament CMS panel
- **WHEN** an authenticated non-admin user opens `/admin`
- **THEN** the system denies access to the Filament CMS panel

#### Scenario: Public PWA remains available
- **WHEN** a guest opens a public team page such as `/`, `/schedule`, `/roster`, or `/leaderboard`
- **THEN** the system renders the public Inertia PWA page without requiring Filament or admin authentication

### Requirement: Filament Player and Match Resources
The system SHALL expose basic Filament resources for managing players and matches using the existing Zeitlos domain models and schema.

#### Scenario: Admin manages players in Filament
- **WHEN** an admin creates or updates a player through the Filament player resource
- **THEN** the player record is persisted with roster identity, active status, photo path, join date, and stat correction fields

#### Scenario: Admin manages matches in Filament
- **WHEN** an admin creates or updates a match through the Filament match resource
- **THEN** the match record is persisted with schedule, venue, payment, announcement, status, and score fields

#### Scenario: Admin uses grouped match form
- **WHEN** an admin opens the Filament match create or edit form
- **THEN** the form groups fields into schedule, venue/maps, payment, announcement, and status/score sections

### Requirement: Filament Match Roster Management
The system SHALL provide a Filament-native workflow for admins to manage roster entries for a specific match using existing players or guest names.

#### Scenario: Admin opens Filament roster management
- **WHEN** an authenticated admin opens the Filament roster management page for a match
- **THEN** the system shows match context and the match's roster entries grouped by goalkeeper and player roles

#### Scenario: Admin assigns an existing player in Filament
- **WHEN** an admin adds a roster entry with an existing player and role from the Filament roster workflow
- **THEN** the roster entry is persisted for that match and player

#### Scenario: Admin assigns a guest in Filament
- **WHEN** an admin adds a roster entry with a guest name and role from the Filament roster workflow
- **THEN** the roster entry is persisted for that match with the guest name and no player

#### Scenario: Admin submits invalid roster identity in Filament
- **WHEN** an admin submits a roster entry with both a player and guest name or with neither identity
- **THEN** the system rejects the entry and does not create a roster record

#### Scenario: Admin removes a roster entry in Filament
- **WHEN** an admin removes a roster entry from the Filament roster workflow
- **THEN** the roster entry is deleted and no longer appears in that match's grouped roster list

### Requirement: Filament WhatsApp Roster Text
The system SHALL show copyable WhatsApp roster text in the Filament match roster workflow using the existing server-side roster text generator.

#### Scenario: Admin views Filament WhatsApp roster text
- **WHEN** an admin opens the Filament roster management page for a match with roster entries
- **THEN** the page includes WhatsApp roster text containing match details and grouped roster names

