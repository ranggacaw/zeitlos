# public-team-pages Specification

## Purpose
TBD - created by archiving change add-public-team-pages. Update Purpose after archive.
## Requirements
### Requirement: Public Team Dashboard
The system SHALL provide a public read-only dashboard that summarizes Zeitlos team information from persisted football domain data.

#### Scenario: Visitor opens the public dashboard
- **WHEN** a visitor opens the public dashboard route
- **THEN** the page shows summary information for the next match, recent result, active roster, and top leaderboard players when that data exists

#### Scenario: Dashboard has no domain data
- **WHEN** no players or matches exist
- **THEN** the page renders public empty states instead of failing

### Requirement: Public Player Details
The system SHALL provide a public read-only player detail page for active players with roster identity and derived stat totals.

#### Scenario: Visitor opens an active player
- **WHEN** a visitor opens an active player's detail route
- **THEN** the page shows the player's name, jersey number, position, optional photo fallback, goals, and assists

#### Scenario: Visitor opens an unavailable player
- **WHEN** a visitor opens a missing or inactive player detail route
- **THEN** the system does not expose a public player detail page for that player

### Requirement: Public Schedule
The system SHALL provide a public read-only schedule page that lists upcoming and finished Zeitlos matches from persisted match records.

#### Scenario: Visitor views the schedule
- **WHEN** a visitor opens the schedule route
- **THEN** the page shows scheduled matches with date, time, opponent, venue, maps URL, dress code, facilities, payment information, and notes where present

#### Scenario: Finished matches are shown
- **WHEN** finished matches exist
- **THEN** the schedule includes their final Zeitlos and opponent scores

### Requirement: Public Roster
The system SHALL provide a public read-only roster page that lists active players and match roster assignments from persisted roster records.

#### Scenario: Visitor views active roster
- **WHEN** a visitor opens the roster route
- **THEN** the page lists active players with jersey number, position, optional photo fallback, and stat totals

#### Scenario: Visitor views match roster groups
- **WHEN** a match has roster entries
- **THEN** the page can show goalkeepers, regular players, and guest names grouped for that match

### Requirement: Public Leaderboard
The system SHALL provide a public read-only leaderboard that ranks players by derived goal and assist totals.

#### Scenario: Visitor views leaderboard
- **WHEN** a visitor opens the leaderboard route
- **THEN** the page lists active players ordered by goals and assists using event-derived totals plus configured stat corrections

### Requirement: Mobile-First Public App Shell
The system SHALL present public team pages in a mobile-first app shell with persistent public navigation that remains reachable and readable on small screens.

#### Scenario: Visitor uses public navigation on mobile
- **WHEN** a visitor opens any public team page on a narrow mobile viewport
- **THEN** the page shows touch-friendly navigation for Dashboard, Schedule, Roster, and Leaderboard without obscuring the page content

### Requirement: Responsive Public Page Presentation
The system SHALL render public team content with responsive layouts and touch-friendly presentation across dashboard, schedule, roster, player detail, and leaderboard pages.

#### Scenario: Visitor views public content on mobile
- **WHEN** a visitor opens a public team page on a narrow mobile viewport
- **THEN** match, player, roster, and leaderboard content remains readable without horizontal scrolling and primary links or actions use comfortable touch targets

