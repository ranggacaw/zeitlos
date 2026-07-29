# Filament CMS Replacement Roadmap

## Increment Roadmap

| Increment | Scope | Proposed `change-id` | Depends on | Status |
|-----------|-------|----------------------|-----------|--------|
| 1 | Install Filament, configure admin-only panel at `/admin`, add Zeitlos-themed dashboard, and add basic Player + Match resources | `add-filament-admin-foundation` | — | archived |
| 2 | Add match roster management inside Filament, including existing players, guest names, goalkeeper/player grouping, and copyable WhatsApp text | `add-filament-match-rosters` | Increment 1 | archived |
| 3 | Add Filament live scoring workflow: start live, record/delete goals with assists, finalize score, and keep stats reflected publicly | `add-filament-live-scoring` | Increment 2 | archived |
| 4 | Add leaderboard correction workflow and admin usability polish: grouped navigation, labels, filters, quick actions, dark Zeitlos theme refinements | `add-filament-leaderboard-polish` | Increment 3 | archived |
| 5 | Retire or redirect the old custom Inertia admin routes/pages/controllers and update specs/tests to make Filament the admin source of truth | `replace-inertia-admin-with-filament` | Increment 4 | archived |

## Next Increment to Run

**Next up:** All increments are archived. The Filament CMS replacement feature is complete.

Advance the roadmap by running Resume Mode after each increment has been implemented and archived:

```bash
feature-planner filament-cms-replacement continue
```

Check progress anytime with:

```bash
feature-planner filament-cms-replacement status
```
