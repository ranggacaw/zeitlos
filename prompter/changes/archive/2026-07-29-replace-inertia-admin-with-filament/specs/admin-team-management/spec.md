## ADDED Requirements
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

## REMOVED Requirements
### Requirement: Admin CMS Dashboard
**Reason**: The temporary Inertia admin dashboard is retired; the Filament dashboard requirement is the active CMS contract.
**Migration**: Use `/admin` and the Filament dashboard/navigation.

### Requirement: Player Management
**Reason**: Temporary Inertia player management is retired; Filament player resources are the active CMS contract.
**Migration**: Use `/admin/players` through Filament.

### Requirement: Match Management
**Reason**: Temporary Inertia match management is retired; Filament match resources are the active CMS contract.
**Migration**: Use `/admin/football-matches` through Filament.

### Requirement: Match Roster Management
**Reason**: Temporary Inertia roster management is retired; Filament match roster management is the active CMS contract.
**Migration**: Use `/admin/football-matches/{record}/rosters` through Filament.

### Requirement: WhatsApp Roster Text
**Reason**: Temporary Inertia roster text display is retired; Filament WhatsApp roster text is the active CMS contract.
**Migration**: Use the Filament match roster management page.

### Requirement: Leaderboard Correction Management
**Reason**: Temporary Inertia leaderboard corrections are retired; Filament leaderboard correction workflow is the active CMS contract.
**Migration**: Use `/admin/leaderboard` through Filament.
