## ADDED Requirements
### Requirement: SuperAdmin Override Permissions
The system SHALL allow users with the `superadmin` role to override finished match constraints, enabling roster modifications, live scoring adjustments, goal deletions, score updates, and match deletions regardless of match status.

#### Scenario: SuperAdmin manages roster on finished match
- **WHEN** an authenticated superadmin opens the roster management page for a finished match
- **THEN** the system displays roster mutation actions (add entry, delete entry, WhatsApp import) and indicates SuperAdmin edit mode

#### Scenario: SuperAdmin manages scoring on finished match
- **WHEN** an authenticated superadmin opens the live scoring page for a finished match
- **THEN** the system displays scoring mutation actions (record goal, record opponent goal, delete goal, finalize score) and indicates SuperAdmin edit mode

#### Scenario: Standard admin cannot modify finished match
- **WHEN** an authenticated standard admin opens the roster or live scoring page for a finished match
- **THEN** the system presents the match in read-only mode and hides mutation actions

#### Scenario: SuperAdmin deletes match
- **WHEN** an authenticated superadmin executes a delete action on any match (including finished matches)
- **THEN** the match record and associated roster and event relations are deleted

### Requirement: Filament User Management Resource
The system SHALL provide a Filament User Management resource accessible exclusively to superadmins for managing user accounts, passwords, and role assignments (`user`, `admin`, `superadmin`).

#### Scenario: SuperAdmin opens User Management in Filament
- **WHEN** an authenticated superadmin opens the Filament User Management resource
- **THEN** the system lists all user accounts with search and role badges

#### Scenario: SuperAdmin creates or updates a user
- **WHEN** an authenticated superadmin creates or updates a user with name, email, password, and assigned role
- **THEN** the user record is persisted with the specified role and hashed password

#### Scenario: Standard admin cannot access User Management
- **WHEN** an authenticated standard admin attempts to access the Filament User Management resource
- **THEN** the system denies access

#### Scenario: SuperAdmin cannot delete their own account
- **WHEN** an authenticated superadmin attempts to delete their currently authenticated user account
- **THEN** the system prevents deletion and returns an authorization warning
