## MODIFIED Requirements
### Requirement: Install Prompt
The system SHALL provide a non-disruptive install prompt where the browser exposes install capability, and the prompt SHALL remain usable alongside the mobile app navigation.

#### Scenario: Browser exposes install prompt
- **WHEN** the browser fires the install prompt event for the Zeitlos app
- **THEN** the public UI shows an install action that lets the visitor trigger or dismiss installation

#### Scenario: Browser does not support install prompt
- **WHEN** the browser does not expose install capability
- **THEN** no install prompt is shown and the public pages remain usable

#### Scenario: Install prompt appears on mobile
- **WHEN** the install prompt is shown on a narrow mobile viewport
- **THEN** the prompt does not block persistent public navigation or prevent access to page content
