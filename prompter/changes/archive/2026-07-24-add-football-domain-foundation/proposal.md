# Change: Add Football Domain Foundation

## Why
Zeitlos needs durable football team data before public pages, admin management, live scoring, and PWA behavior can be built safely. The current Laravel Breeze scaffold has only auth/profile starter code and no team domain model.

## What Changes
- Add admin role support to Breeze users.
- Add core football domain tables for players, matches, match rosters, and match events.
- Add Eloquent models, relationships, factories, and seed data for a demo admin, players, upcoming match, past match, rosters, and scoring events.
- Add tests covering role support, domain relationships, seed data, and player goals/assists aggregation.

## Impact
- Affected specs: football-domain
- Affected code: `database/migrations`, `app/Models`, `database/factories`, `database/seeders/DatabaseSeeder.php`, `tests/Feature`, `tests/Unit`
- Later increments depend on this foundation for public pages, admin CRUD, live scoring, PWA behavior, and mobile styling.
