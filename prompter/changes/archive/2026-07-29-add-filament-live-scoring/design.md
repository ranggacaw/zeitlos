## Context
Increment 1 introduced the Filament admin panel and basic match resource. Increment 2 moved match roster management and WhatsApp roster text into Filament. Live scoring already exists in the domain through `MatchEvent`, `FootballMatch` status/score fields, and legacy Inertia controllers/routes, but Filament does not yet expose this workflow.

## Goals / Non-Goals
- Goals: Add a Filament-native match live scoring page, support live status changes, goal creation/removal, final score submission, and keep public stat totals derived from `MatchEvent` unchanged.
- Non-Goals: Change scoring schema, remove legacy Inertia scoring routes, add non-goal event types, or redesign public PWA scoring/stat pages.

## Decisions
- Add a dedicated Filament page under `FootballMatchResource` instead of embedding scoring into the match edit form, because live scoring is a fast workflow with status controls, event entry, event timeline, and finalization controls.
- Reuse existing `MatchEvent` records and `FootballMatch` status/score fields so public leaderboard and player totals continue to derive from the same data.
- Reuse the legacy scoring-player selection rule: prefer roster players with linked `Player` records; if no player roster exists, fall back to active players.
- Keep route and navigation additions inside the existing Football Match resource to preserve Filament resource conventions from prior increments.

## Risks / Trade-offs
- Filament page/action APIs differ from legacy request/redirect controllers. Mitigation: model the workflow with Filament actions/forms and cover status, event, deletion, finalization, and public stat reflection with Livewire feature tests.
- Live scoring needs to be fast and low-mistake. Mitigation: keep the first Filament version straightforward, with clear match context, compact goal entry, and visible event timeline; deeper usability polish remains Increment 4.

## Migration Plan
No data migration is required. Existing `match_events` and match score/status data must render in the new Filament workflow.
