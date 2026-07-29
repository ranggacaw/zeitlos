## ADDED Requirements

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
