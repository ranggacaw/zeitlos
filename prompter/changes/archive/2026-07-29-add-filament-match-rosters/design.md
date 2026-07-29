## Context
Increment 1 introduced Filament as the admin CMS foundation and basic `FootballMatch` CRUD. Match roster management already exists in the domain through `MatchRoster` and `WhatsAppRosterText`, plus legacy Inertia admin tests/routes, but Filament does not yet expose the roster workflow.

## Goals / Non-Goals
- Goals: Add a Filament-native roster management page for a match, support existing player and guest roster entries, group goalkeepers and players, and show deterministic WhatsApp text.
- Non-Goals: Remove legacy Inertia roster routes, change the `match_rosters` schema, or add live scoring behavior.

## Decisions
- Add a dedicated Filament page under `FootballMatchResource` instead of embedding roster editing into the match edit form, because roster management is a workflow with grouped lists and copyable text rather than a simple match field.
- Reuse `App\Team\WhatsAppRosterText` for the displayed roster text so the Filament and legacy workflows remain deterministic and testable.
- Keep route and navigation additions inside the existing Football Match resource to preserve Filament's resource conventions from Increment 1.

## Risks / Trade-offs
- Filament page/action APIs may differ from the existing resource form/table APIs. Mitigation: follow the installed Filament resource page patterns and cover the workflow with Livewire feature tests.
- Copy-to-clipboard behavior is partly browser/UI behavior. Mitigation: make the text visible/copyable in the page and keep the visual copy behavior as a manual verification task if needed.

## Migration Plan
No data migration is required. Existing `match_rosters` data must render in the new Filament workflow.
