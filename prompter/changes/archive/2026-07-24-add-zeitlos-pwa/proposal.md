# Change: Add Zeitlos PWA support

## Why
Zeitlos is intended to be used from mobile devices around match days, where quick access and resilience to flaky connectivity matter. Adding install metadata, a service worker, and an offline fallback makes the public team app behave like a lightweight PWA before the final mobile visual polish increment.

## What Changes
- Add web app manifest metadata, app icons, theme color, display mode, and iOS home-screen metadata.
- Register a service worker from the Inertia React entrypoint and serve a static offline fallback page.
- Cache the app shell and static PWA assets so repeat visits can load basic fallback content when offline.
- Add an install prompt component that surfaces browser install capability without disrupting unsupported browsers.

## Impact
- Affected specs: zeitlos-pwa
- Affected code: `resources/views/app.blade.php`, `resources/js/app.jsx`, `resources/js/Components/InstallPrompt.jsx`, `resources/js/Layouts/PublicLayout.jsx`, `public/manifest.webmanifest`, `public/offline.html`, `public/sw.js`, `public/icons/*`, feature tests for PWA assets
