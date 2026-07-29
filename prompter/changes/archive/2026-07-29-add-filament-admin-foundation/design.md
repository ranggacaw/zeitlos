## Context
Zeitlos is a Laravel 12 + Breeze + Inertia/React PWA. Public pages are mobile-first and should remain Inertia/React. Admin pages currently live under `routes/web.php` with `/admin` custom Inertia controllers and React pages. The requested change is to move admin CMS work toward Filament while preserving the public app and existing football-domain models.

## Goals / Non-Goals
- Goals: install Filament, create an admin-only `/admin` panel, provide a dashboard, and expose basic Player and Match resources.
- Goals: reuse existing models and schema without introducing new tables in Increment 1.
- Goals: preserve public `public.*` routes and Breeze `/dashboard` behavior.
- Non-Goals: implement roster management, live scoring, leaderboard corrections, or old admin cleanup in this increment.
- Non-Goals: redesign public PWA screens.

## Decisions
- Use Filament only for authenticated admin CMS workflows; public app remains Inertia/React.
- Gate panel access through the existing admin semantics, preferably `User::canAccessPanel()` delegating to `User::isAdmin()`.
- Configure Filament at `/admin` because the approved goal is to replace the custom admin UI, but keep any old admin routes only if needed temporarily to avoid breaking incomplete workflows before Increment 5.
- Create `PlayerResource` and `FootballMatchResource` first because they are foundational for roster, scoring, and leaderboard increments.
- Use Filament form sections to group match fields by schedule, venue/maps, payment, announcement, status, and score so the CMS is easier to use than one flat form.

## Risks / Trade-offs
- Route collision: current custom Inertia admin routes already own `/admin`; implementation must make Filament route ownership explicit and avoid partial duplicate route names.
- Dependency impact: Filament introduces Livewire conventions into a mostly Inertia app; isolating Filament to admin keeps the public PWA unaffected.
- Test migration: current admin tests assert Inertia components; Increment 1 should update only tests affected by the new Filament foundation and leave later workflow assertions for later increments.
- Theme fidelity: a full custom Filament theme can be polished later; Increment 1 should apply enough branding/dark mode to make the panel recognizable and usable.

## Migration Plan
1. Install Filament and register the admin panel provider.
2. Add admin-only panel access and verify guests/non-admins cannot access `/admin`.
3. Add dashboard and basic resources.
4. Adjust route ownership for `/admin` while preserving public routes and Breeze `/dashboard`.
5. Update tests/spec deltas and validate.

## Open Questions
- None for Increment 1.
