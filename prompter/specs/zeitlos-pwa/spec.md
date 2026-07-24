# zeitlos-pwa Specification

## Purpose
Make the Zeitlos public team app installable and resilient on mobile: expose PWA install metadata, register a service worker, serve a deterministic offline fallback, and surface a non-disruptive install prompt where the browser supports it.
## Requirements
### Requirement: Installable PWA Metadata
The system SHALL expose installable PWA metadata for the Zeitlos public team app.

#### Scenario: Browser reads install metadata
- **WHEN** a visitor opens a Zeitlos page in a browser that supports web app manifests
- **THEN** the response includes manifest and theme metadata that allow the browser to identify the app name, start URL, display mode, colors, and app icons

### Requirement: Service Worker Registration
The system SHALL register a service worker in supported browser contexts so the public app can provide basic offline behavior.

#### Scenario: Supported browser loads the app
- **WHEN** a visitor loads the app in a browser with service worker support
- **THEN** the app registers the Zeitlos service worker without blocking the Inertia page render

### Requirement: Offline Fallback
The system SHALL provide a deterministic offline fallback for failed navigation requests after the service worker is installed.

#### Scenario: Visitor navigates while offline
- **WHEN** a visitor has previously loaded the app and then opens a navigation route while offline
- **THEN** the service worker returns the offline fallback page instead of a browser network error

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

