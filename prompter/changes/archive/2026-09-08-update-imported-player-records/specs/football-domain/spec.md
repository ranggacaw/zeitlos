## ADDED Requirements

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
