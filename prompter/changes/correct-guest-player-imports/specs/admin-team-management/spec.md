## MODIFIED Requirements

### Requirement: WhatsApp Template Player Resolution
The system SHALL resolve every parsed WhatsApp `LIST KIPER` and `LIST PLAYER` roster name by trimming whitespace and matching existing player names case-insensitively. When a match exists, the importer SHALL persist the matching `player_id` with no `guest_name`. When no match exists, the importer SHALL persist the trimmed name as `guest_name` with no `player_id` and SHALL NOT create a player record. The system SHALL continue to support intentional manually entered guest roster entries.

#### Scenario: Import resolves a trimmed case-insensitive name
- **WHEN** a WhatsApp roster entry has leading or trailing whitespace or differs only by letter case from an existing player
- **THEN** the imported roster entry references that existing player and stores no guest name

#### Scenario: Import retains an unknown goalkeeper as a guest
- **WHEN** a `LIST KIPER` roster name has no matching player
- **THEN** the importer stores the trimmed name as a guest goalkeeper entry without creating a player record

#### Scenario: Import retains an unknown player as a guest
- **WHEN** a `LIST PLAYER` roster name has no matching player
- **THEN** the importer stores the trimmed name as a guest player entry without creating a player record

#### Scenario: Admin enters an intentional guest
- **WHEN** an admin manually adds an external substitute by guest name in the roster workflow
- **THEN** the roster entry retains the guest name without requiring a player record
