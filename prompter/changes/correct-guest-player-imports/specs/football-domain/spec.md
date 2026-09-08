## MODIFIED Requirements

### Requirement: Historical Guest Record Normalization
The system SHALL preserve historical guest roster, scorer, and assist names as guest data. The system SHALL provide an admin-only review action that lets an admin select accidentally created player records, restore each selected record's roster, scorer, and assist references to the player's name in the applicable guest-name field, and delete only those selected player records. The system SHALL NOT automatically convert guest names into player records or automatically delete player records.

#### Scenario: Admin reviews accidental guest player records
- **WHEN** an admin opens the Players management list
- **THEN** the admin can filter player records for guest-recovery review and select only the records to restore

#### Scenario: Admin restores selected guest roster records
- **WHEN** an admin confirms recovery for a selected accidental player record referenced by match rosters
- **THEN** each roster reference is replaced by the player's name as `guest_name`, the player reference is cleared, and the selected player record is deleted

#### Scenario: Admin restores selected guest scoring records
- **WHEN** an admin confirms recovery for a selected accidental player record referenced as a scorer or assist
- **THEN** each applicable event reference is cleared, the player's name is stored in the corresponding guest-name field, and the selected player record is deleted

#### Scenario: Unselected player is preserved
- **WHEN** an admin confirms recovery for selected player records
- **THEN** unselected player records and their roster and event references are unchanged
