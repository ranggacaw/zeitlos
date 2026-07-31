## MODIFIED Requirements
### Requirement: Admin Access Control
The system SHALL restrict team management pages and mutations to authenticated admin users and SHALL route authenticated admins to the Filament CMS after login.

#### Scenario: Guest is redirected from admin management
- **WHEN** a guest opens an admin team management route
- **THEN** the system redirects the guest to authentication

#### Scenario: Non-admin user is denied admin management
- **WHEN** an authenticated non-admin user opens an admin team management route
- **THEN** the system denies access

#### Scenario: Admin user accesses team management
- **WHEN** an authenticated admin user opens an admin team management route
- **THEN** the system renders the requested admin management page

#### Scenario: Admin is redirected to the Filament CMS after login
- **WHEN** an authenticated admin completes login
- **THEN** the system redirects the admin to `/admin` instead of a standalone dashboard page

#### Scenario: Placeholder dashboard route is retired
- **WHEN** the application registers authenticated entry routes
- **THEN** the standalone `/dashboard` route and page are no longer registered, and non-admin authenticated users are routed to the public home page

### Requirement: Filament Player and Match Resources
The system SHALL expose basic Filament resources for managing players and matches using the existing Zeitlos domain models and schema.

#### Scenario: Admin manages players in Filament
- **WHEN** an admin creates or updates a player through the Filament player resource
- **THEN** the player record is persisted with roster identity, active status, a photo set by either uploading an image file or entering a photo URL, join date, and stat correction fields

#### Scenario: Admin manages matches in Filament
- **WHEN** an admin creates or updates a match through the Filament match resource
- **THEN** the match record is persisted with schedule, venue, payment, announcement, status, and score fields

#### Scenario: Admin uses grouped match form
- **WHEN** an admin opens the Filament match create or edit form
- **THEN** the form groups fields into schedule, venue/maps, payment, announcement, and status/score sections

## ADDED Requirements
### Requirement: Admin Authentication Entry Usability
The system SHALL provide a show/hide password control on both the Breeze login page and the Filament admin login page.

#### Scenario: Admin toggles password visibility on Breeze login
- **WHEN** an admin clicks the password visibility toggle on the Breeze `/login` page
- **THEN** the password field reveals or hides the entered password

#### Scenario: Admin toggles password visibility on Filament login
- **WHEN** an admin clicks the password visibility toggle on the Filament `/admin/login` page
- **THEN** the password field reveals or hides the entered password
