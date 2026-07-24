## 1. PWA Metadata
- [x] 1.1 Add a web app manifest with name, short name, start URL, display mode, theme/background colors, and icons - `public/manifest.webmanifest`.
- [x] 1.2 Add app icon assets referenced by the manifest - `public/icons/*`.
- [x] 1.3 Add manifest, theme color, Apple mobile web app, and icon metadata to the Inertia shell - `resources/views/app.blade.php`.

## 2. Service Worker & Offline Fallback
- [x] 2.1 Add a static offline fallback page for unavailable navigation requests - `public/offline.html`.
- [x] 2.2 Add a versioned service worker that caches stable PWA assets and falls back to the offline page for failed navigation requests - `public/sw.js`.
- [x] 2.3 Register the service worker only in production-capable browser contexts - `resources/js/app.jsx`.

## 3. Install Prompt
- [x] 3.1 Add a small install prompt component using `beforeinstallprompt`, with dismiss and install actions - `resources/js/Components/InstallPrompt.jsx`.
- [x] 3.2 Render the install prompt from the public layout without affecting admin/authenticated pages - `resources/js/Layouts/PublicLayout.jsx`.
- [ ] 3.3 Verify install prompt behavior in a supported browser on desktop/mobile (manual).

## 4. Tests / Validation
- [x] 4.1 Add feature tests that verify the manifest, service worker, and offline page are served successfully - `tests/Feature/PwaAssetsTest.php`.
- [x] 4.2 Run the frontend build - `npm run build`.
- [x] 4.3 Run the Laravel test suite - `php artisan test`.

## Post-Implementation
- [x] Update `AGENTS.md` with PWA asset and service worker notes if the implementation creates new project conventions.
