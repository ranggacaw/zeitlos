# Zeitlos Football Team Management PWA Roadmap

## Increment Roadmap

| Increment | Scope | Proposed `change-id` | Depends on | Status |
|-----------|-------|----------------------|-----------|--------|
| 1 | Core domain schema, models, admin role, seed data, and baseline domain tests | `add-football-domain-foundation` | - | archived |
| 2 | Public read-only app shell, dashboard, player details, schedule, roster, and leaderboard using seeded/domain data | `add-public-team-pages` | Increment 1 | archived |
| 3 | Admin CRUD for players, matches, and match rosters, including WhatsApp roster text generation | `add-admin-team-management` | Increment 2 | archived |
| 4 | Admin live match console, match events, score finalization, and leaderboard/stat corrections | `add-live-match-scoring` | Increment 3 | archived |
| 5 | PWA manifest/service worker/offline fallback/install prompt/iOS safe-area metadata | `add-zeitlos-pwa` | Increment 4 | archived |
| 6 | Mobile-first visual styling pass with bottom tabs, dark sporty theme, touch targets, and responsive polish | `update-mobile-app-design` | Increment 5 | archived |

## Next Increment to Run

All increments are archived. There is no next increment to scaffold.

Check progress anytime with:

```bash
feature-planner zeitlos-football-team-management-pwa status
```
