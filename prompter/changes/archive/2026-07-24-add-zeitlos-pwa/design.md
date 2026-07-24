## Context
The app is a Laravel 12 + Inertia React application built with Vite. Public team pages already exist under `resources/js/Pages/Public`, and the shared public chrome lives in `resources/js/Layouts/PublicLayout.jsx`.

## Goals / Non-Goals
- Goals: provide installable PWA metadata, register a service worker, support a deterministic offline fallback, and expose a lightweight install prompt where supported.
- Non-Goals: full offline data synchronization, background updates, push notifications, or visual redesign. Those are outside this increment.

## Decisions
- Decision: use a static `public/sw.js` service worker rather than adding a Vite PWA plugin.
- Rationale: the current build has no PWA dependency, and a small static worker is enough for manifest/offline fallback requirements.
- Decision: cache only the offline page and explicit PWA assets.
- Rationale: Inertia responses and Vite hashed assets can change frequently; broad caching would risk stale application behavior.

## Risks / Trade-offs
- Service worker cache drift: keep cache names versioned and limit cached URLs to stable public assets.
- Browser support differences: install prompt behavior differs across platforms, so unsupported browsers should simply not show the prompt.
- Icon completeness: use simple generated/committed icons now; future brand-specific art can replace them without changing app behavior.

## Migration Plan
No data migration is required. The implementation only adds static assets, head metadata, React registration/prompt behavior, and asset-serving tests.
