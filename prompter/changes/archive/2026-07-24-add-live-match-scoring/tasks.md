## 1. Backend
- [x] 1.1 Add admin routes in `routes/web.php` for match scoring console, goal event creation/deletion, and score finalization.
- [x] 1.2 Create `app/Http/Controllers/Admin/MatchScoringController.php` to render a match scoring console with match, roster/player options, and ordered events.
- [x] 1.3 Create `app/Http/Controllers/Admin/MatchEventController.php` to validate and persist/delete goal events with `match_id`, `scorer_id`, optional `assist_player_id`, and optional `minute`.
- [x] 1.4 Create `app/Http/Controllers/Admin/MatchFinalScoreController.php` to validate score fields, set `status` to finished, and store final scores.

## 2. Frontend
- [x] 2.1 Add `resources/js/Pages/Admin/Matches/Scoring.jsx` for the live scoring console.
- [x] 2.2 Add score/action links from `resources/js/Pages/Admin/Matches/Index.jsx` so admins can open scoring for each match.
- [x] 2.3 Keep forms and controls aligned with existing admin Inertia/Tailwind patterns from `resources/js/Pages/Admin/Matches/Partials/MatchForm.jsx`.
- [ ] 2.4 Manually verify the scoring console is usable on a mobile viewport. (manual)

## 3. Public Stats Integration
- [x] 3.1 Ensure the existing public serialization in `app/Http/Controllers/PublicTeamController.php` continues to include recorded events for match detail displays.
- [x] 3.2 Ensure `app/Models/Player.php` goal and assist totals reflect created/deleted scoring events plus existing adjustment fields.

## 4. Tests
- [x] 4.1 Add feature coverage in `tests/Feature/AdminLiveMatchScoringTest.php` for admin-only access, recording goals, deleting events, and finalizing scores.
- [x] 4.2 Add or update public stats coverage to verify leaderboard/player totals change after scoring events are recorded.
- [x] 4.3 Run `php artisan test`.

## Post-Implementation
- [x] Update AGENTS.md in the project root for new admin live scoring conventions if implementation introduces new durable patterns.
