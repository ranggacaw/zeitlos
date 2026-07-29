<!-- PROMPTER:START -->
# Prompter Instructions

These instructions are for AI assistants working in this project.

Always open `@/prompter/AGENTS.md` when the request:
- Mentions planning or proposals (words like proposal, spec, change, plan)
- Introduces new capabilities, breaking changes, architecture shifts, or big performance/security work
- Sounds ambiguous and you need the authoritative spec before coding

Use `@/prompter/AGENTS.md` to learn:
- How to create and apply change proposals
- Spec format and conventions
- Project structure and guidelines
- Show Remaining Tasks

Behavioral guidelines to reduce common LLM coding mistakes. Merge with project-specific instructions as needed.

**Tradeoff:** These guidelines bias toward caution over speed. For trivial tasks, use judgment.

## 1. Think Before Coding

**Don't assume. Don't hide confusion. Surface tradeoffs.**

Before implementing:
- State your assumptions explicitly. If uncertain, ask.
- If multiple interpretations exist, present them - don't pick silently.
- If a simpler approach exists, say so. Push back when warranted.
- If something is unclear, stop. Name what's confusing. Ask.

## 2. Simplicity First

**Minimum code that solves the problem. Nothing speculative.**

- No features beyond what was asked.
- No abstractions for single-use code.
- No "flexibility" or "configurability" that wasn't requested.
- No error handling for impossible scenarios.
- If you write 200 lines and it could be 50, rewrite it.

Ask yourself: "Would a senior engineer say this is overcomplicated?" If yes, simplify.

## 3. Surgical Changes

**Touch only what you must. Clean up only your own mess.**

When editing existing code:
- Don't "improve" adjacent code, comments, or formatting.
- Don't refactor things that aren't broken.
- Match existing style, even if you'd do it differently.
- If you notice unrelated dead code, mention it - don't delete it.

When your changes create orphans:
- Remove imports/variables/functions that YOUR changes made unused.
- Don't remove pre-existing dead code unless asked.

The test: Every changed line should trace directly to the user's request.

## 4. Goal-Driven Execution

**Define success criteria. Loop until verified.**

Transform tasks into verifiable goals:
- "Add validation" → "Write tests for invalid inputs, then make them pass"
- "Fix the bug" → "Write a test that reproduces it, then make it pass"
- "Refactor X" → "Ensure tests pass before and after"

For multi-step tasks, state a brief plan:
\`\`\`
1. [Step] → verify: [check]
2. [Step] → verify: [check]
3. [Step] → verify: [check]
\`\`\`

Strong success criteria let you loop independently. Weak criteria ("make it work") require constant clarification.

---

**These guidelines are working if:** fewer unnecessary changes in diffs, fewer rewrites due to overcomplication, and clarifying questions come before implementation rather than after mistakes.

<!-- PROMPTER:END -->

<!-- LOCAL:START -->
## Public Team Pages

- Public read-only team pages use `PublicTeamController` and named routes prefixed with `public.*`.
- Keep `/dashboard` reserved for the authenticated Breeze admin/user flow.
- Public Inertia pages live under `resources/js/Pages/Public`, with the home dashboard rendered by `Welcome.jsx`.

## Mobile Public App Shell

- The public app is mobile-first: `resources/js/Layouts/PublicLayout.jsx` renders a compact header (with the desktop nav fallback) plus a fixed bottom tab bar that is mobile-only (`sm:hidden`) and covers the four public routes (Dashboard, Schedule, Roster, Leaderboard).
- Safe-area spacing tokens (`safe-top`, `safe-bottom`, `safe-left`, `safe-right`) are defined in `tailwind.config.js`; they resolve to `env(safe-area-inset-*)` and require `viewport-fit=cover` in the viewport meta (`resources/views/app.blade.php`) to take effect.
- The install prompt (`resources/js/Components/InstallPrompt.jsx`) floats above the bottom tab bar on mobile (`bottom-28`) and reverts to `sm:bottom-6` on desktop so it never blocks the tab bar.

## Admin Team Management

- Filament is the admin CMS foundation at `/admin`, configured by `App\Providers\Filament\AdminPanelProvider` and guarded by `User::canAccessPanel()` delegating to `User::isAdmin()`.
- Filament resources live under `app/Filament/Resources`; the admin dashboard lives under `app/Filament/Pages` and `app/Filament/Widgets`.
- Temporary legacy Inertia admin workflows live under `app/Http/Controllers/Admin` with routes prefixed `/admin-legacy` and named `admin.*` until later increments replace them.
- `/dashboard` stays reserved for Breeze; `/admin` is the Filament CMS entry point.
- WhatsApp roster text is generated server-side via `App\Team\WhatsAppRosterText` so the copyable text is deterministic and testable.
- Match roster management is a Filament page at `/admin/football-matches/{record}/rosters` (`ManageFootballMatchRosters`) that groups roster entries by role and shows copyable WhatsApp text; the legacy Inertia roster routes (`admin.matches.roster.*`) remain until the final replacement increment.
- Legacy admin Inertia pages live under `resources/js/Pages/Admin`.

## PWA Support

- The public team app is installable as a lightweight PWA. Static PWA assets live directly under `public/`: `manifest.webmanifest`, `sw.js`, `offline.html`, and `public/icons/*`.
- PWA metadata (manifest link, theme color, Apple touch icon, Apple mobile web app tags) is emitted from the Inertia shell in `resources/views/app.blade.php`, not from per-page layouts.
- The service worker is a static `public/sw.js` (no Vite PWA plugin). It is registered from `resources/js/app.jsx` **only in production + secure contexts** (`import.meta.env.PROD && window.isSecureContext`) so it never interferes with Vite HMR in dev.
- The worker caches only stable PWA assets (manifest, offline page, icons) plus same-origin GET responses; navigation requests fall back to `/offline.html` on network failure. Inertia/data responses are intentionally network-first to avoid stale match data.
- Bump the `CACHE_VERSION` constant in `public/sw.js` whenever cached shell assets change.
- Feature tests assert the assets exist on disk and that the shell links to them (the PHPUnit kernel does not serve static files, so disk + content checks are used instead of HTTP status checks).

## Design System

A project-level design system is generated and maintained at `prompter/design-system.md`.

- Generated by the `design-system-generator` skill (`prompter/skills/design-system-generator/`)
- In **wiki mode**, `design-system.md` is the index hub and `prompter/design-system/` holds the interlinked pages: `tokens/`, `components/` (one full contract per component), `patterns/`, `ai-agent-instructions.md`, and `log.md`
- **AI agents generating UI:** read `prompter/design-system/ai-agent-instructions.md` first, then the relevant `components/<name>.md` contract; use only token-referenced styles
- Consult it when building UI components or making styling decisions to ensure consistency
- Regenerate/update it by invoking the `design-system-generator` skill with updated source material
<!-- LOCAL:END -->
