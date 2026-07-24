## Context
The football domain foundation and public read-only pages already exist. Increment 3 adds authenticated admin workflows that mutate the same models used by the public pages.

## Goals / Non-Goals
- Goals: admin-only CRUD for players, matches, match roster entries, and copyable WhatsApp roster text.
- Goals: follow existing Laravel 12, Breeze auth, Inertia React, PHPUnit feature test patterns.
- Non-Goals: live scoring, match events, final score workflows, PWA/offline behavior, and full mobile visual polish.

## Decisions
- Use standard resource-style controllers under `app/Http/Controllers/Admin` to keep mutation workflows separate from `PublicTeamController`.
- Add an admin middleware that checks `User::isAdmin()` and apply it to `/admin/*` routes alongside `auth` and `verified`.
- Keep `/dashboard` unchanged for the Breeze authenticated dashboard and use `/admin` for team management.
- Generate WhatsApp roster text server-side from persisted match and roster data so the copy text is deterministic and testable.

## Risks / Trade-offs
- Admin forms touch several related models; mitigate with feature tests covering authorization and key create/update flows.
- Roster entries can reference either a player or guest name; validation must require exactly one usable identity path.

## Migration Plan
No new tables are expected. The change uses existing football-domain tables and may add only controllers, middleware, routes, UI pages, and tests.
