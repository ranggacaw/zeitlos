## Context
Zeitlos is currently a Laravel 12 Breeze + Inertia React scaffold. The product brief requires public read-only team pages and admin-only management, but the app has no football domain schema yet.

## Goals / Non-Goals
- Goals: create portable Laravel schema and models for players, matches, rosters, events, seeded demo data, and baseline tests.
- Non-Goals: build public React pages, admin CRUD forms, live scoring actions, PWA assets, or final mobile styling in this increment.

## Decisions
- Decision: use `FootballMatch` as the Eloquent class name mapped to the `matches` table.
- Rationale: keeps the database table name natural while avoiding ambiguity around a generic `Match` class name.
- Decision: store match announcement and payment fields directly on `matches`.
- Rationale: the WhatsApp roster output is match-specific and the fields are part of the match announcement record.
- Decision: derive goals and assists from `match_events`, with optional adjustment columns on `players` if manual corrections are needed.
- Rationale: events remain the source of truth for live scoring while preserving the brief's requirement that admins can correct leaderboard totals later.
- Decision: use string status/type/role columns instead of database enums.
- Rationale: keeps migrations portable across SQLite development and MySQL deployment.

## Risks / Trade-offs
- Risk: manual stat corrections can become ambiguous if mixed with event totals. Mitigation: document model helpers clearly and test aggregate behavior.
- Risk: Breeze public registration still exists after adding admin role support. Mitigation: keep auth route policy changes for the admin/auth increment instead of changing unrelated starter behavior here.
- Risk: SQLite development can hide MySQL-specific issues. Mitigation: avoid DB-specific enum/check syntax and use Laravel portable column types.

## Migration Plan
1. Add migrations for user role and football domain tables.
2. Add models, relationships, factories, and seed data.
3. Run migrations and tests locally.

## Open Questions
- None for this increment.
