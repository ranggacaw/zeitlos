# Feature UI Map: Filament CMS Replacement

> Derived from the approved implementation plan and roadmap. This maps new admin screens and navigation outcomes only. Filament supplies most visual structure; this file records what pages/actions the admin CMS needs so future design or preview work can stay aligned.

## Action Vocabulary

| Form | Meaning |
|------|---------|
| `→ /route` | Navigate to that page |
| `→ back` | Navigate to the previous page |
| `open modal: <modal-id>` | Open a modal defined in the Modals section |
| `close modal` | Close the current modal |
| `toast: "<message>"` | Show a toast |
| `→ external: <url>` | Open an external link |
| `expand/collapse` | Toggle an in-page section |

---

## New Routes

```text
/admin                                      Filament Dashboard             admin   Increment 1
/admin/players                              Player List                    admin   Increment 1
/admin/players/create                       Create Player                  admin   Increment 1
/admin/players/:id/edit                     Edit Player                    admin   Increment 1
/admin/football-matches                     Match List                     admin   Increment 1
/admin/football-matches/create              Create Match                   admin   Increment 1
/admin/football-matches/:id/edit            Edit Match                     admin   Increment 1
/admin/football-matches/:id/rosters         Match Roster Management        admin   Increment 2
/admin/football-matches/:id/live-scoring    Live Scoring                   admin   Increment 3
/admin/leaderboard                          Leaderboard Corrections        admin   Increment 4
```

## Entry Points from Existing UI

| Existing location | New element | Action |
|-------------------|-------------|--------|
| Breeze authenticated area `/dashboard` | Admin CMS link for admins | `→ /admin` |
| Filament sidebar | Players nav item | `→ /admin/players` |
| Filament sidebar | Matches nav item | `→ /admin/football-matches` |
| Filament sidebar | Leaderboard nav item | `→ /admin/leaderboard` |

## App Shell

Reuse Filament's panel shell. Add Zeitlos CMS branding, dark-friendly colors, and admin-only navigation groups.

---

## Pages

### `/admin` — Filament Dashboard *(Increment 1)*

- **Purpose:** Give admins a quick overview of team management status.
- **Access:** admin only.
- **Arrived from:** Breeze `/dashboard` admin link or direct `/admin` visit.
- **Layout sections:** Overview stats · Live/next match card · Recent result · Top scorers/assists · Quick actions.

**Elements & Navigation**

| Element | Type | Location | Action |
|---------|------|----------|--------|
| Players quick action | link/card | dashboard | `→ /admin/players` |
| Matches quick action | link/card | dashboard | `→ /admin/football-matches` |
| Live match card | link/card | dashboard | `→ /admin/football-matches/:id/live-scoring` |
| Next match card | link/card | dashboard | `→ /admin/football-matches/:id/edit` |
| Top scorers/assists | link/card | dashboard | `→ /admin/leaderboard` |

### `/admin/players` — Player List *(Increment 1)*

- **Purpose:** Manage player records and open player create/edit workflows.
- **Access:** admin only.
- **Arrived from:** Filament sidebar or dashboard quick action.
- **Layout sections:** Header + create CTA · searchable table · filters · pagination.

**Elements & Navigation**

| Element | Type | Location | Action |
|---------|------|----------|--------|
| New player | primary button | page header | `→ /admin/players/create` |
| Edit | row action | table | `→ /admin/players/:id/edit` |
| Delete | row action | table | `open modal: confirm-delete-player` |
| Search | input | table toolbar | stays on page |
| Active filter | filter | table toolbar | stays on page |

### `/admin/players/create` — Create Player *(Increment 1)*

- **Purpose:** Add a new roster player.
- **Access:** admin only.
- **Arrived from:** Player list New player action.
- **Layout sections:** Identity fields · status/photo fields · stat adjustment fields · form actions.

**Elements & Navigation**

| Element | Type | Location | Action |
|---------|------|----------|--------|
| Create | primary button | form actions | `toast: "Player created"` → `/admin/players` |
| Create and create another | secondary button | form actions | `toast: "Player created"` |
| Cancel | link/button | form actions | `→ /admin/players` |

### `/admin/players/:id/edit` — Edit Player *(Increment 1)*

- **Purpose:** Update player identity, status, photo path, and stat corrections.
- **Access:** admin only.
- **Arrived from:** Player list Edit action.
- **Layout sections:** Identity fields · status/photo fields · stat adjustment fields · form actions.

**Elements & Navigation**

| Element | Type | Location | Action |
|---------|------|----------|--------|
| Save changes | primary button | form actions | `toast: "Player saved"` |
| Delete | destructive action | form actions | `open modal: confirm-delete-player` |
| Back | link/button | form actions | `→ /admin/players` |

### `/admin/football-matches` — Match List *(Increment 1)*

- **Purpose:** Manage match records and open match-specific workflows.
- **Access:** admin only.
- **Arrived from:** Filament sidebar or dashboard quick action.
- **Layout sections:** Header + create CTA · status tabs/filters · match table · row actions.

**Elements & Navigation**

| Element | Type | Location | Action |
|---------|------|----------|--------|
| New match | primary button | page header | `→ /admin/football-matches/create` |
| Edit | row action | table | `→ /admin/football-matches/:id/edit` |
| Manage roster | row action | table | `→ /admin/football-matches/:id/rosters` |
| Live scoring | row action | table | `→ /admin/football-matches/:id/live-scoring` |
| Delete | row action | table | `open modal: confirm-delete-match` |
| Status filter | filter/tab | table toolbar | stays on page |

### `/admin/football-matches/create` — Create Match *(Increment 1)*

- **Purpose:** Add a scheduled match with public match info.
- **Access:** admin only.
- **Arrived from:** Match list New match action.
- **Layout sections:** Schedule · venue/maps · payment · WhatsApp announcement · status/score · form actions.

**Elements & Navigation**

| Element | Type | Location | Action |
|---------|------|----------|--------|
| Create | primary button | form actions | `toast: "Match created"` → `/admin/football-matches` |
| Create and create another | secondary button | form actions | `toast: "Match created"` |
| Cancel | link/button | form actions | `→ /admin/football-matches` |
| Maps URL | external link preview | venue section | `→ external: <maps_url>` |

### `/admin/football-matches/:id/edit` — Edit Match *(Increment 1)*

- **Purpose:** Update schedule, venue, payment, announcement, status, and score fields.
- **Access:** admin only.
- **Arrived from:** Match list Edit action, dashboard match cards.
- **Layout sections:** Schedule · venue/maps · payment · WhatsApp announcement · status/score · related workflow actions.

**Elements & Navigation**

| Element | Type | Location | Action |
|---------|------|----------|--------|
| Save changes | primary button | form actions | `toast: "Match saved"` |
| Manage roster | secondary action | page/header actions | `→ /admin/football-matches/:id/rosters` |
| Live scoring | secondary action | page/header actions | `→ /admin/football-matches/:id/live-scoring` |
| Delete | destructive action | form actions | `open modal: confirm-delete-match` |
| Back | link/button | form actions | `→ /admin/football-matches` |
| Maps URL | external link preview | venue section | `→ external: <maps_url>` |

### `/admin/football-matches/:id/rosters` — Match Roster Management *(Increment 2)*

- **Purpose:** Manage goalkeeper/player roster entries and copy WhatsApp text.
- **Access:** admin only.
- **Arrived from:** Match list row action or match edit page.
- **Layout sections:** Match summary · add entry form · grouped roster lists · WhatsApp text panel.

**Elements & Navigation**

| Element | Type | Location | Action |
|---------|------|----------|--------|
| Add roster entry | button | add form | `toast: "Roster entry added"` |
| Remove roster entry | row action | grouped roster list | `open modal: confirm-remove-roster-entry` |
| Copy WhatsApp text | button | WhatsApp panel | `toast: "WhatsApp text copied"` |
| Back to match | link/button | page header | `→ /admin/football-matches/:id/edit` |

### `/admin/football-matches/:id/live-scoring` — Live Scoring *(Increment 3)*

- **Purpose:** Record live match status, goals, assists, corrections, and final score quickly.
- **Access:** admin only.
- **Arrived from:** Match list row action, match edit page, or dashboard live match card.
- **Layout sections:** Scoreboard · status action · fast goal form · goal timeline · final score form.

**Elements & Navigation**

| Element | Type | Location | Action |
|---------|------|----------|--------|
| Start live match | button | status panel | `toast: "Match is live"` |
| Record goal | button | fast goal form | `toast: "Goal recorded"` |
| Delete goal | row action | timeline | `open modal: confirm-delete-goal` |
| Finalize match | button | final score form | `toast: "Match finalized"` → `/admin/football-matches` |
| Back to match | link/button | page header | `→ /admin/football-matches/:id/edit` |

### `/admin/leaderboard` — Leaderboard Corrections *(Increment 4)*

- **Purpose:** Review derived player totals and correct goal/assist adjustment fields.
- **Access:** admin only.
- **Arrived from:** Filament sidebar or dashboard leaderboard cards.
- **Layout sections:** Scorer table · assist table or tabs · inline adjustment fields · save actions.

**Elements & Navigation**

| Element | Type | Location | Action |
|---------|------|----------|--------|
| Edit player stats | row action | table | `→ /admin/players/:id/edit` |
| Save adjustment | inline/table action | row | `toast: "Stats updated"` |
| Top scorer tab | tab | page body | stays on page |
| Top assist tab | tab | page body | stays on page |

---

## Modals

| Modal id | Title | Opened from | Content | Buttons → outcome |
|----------|-------|-------------|---------|-------------------|
| `confirm-delete-player` | Delete player? | Player list/edit Delete | Warn that roster/event references may be affected by model constraints | Cancel → `close modal` · Delete → `close modal` → `toast: "Player deleted"` → `/admin/players` |
| `confirm-delete-match` | Delete match? | Match list/edit Delete | Warn that roster entries and events will be deleted with the match | Cancel → `close modal` · Delete → `close modal` → `toast: "Match deleted"` → `/admin/football-matches` |
| `confirm-remove-roster-entry` | Remove roster entry? | Roster entry Remove | Confirm removing this player/guest from the match roster | Cancel → `close modal` · Remove → `close modal` → `toast: "Roster entry removed"` |
| `confirm-delete-goal` | Delete goal? | Goal timeline Delete | Confirm removing this goal from scoring totals | Cancel → `close modal` · Delete → `close modal` → `toast: "Goal deleted"` |

## Drawers

No new drawers planned.

## Toasts

| Toast | Type | Triggered by |
|-------|------|--------------|
| "Player created" | success | Create player |
| "Player saved" | success | Save player |
| "Player deleted" | success | Delete player |
| "Match created" | success | Create match |
| "Match saved" | success | Save match |
| "Match deleted" | success | Delete match |
| "Roster entry added" | success | Add roster entry |
| "Roster entry removed" | success | Remove roster entry |
| "WhatsApp text copied" | success | Copy WhatsApp roster text |
| "Match is live" | success | Start live match |
| "Goal recorded" | success | Record goal |
| "Goal deleted" | success | Delete goal |
| "Match finalized" | success | Finalize match score |
| "Stats updated" | success | Save leaderboard adjustment |

---

## Pages by Increment

| Increment | Scope | Pages |
|-----------|-------|-------|
| 1 | Filament foundation, dashboard, Player + Match resources | `/admin`, `/admin/players`, `/admin/players/create`, `/admin/players/:id/edit`, `/admin/football-matches`, `/admin/football-matches/create`, `/admin/football-matches/:id/edit` |
| 2 | Match roster management and WhatsApp text | `/admin/football-matches/:id/rosters` |
| 3 | Live scoring workflow | `/admin/football-matches/:id/live-scoring` |
| 4 | Leaderboard corrections and polish | `/admin/leaderboard` |
| 5 | Retire old Inertia admin | No new pages; route cleanup only |

---

## Next Step

Design these pages as clickable previews with the `ui-ux-pro` skill if you want custom UI direction before implementation. For Filament-native resources, this map can also serve as the navigation contract without custom previews.
