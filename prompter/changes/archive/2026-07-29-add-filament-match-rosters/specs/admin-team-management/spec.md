## ADDED Requirements

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
