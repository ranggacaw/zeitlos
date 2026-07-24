## 1. Routing and Data Queries
- [x] 1.1 Update `routes/web.php` to register public read-only routes for dashboard, player detail, schedule, roster, and leaderboard pages.
- [x] 1.2 Create public controller actions under `app/Http/Controllers` that render Inertia pages with serialized `Player`, `FootballMatch`, `MatchRoster`, and stat data.
- [x] 1.3 Keep existing authenticated `/dashboard` and profile routes intact for Breeze admin/user flows.

## 2. Public React Pages
- [x] 2.1 Create a public app layout under `resources/js/Layouts` with navigation across dashboard, schedule, roster, and leaderboard.
- [x] 2.2 Replace the starter `resources/js/Pages/Welcome.jsx` content with a Zeitlos public dashboard using upcoming match, recent result, roster, and leaderboard summaries.
- [x] 2.3 Add public pages under `resources/js/Pages` for player detail, match schedule, roster, and leaderboard views.

## 3. Domain Presentation
- [x] 3.1 Show active players with jersey number, position, optional photo fallback, and stat totals derived from existing model helpers.
- [x] 3.2 Show upcoming and finished matches with venue, time/date, payment/announcement details, score, and roster groupings where available.
- [x] 3.3 Ensure empty states render when seeded/domain data is absent.

## 4. Tests and Verification
- [x] 4.1 Add feature tests under `tests/Feature` for each public page route and core data props.
- [x] 4.2 Run `composer test`.
- [x] 4.3 Run `npm run build`.

## Post-Implementation
- [x] Update `AGENTS.md` in the project root for new changes in this specs.
