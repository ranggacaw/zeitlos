## ADDED Requirements

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
