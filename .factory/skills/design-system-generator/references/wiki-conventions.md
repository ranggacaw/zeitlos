# Wiki Conventions

How the **wiki output mode** structures a design system as an interlinked, AI-agent-consumable
knowledge base (Karpathy LLM Wiki pattern, adapted to a fixed design-system schema).

> This is distinct from a general project knowledge wiki (e.g. a `cerebro-wiki`). This wiki is
> a domain-specific, on-disk artifact for **UI generation** — tokens, components, and patterns.

---

## Directory structure

```
<output-dir>/design-system.md          # INDEX HUB — backward-compatible entry point
<output-dir>/design-system/
├── index.md                           # catalog of every page (content-oriented)
├── log.md                             # append-only chronological record
├── ai-agent-instructions.md           # operating contract for generating UI
├── tokens/
│   ├── color.md  typography.md  spacing.md  borders.md
│   ├── shadows.md  motion.md  z-index.md  dark-mode.md
│   └── effects.md  sizing.md              # only when source uses them (SKILL §2.9)
├── components/
│   └── <component-slug>.md            # one full contract per component
└── patterns/
    └── <pattern-slug>.md              # interaction/behavior rules
```

**Backward compatibility (REQUIRED):** keep `design-system.md` as the index hub. Existing
references (CLAUDE.md, AGENTS.md, skill Step 6) point at `prompter/design-system.md` — do not
break them. `design-system.md` may either BE the index or be a thin pointer to
`design-system/index.md`. Default: make `design-system.md` the index hub and mirror it at
`design-system/index.md`, or have `design-system.md` link into the wiki dir.

---

## Frontmatter schema

Every page carries YAML frontmatter so Obsidian Dataview and lint tooling can query it.

| Field | Pages | Values |
|-------|-------|--------|
| `name` | all | kebab-case slug, unique |
| `type` | all | `index` / `component` / `token` / `pattern` / `contract` |
| `status` | all | `source` (read from input) · `inferred` (synthesized) · `extension` (added beyond source) |
| `id` | component | `CMP.NN` stable identifier |
| `tokens` | component | list of token slugs the component references |
| `variants`, `sizes` | component | lists |
| `category` | token | `color` / `typography` / `spacing` / … |
| `updated` | all | ISO date |

### Provenance is load-bearing
`status` drives the AI-agent **source-fidelity rule**: agents must flag any `inferred`/`extension`
token or component before relying on it. Never silently present synthesized content as ground truth.

---

## Cross-linking

- Use Obsidian-style `[[wikilinks]]` so the wiki renders in Obsidian's graph view.
- Component pages link to **every token page they reference** and any relevant pattern page.
- Token pages link back to components that use them ("Used by:").
- A `[[link]]` to a not-yet-created page is fine — it marks a page worth writing (an orphan to fill).

---

## Operations

### Ingest
Trigger: new UI source (HTML / React / screenshots / CSS / URL) to fold in.
1. Read the source; extract tokens (Step 2) and component contracts (Step 3).
2. For each token category → create/update its `tokens/*.md` page.
3. For each component → create/update `components/<slug>.md` using the component template;
   set `status: source`. Synthesize missing-but-expected components as `status: inferred`.
4. Create/update `patterns/*.md` for interaction rules found.
5. Update `index.md` (add/adjust rows) and refresh cross-links.
6. Append an entry to `log.md` (`## [YYYY-MM-DD] ingest | <source>`), listing pages touched.
A single source may touch 10–15 pages — that is expected.

### Render (optional)
Trigger: a human wants a browsable view of the system.
Generate `design-system.html` (sibling to `design-system.md`) from the wiki pages using
`assets/html-reference-template.html`: token pages → `:root{}` CSS vars (+ `dark-mode.md` →
`[data-theme="dark"]`); each component page → a Preview/Specification tabbed card (live markup
in Preview, the same markup HTML-escaped in the Spec pane's `<pre>`); `ai-agent-instructions.md`
→ §7; `index.md` → hero/metadata; this log → §8 changelog. §1 is a tweakcn-style Theme
Showcase (Dashboard / Application / Typography / Colors panes) — token-driven demo content,
not wiki-sourced; drop panes/rows whose tokens the system lacks. It is a **derived view** — never a
source of truth, never read by the agent contract. Re-run after an ingest to refresh it; append
an entry to `log.md`.

### Query
Trigger: an agent (or human) needs to generate/understand UI.
1. Read `ai-agent-instructions.md`, then `index.md`.
2. Drill into the relevant `components/*.md` and the token pages it links.
3. Generate token-referenced code per the output contract.
4. A genuinely useful answer (a new composition, a comparison) can be filed back as its own page.

### Lint (LIGHT — fixed-schema, not open-ended discovery)
Run on request or after a batch ingest. Report (don't auto-delete):
- **Undefined token reference** — a component's `tokens:` or code cites a token absent from any token page.
- **Unused token** — a token defined but referenced by no component.
- **Missing component page** — an `[[components/x]]` link with no file, or a component named in source with no page.
- **Broken cross-link** — a `[[link]]` whose target doesn't exist (other than intentional stubs).
- **Stale provenance** — `status: inferred` content that a newer source now confirms (promote to `source`).

Skip the heavy Karpathy machinery (cross-source contradiction synthesis, open-ended concept
discovery) — a design system has a fixed schema, so it isn't needed.

---

## Token vocabulary

Default to the vocabulary the **source** uses:
- **shadcn/ui** sources → semantic pairs: `--primary` / `--primary-foreground`, `--card`,
  `--popover`, `--muted`, `--secondary`, `--accent`, `--border`, `--ring`, `--sidebar-*`, `--chart-*`.
- **Tailwind-scale** sources → `--ds-*` with 50–950 scales (the legacy single-file template model).

Do not translate between vocabularies unless asked — fidelity to source beats normalization.

---

## Templates

Page templates live in `assets/wiki/`:
`index-template.md` · `component-template.md` · `token-template.md` · `pattern-template.md` ·
`ai-agent-instructions-template.md` · `log-template.md`.
