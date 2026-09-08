## ADDED Requirements

### Requirement: WhatsApp Template Player Resolution
The system SHALL resolve every parsed WhatsApp `LIST KIPER` and `LIST PLAYER` roster name by trimming whitespace and matching player names case-insensitively. The importer SHALL reuse a matching player or create an active player when none exists, persist the resolved `player_id` with no `guest_name`, and assign newly created players the `Goalkeeper` position for `LIST KIPER` entries and the `Player` position for `LIST PLAYER` entries. The system SHALL continue to support intentional manually entered guest roster entries.

#### Scenario: Import resolves a trimmed case-insensitive name
- **WHEN** a WhatsApp roster entry has leading or trailing whitespace or differs only by letter case from an existing player
- **THEN** the imported roster entry references that existing player and stores no guest name

#### Scenario: Import creates a goalkeeper
- **WHEN** a `LIST KIPER` roster name has no matching player
- **THEN** the importer creates an active player with position `Goalkeeper` and the roster entry references that player without a guest name

#### Scenario: Import creates a player
- **WHEN** a `LIST PLAYER` roster name has no matching player
- **THEN** the importer creates an active player with position `Player` and the roster entry references that player without a guest name

#### Scenario: Admin enters an intentional guest
- **WHEN** an admin manually adds an external substitute by guest name in the roster workflow
- **THEN** the roster entry retains the guest name without requiring a player record
