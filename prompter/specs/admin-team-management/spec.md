# admin-team-management Specification

## Purpose
Authenticated admin CMS workflows for managing Zeitlos football domain data: players, matches, match rosters (players and guests), leaderboard corrections, dashboard overview data, and copyable WhatsApp roster text, protected behind an admin-only route group.
## Requirements
### Requirement: Admin Access Control
The system SHALL restrict team management pages and mutations to authenticated admin users and SHALL route authenticated admins to the Filament CMS after login.

#### Scenario: Guest is redirected from admin management
- **WHEN** a guest opens an admin team management route
- **THEN** the system redirects the guest to authentication

#### Scenario: Non-admin user is denied admin management
- **WHEN** an authenticated non-admin user opens an admin team management route
- **THEN** the system denies access

#### Scenario: Admin user accesses team management
- **WHEN** an authenticated admin user opens an admin team management route
- **THEN** the system renders the requested admin management page

#### Scenario: Admin is redirected to the Filament CMS after login
- **WHEN** an authenticated admin completes login
- **THEN** the system redirects the admin to `/admin` instead of a standalone dashboard page

#### Scenario: Placeholder dashboard route is retired
- **WHEN** the application registers authenticated entry routes
- **THEN** the standalone `/dashboard` route and page are no longer registered, and non-admin authenticated users are routed to the public home page

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
- **THEN** the player record is persisted with roster identity, active status, a photo set by either uploading an image file or entering a photo URL, join date, and stat correction fields

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

### Requirement: Filament Live Scoring Workflow
The system SHALL provide a Filament-native workflow for admins to manage live scoring for a specific match using the existing match status, score, and goal event models.

#### Scenario: Admin opens Filament live scoring
- **WHEN** an authenticated admin opens the Filament live scoring page for a match
- **THEN** the system shows match context, current status and score, selectable scoring players, and existing goal events

#### Scenario: Admin marks a match live in Filament
- **WHEN** an admin marks a scheduled match live from the Filament live scoring workflow
- **THEN** the match status is persisted as live without changing final score fields

#### Scenario: Admin records a goal in Filament
- **WHEN** an admin submits a scorer, optional assist player, and optional minute from the Filament live scoring workflow
- **THEN** the system persists a goal event for that match using the existing `MatchEvent` model

#### Scenario: Admin removes a goal in Filament
- **WHEN** an admin removes a goal event from the Filament live scoring workflow
- **THEN** the event is deleted and derived player statistics no longer include it

#### Scenario: Admin finalizes a match in Filament
- **WHEN** an admin submits valid final Zeitlos and opponent scores from the Filament live scoring workflow
- **THEN** the system stores both scores and marks the match as finished

#### Scenario: Filament scoring updates public stats
- **WHEN** an admin records a goal with an assist through the Filament live scoring workflow
- **THEN** the public leaderboard and player totals include the scorer goal and assist player assist

### Requirement: Filament Leaderboard Correction Workflow
The system SHALL provide a Filament-native workflow for admins to review player scoring totals and update goal and assist correction fields.

#### Scenario: Admin opens Filament leaderboard corrections
- **WHEN** an authenticated admin opens the Filament leaderboard correction page
- **THEN** the system shows players with event-derived goals, event-derived assists, adjustment fields, and adjusted totals

#### Scenario: Admin updates leaderboard corrections in Filament
- **WHEN** an admin changes a player's goal or assist adjustment from the Filament leaderboard workflow
- **THEN** the correction is persisted on the player record and public leaderboard totals reflect the adjusted values

### Requirement: Filament Admin Usability Polish
The system SHALL organize Filament admin workflows with clear navigation labels, grouped resource navigation, useful table filtering, quick actions, and dark-friendly custom surfaces.

#### Scenario: Admin navigates Filament workflows
- **WHEN** an admin opens the Filament CMS panel
- **THEN** the navigation exposes dashboard, player management, match management, and leaderboard correction workflows with clear labels and grouping

#### Scenario: Admin uses workflow quick actions
- **WHEN** an admin views Filament dashboard or match management surfaces
- **THEN** the page provides quick actions or row actions for the related player, match, roster, live scoring, and leaderboard workflows

#### Scenario: Admin uses polished tables in dark mode
- **WHEN** an admin uses Filament tables or custom admin surfaces in dark mode
- **THEN** searchable/filterable data remains readable and follows the Zeitlos CMS dark-friendly visual treatment

### Requirement: Legacy Inertia Admin Retirement
The system SHALL retire the temporary Inertia admin CMS surface so Filament `/admin` is the only admin CMS source of truth.

#### Scenario: Legacy admin entry redirects to Filament
- **WHEN** an admin opens a legacy admin entry URL under `/admin-legacy`
- **THEN** the system redirects the request to `/admin`

#### Scenario: Legacy admin route names are unavailable
- **WHEN** the application registers routes
- **THEN** legacy Inertia admin route names for player, match, roster, scoring, leaderboard, and dashboard management are not registered

#### Scenario: Filament admin remains available after retirement
- **WHEN** an authenticated admin opens `/admin`
- **THEN** the system renders the Filament CMS panel for admin team management

### Requirement: Admin Authentication Entry Usability
The system SHALL provide a show/hide password control on both the Breeze login page and the Filament admin login page.

#### Scenario: Admin toggles password visibility on Breeze login
- **WHEN** an admin clicks the password visibility toggle on the Breeze `/login` page
- **THEN** the password field reveals or hides the entered password

#### Scenario: Admin toggles password visibility on Filament login
- **WHEN** an admin clicks the password visibility toggle on the Filament `/admin/login` page
- **THEN** the password field reveals or hides the entered password

