## MODIFIED Requirements

### Requirement: Public Player Details
The system SHALL provide a public read-only player detail page for active players with roster identity, derived stat totals, and an accessible vertical list of rostered match appearances. Appearances for starting, live, and finished matches SHALL link to the public live-match page, while scheduled appearances SHALL be presented without a live-match link.

#### Scenario: Visitor opens an active player
- **WHEN** a visitor opens an active player's detail route
- **THEN** the page shows the player's name, jersey number, position, optional photo fallback, goals, assists, and rostered match appearances

#### Scenario: Visitor opens an unavailable player
- **WHEN** a visitor opens a missing or inactive player detail route
- **THEN** the system does not expose a public player detail page for that player

#### Scenario: Visitor views a live-eligible appearance
- **WHEN** an active player's appearance has starting, live, or finished status
- **THEN** the accessible vertical appearance list links that item to the public live-match page

#### Scenario: Visitor views a scheduled appearance
- **WHEN** an active player's appearance has scheduled status
- **THEN** the accessible vertical appearance list presents that item without a link to the public live-match page
