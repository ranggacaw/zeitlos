---
name: design-system-generator
description: Generate a structured design system from HTML pages, React components, or screenshots — as a single Markdown doc OR an interlinked, AI-agent-consumable wiki (Karpathy LLM Wiki pattern). Extracts colors, typography, spacing, shadows, borders, breakpoints, plus full per-component contracts (variants, sizes, states, props, anatomy, accessibility, do/don't, literal code) and interaction patterns. Use when creating a design system from existing UI, auditing design consistency, bootstrapping design tokens, or producing a design system an AI agent can generate UI from.
---

# Design System Generator

You are a senior design systems engineer who reverse-engineers production UIs into clean, reusable design tokens. You favor precise, evidence-based extraction over guesswork and produce documentation a frontend team can adopt immediately.

Analyze UI input (HTML, React, screenshots, or live URLs) and produce a comprehensive design system document with extracted design tokens and component patterns.

## Quick Start

1. **DETERMINE INPUT TYPE** — Ask user for source material (files, URLs, or screenshots)
2. **COLLECT INPUT** — Read files, scrape URLs, or analyze screenshots
3. **EXTRACT TOKENS** — Pull colors, typography, spacing, shadows, borders, radii, breakpoints
4. **IDENTIFY COMPONENTS** — Catalog reusable UI components and their variants
5. **GENERATE DOCUMENT** — Output as a Wiki (interlinked pages, recommended for AI agents) or a single Markdown file; templates in `assets/wiki/` and `assets/design-system-template.md`
6. **SAVE OUTPUT** — Write to user-specified path or default `prompter/design-system.md` (+ `prompter/design-system/` in wiki mode)
7. **WIRE UP** — Update AGENTS.md / CLAUDE.md so agents know the design system exists (Step 6, REQUIRED)

---

## Step 0: Determine Input Type (REQUIRED)

Present the following options:

```
What source material should I analyze?

1. **HTML file(s)** — Static HTML pages with inline/linked CSS
2. **React component(s)** — JSX/TSX files with CSS/Tailwind/styled-components
3. **Live URL** — Scrape a live webpage for design tokens
4. **Screenshot(s)** — Analyze visual design from images
5. **CSS/SCSS file(s)** — Extract tokens directly from stylesheets
6. **Tailwind config** — Parse tailwind.config.js/ts for design tokens
7. **Mixed** — Combination of the above

Please select (1-7) or describe your input:
```

Wait for user response before proceeding.

---

## Step 1: Collect & Parse Input

### For HTML Files
1. Read the HTML file(s) with the `Read` tool
2. Extract all `<style>` blocks and inline `style` attributes
3. Identify linked stylesheets via `<link rel="stylesheet">` and read those files
4. Note all CSS custom properties (`--var-name`) declarations

### For React Components
1. Read JSX/TSX file(s)
2. Detect styling approach:
   - **CSS Modules** → read associated `.module.css` files
   - **Tailwind CSS** → read `tailwind.config.js/ts`, catalog utility classes used
   - **Styled-components/Emotion** → extract template literals
   - **Inline styles** → extract style objects
   - **CSS-in-JS (other)** → extract theme objects
3. Identify component props that affect visual appearance (variant, size, color)

### For Live URLs
1. Fetch the page with the `WebFetch` tool (returns page content as markdown).
   Note: only public http(s) URLs work; local/private addresses are blocked.
2. If a dedicated scrape/branding tool is available in the session, prefer it for
   richer HTML/brand extraction; otherwise parse the fetched content as HTML input.
3. If the URL cannot be fetched, ask the user to paste the HTML/CSS or provide a screenshot.

### For Screenshots
1. Analyze the image for visual design elements
2. Extract approximate color values using visual analysis
3. Identify typography patterns (relative sizes, weights)
4. Note spacing patterns and layout structure
5. Catalog visible UI components

### For CSS/SCSS Files
1. Read the stylesheet(s) directly
2. Parse CSS custom properties, SCSS variables, mixins
3. Extract all token-relevant declarations

### For Tailwind Config
1. Read `tailwind.config.js` or `tailwind.config.ts`
2. Extract `theme.extend` and base `theme` values
3. Map Tailwind tokens to design system categories

---

## Step 2: Extract Design Tokens

> **Accuracy rule:** Never invent exact token values. Only emit a precise hex/px/HSL
> when it is directly read from source code or mathematically derived from a known value.
> For screenshots and other visual-only inputs, tag every estimated value with `(approx.)`
> and recommend verification. Omit HSL annotations unless computed from a confirmed hex.

For detailed extraction patterns per CSS property, see [extraction-patterns.md](references/extraction-patterns.md).

Extract tokens in this order of priority:

### 2.1 Colors
- Background colors, text colors, border colors
- Group into semantic categories: `primary`, `secondary`, `accent`, `neutral`, `success`, `warning`, `error`, `info`
- Identify color scales (50–950 shades) when present
- Extract opacity/alpha variants
- Note dark mode / alternate theme colors if detected
- **Verify contrast** for every surface/`-foreground` (or background/text) pair: compute the
  ratio and check WCAG AA — body text ≥ 4.5:1, large text & UI/borders ≥ 3:1. Record results
  in the color token page's Contrast table and flag any failing pair (do not silently ship it).

### 2.2 Typography
- Font families (heading, body, mono)
- Font sizes (map to scale: xs, sm, base, lg, xl, 2xl, etc.)
- Font weights used
- Line heights
- Letter spacing
- Text transform patterns
- Font loading & provenance — `@font-face` blocks, Google Fonts `<link>`s, `next/font` /
  `@fontsource` imports; record each family's source and full fallback stack verbatim

### 2.3 Spacing
- Padding and margin values used
- Gap values in flex/grid layouts
- Map to a consistent scale (0, 1, 2, 3, 4, 5, 6, 8, 10, 12, 16, 20, 24...)
- Identify base unit (commonly 4px or 8px)

### 2.4 Layout & Breakpoints
- Container max-widths
- Media query breakpoints (sm, md, lg, xl, 2xl)
- Grid column counts and gutter widths

### 2.5 Borders & Radii
- Border widths
- Border styles
- Border radius values (none, sm, md, lg, full)

### 2.6 Shadows
- Box shadow definitions
- Map to elevation scale (sm, md, lg, xl)
- Note any colored or inset shadows

### 2.7 Transitions & Animation
- Transition durations
- Easing functions
- Named animations/keyframes

### 2.8 Z-Index Scale
- All z-index values used
- Assign semantic names (dropdown, modal, tooltip, etc.)

### 2.9 Other (opacity, gradients, blur, sizing)
- Opacity scale values
- Gradient definitions (document stops as references to color tokens)
- Blur / backdrop-filter values (glassmorphism surfaces)
- Sizing tokens (icon sizes, control heights) when the source uses a consistent scale
- Emit a token category page only when the source actually uses these — never scaffold empty categories

---

## Step 3: Identify Components (Full Contract)

Catalog reusable UI patterns found in the input. For an AI agent to *generate* UI, a
component needs a complete contract — not a one-line description. Extract this schema for
each component (it is the actual cure for "the doc only covers color"):

- **Name** — PascalCase identifier; **id** — stable `CMP.NN`
- **Purpose** — one line: what it's for
- **Variants** — visual/behavioral variations, each mapped to its surface + text tokens
- **Sizes** — size options with height/padding; mark the default
- **States** — default, hover, focus, active, disabled, loading (those that apply)
- **Props** — prop · type · description · default
- **Anatomy** — the parts and how tokens lay them out (gaps, radius, focus)
- **Accessibility** — role/element, keyboard, focus, ARIA requirements
- **Do / Don't** — usage rules
- **Code** — literal markup (token-referenced): keep both a copy-anywhere HTML/CSS form and
  the project's framework form (React/Tailwind/shadcn) unless the project standardizes on one
- **Tokens used** — token slugs the component references (drives cross-linking + lint)
- **Provenance** — `source` if read from input, `inferred` if synthesized from existing tokens

This schema is realized directly by the wiki component page (`assets/wiki/component-template.md`).

### Production Coverage Checklist (REQUIRED)

A production-grade system needs more than what a single source page exposes. After
cataloging components found in the source, check the input against this set. For each
member: document it as `status: source` if present, synthesize a `status: inferred`
stub if it can be composed from existing tokens, or list it under **"Not covered
(absent from source)"** in the index — never leave a silent gap.

- **Actions:** Button, IconButton, Link, ButtonGroup
- **Inputs:** Input, Textarea, Select, Combobox, Checkbox, Radio, Toggle/Switch,
  Slider, DatePicker, FileUpload, FormField (label + help + error)
- **Feedback:** Alert, Toast/Snackbar, Banner, ProgressBar, Spinner, Skeleton,
  EmptyState, ErrorState
- **Overlays:** Modal/Dialog, Drawer/Sheet, Dropdown, Popover, Tooltip, ContextMenu
- **Data display:** Table, List, Card, Badge, Tag, Avatar, Tabs, Accordion,
  Pagination, Stepper
- **Navigation:** Navbar, Sidebar, Breadcrumb, Menu
- **Layout & structure:** Header/PageHeader, Footer, Container, Stack, Grid,
  Divider/Separator, Section/Hero

Report the coverage tally in the Step 5 summary, e.g. `🧩 Components: 9 source / 6 inferred / 4 not covered`.

---

## Step 4: Generate Design System Document

1. Pick the output format (see **Output Formats** below) and read its template(s):
   wiki → `assets/wiki/*` + [wiki-conventions.md](references/wiki-conventions.md);
   single file → `assets/design-system-template.md`.
2. Fill in all extracted tokens and component documentation
3. Apply these rules:
   - **Deduplicate** — Merge identical or near-identical values
   - **Normalize** — Convert all color values to hex (with HSL in comments)
   - **Scale** — Organize values into logical scales where possible
   - **Name** — Apply semantic names to raw values
   - **Omit empty sections** — Remove sections with no extracted tokens

### Output Formats

Offer the user a choice of output format:

```
Which output format would you like?

1. **Wiki** (recommended for AI agents) — Interlinked Markdown wiki: one full
   contract page per component, token pages, interaction patterns, an AI-agent
   operating contract, and an index hub. Best when the design system will be fed
   to an AI agent to generate UI.
2. **Markdown (single file)** — One structured document. Lightweight, diff-friendly.
3. **HTML Reference** (optional) — Self-rendering, interactive single-file HTML page:
   live component previews, theme toggle, and copy buttons. **Best generated *after* the
   Wiki** so it sources tokens + component markup straight from the wiki pages and stays
   consistent. Can also run standalone from extracted tokens.
4. **All** — Generate the Wiki + the HTML Reference (if a wiki exists)

Please select (1-4), or press Enter for Wiki:
```

### Wiki Output (recommended for AI agents)

Produce an interlinked Markdown wiki instead of one flat file. This is the format that
actually fixes "the doc only covers color" — each component gets its own full contract page.

1. Read [wiki-conventions.md](references/wiki-conventions.md) for the directory layout,
   frontmatter schema, cross-linking, provenance rules, and the Ingest/Query/Lint operations.
2. Use the templates in `assets/wiki/`:
   - `index-template.md` → `design-system/index.md` (the catalog)
   - `component-template.md` → one `design-system/components/<slug>.md` per component (Step 3 contract)
   - `token-template.md` → `design-system/tokens/<category>.md`
   - `pattern-template.md` → `design-system/patterns/<slug>.md` (interaction/behavior rules)
   - `ai-agent-instructions-template.md` → `design-system/ai-agent-instructions.md`
   - `log-template.md` → `design-system/log.md`
3. **Backward compatibility (REQUIRED):** keep `design-system.md` as the index hub so existing
   references don't break. Default output root: `prompter/design-system.md` (hub) +
   `prompter/design-system/` (wiki pages).
4. Cross-link with Obsidian-style `[[wikilinks]]`: component pages link to the token pages they
   use; token pages list the components that use them.
5. Tag provenance in every page's frontmatter: `status: source | inferred | extension`. Mark
   synthesized components/tokens `inferred` — never present them as ground truth.
6. Default to the source's token vocabulary (shadcn pairs vs `--ds-*` scales) — see conventions.
7. Append an ingest entry to `log.md`.

If the user re-runs the skill on updated UI, treat it as an **Ingest** operation (update
existing pages + cross-links + log), not a from-scratch regeneration.

### Markdown Output (single file)
- Use the template from `assets/design-system-template.md`
- Include color swatches using inline HTML: `<span style="background:COLOR;width:24px;height:24px;display:inline-block;border-radius:4px;vertical-align:middle"></span>`
- Save to user-specified path or `prompter/design-system.md`

### HTML Reference Output (optional)

A self-contained, interactive single-file HTML page that renders the design system with its
own tokens: a sticky scroll-spy sidebar, a light/dark theme toggle, a filter box, a
**tweakcn-style Theme Showcase** (composed Dashboard / Application / Typography / Colors
previews rendered live from the tokens — reference: tweakcn.com/editor/theme), token
tables with live swatches, and per-component **spec cards with Preview / Specification tabs**.
This is a **derived view**, not a source of truth — never the only output, and never something
the AI-agent contract reads from. It is for humans to browse.

**Page structure** (each section maps to wiki content — this mapping IS the consistency contract):

| Page section | Source | Notes |
|--------------|--------|-------|
| Hero + metadata grid | `index.md` | system name, version, source, platform |
| §1 Theme Showcase | tokens + live component classes | tweakcn-style composed previews (Dashboard / Application / Typography / Colors); demo content is placeholder data, fully token-driven — drop panes/rows whose tokens the source lacks; fill the specimen's `{{size}}` labels from the real type scale |
| §2 Design Tokens (tables) | `tokens/*.md` | one `h3.sub` per category, one row per token; color rows get a live `.swatch` |
| §3 Component Library (cards) | `components/<slug>.md` | one `<article class="comp">` per component |
| §7 AI Agent Instructions | `ai-agent-instructions.md` | one `.ai-rule` per operating rule |
| §8 Versioning & Changelog | `log.md` | one row per ingest/update |

**Recommended flow — generate after the Wiki**, because it sources content *from the wiki
pages* (not by re-extracting the original UI), which is what keeps it consistent.

**Pick the source — in priority order:**

1. **From the wiki (BEST — use when `design-system/` exists):**
   - Token values from `tokens/*.md` → `:root {}` (one CSS custom property per token);
     `tokens/dark-mode.md` → `html[data-theme="dark"] {}` (drop that block if no dark theme).
   - Each `components/<slug>.md` → one component card. The component's `## Code` block goes
     in **two places, not duplicated raw**: the live markup renders in the **Preview pane**;
     the same markup, **HTML-escaped** (escape `< > &`), goes inside the **Specification
     pane's `<pre>`** copy block so it shows as text, not a second render. The contract's
     variants/sizes/states/props/a11y/do-don't fill the rest of the Spec pane.
   - One sidebar `<nav>` link per section and per component card (href = the element `id`).
2. **From an existing single-file `design-system.md` (flat doc, no wiki):**
   - Parse the doc as the source: token tables → `:root {}` vars (+ dark tokens → dark block);
     each component's code block → a card, exactly as in (1).
   - **Caveat:** single-file docs usually carry full token tables but often lack per-component
     markup, so Preview panes may be thin. Emit cards only where markup exists; for richer
     previews, recommend generating the Wiki first.
3. **Standalone (no existing doc):** works from the Step 2/3 extracted tokens and contracts.
   Recommend generating the Wiki first, but proceed from extracted data if the user declines.

Then, regardless of source:

- Use the template at `assets/html-reference-template.html`. Its CSS and JS chrome (theme
  toggle, component tabs, copy buttons, scroll-spy, filter, layout) is **complete and must
  not be templatized** — fill only token values, component markup, and the metadata/changelog
  text. Delete the filling-instructions comment block from the generated output.
- **Omit empty sections and never invent values.** Drop any token category, component, or
  whole section the source doesn't cover (the template's shadcn-style charts/sidebar tokens
  and the AI-rules are examples, not requirements). Tag `inferred`/`extension` tokens honestly
  via the "source gap" callout — same provenance rule as the wiki.
- Save to `prompter/design-system.html` (sibling to `design-system.md`; do not clash with
  `design-system/index.md`). In wiki mode, append an entry to `design-system/log.md`.

---

## Step 5: Save & Report

After generating the document:

1. Save to the specified output path (default: `prompter/design-system.md`)
2. Update agent instruction files — `AGENTS.md` and `CLAUDE.md` (see Step 6 below)
3. Print a summary:

```
✅ Design System Generated

📄 Output: <file-path or wiki dir>
🎨 Colors: <count> tokens extracted
🔤 Typography: <count> tokens extracted
📐 Spacing: <count> tokens extracted
🧩 Components: <count> contract pages (<count> source / <count> inferred)
🔗 Patterns: <count> · 📑 Pages written/updated: <count>   (wiki mode)
📊 Source: <input-type description>

Next steps:
- Review and adjust token names for your conventions
- Open the wiki in Obsidian to browse [[links]] and the graph view (wiki mode)
- Verify any `status: inferred` components against your real UI
- Import tokens into your project's theme configuration
```

---

## Step 6: Update Agent Instruction Files (REQUIRED)

After saving the design system document, update the project's agent instruction files so AI
assistants know the design system exists and where to find it. This covers **both** conventions:

- `AGENTS.md` (root) and `prompter/AGENTS.md` — see 6.1 / 6.2
- `CLAUDE.md` (root) and `prompter/CLAUDE.md` — see 6.4

**Update only — never create.** Touch a file only if it already exists; if it doesn't, skip it
silently (see 6.5). A project may have AGENTS.md, CLAUDE.md, both, or neither — update whichever
are present.

### 6.1 Update root `AGENTS.md`

Check if `AGENTS.md` exists in the project root. If it does:

1. Look for an existing "Design System" section — if found, update it; if not, add it.
2. Also update the `prompter/` directory tree if one is shown (add `design-system.md` to it).

Add or update this block (place it near the Prompter Workflow or Output Location section):

```markdown
## Design System

A project-level design system is generated and maintained at `prompter/design-system.md`.

- Generated by the `design-system-generator` skill (`prompter/skills/design-system-generator/`)
- In **wiki mode**, `design-system.md` is the index hub and `prompter/design-system/` holds the
  interlinked pages: `tokens/`, `components/` (one full contract per component), `patterns/`,
  `ai-agent-instructions.md`, and `log.md`
- **AI agents generating UI:** read `prompter/design-system/ai-agent-instructions.md` first, then
  the relevant `components/<name>.md` contract; use only token-referenced styles
- Consult it when building UI components or making styling decisions to ensure consistency
- Regenerate/update it by invoking the `design-system-generator` skill with updated source material
```

If the root `AGENTS.md` has a directory tree like:

```
prompter/
├── project.md
└── ...
```

Add `design-system.md` to it:

```
prompter/
├── project.md              # Project context (edit this!)
├── design-system.md        # Generated design system (see Design System section)
└── ...
```

### 6.2 Update `prompter/AGENTS.md`

Check if `prompter/AGENTS.md` exists. If it does:

1. In the **Directory Structure** section, add `design-system.md` to the `prompter/` tree if not already present:

```
prompter/
├── project.md              # Project conventions
├── design-system.md        # Generated design system (colors, typography, spacing, components)
├── specs/
...
```

2. In the **Before Any Task** > **Context Checklist**, add this entry if not already present:

```markdown
- [ ] Read `prompter/design-system.md` for UI/styling decisions (if task involves frontend)
```

3. After the checklist, add or update a **Design System** block if not already present:

```markdown
**Design System:**

The project design system lives at `prompter/design-system.md`. It is generated by the `design-system-generator` skill and contains design tokens (colors, typography, spacing, borders, shadows, breakpoints) and component patterns.

- Consult it before building or modifying UI components to stay consistent with established tokens
- Regenerate it with the `design-system-generator` skill when the visual design changes significantly
```

### 6.3 Update `CLAUDE.md` files (if present)

Some projects use `CLAUDE.md` instead of (or alongside) `AGENTS.md`. Apply the same rule to
both **root `CLAUDE.md`** and **`prompter/CLAUDE.md`** if they exist:

1. Look for an existing "Design System" section — if found, update it; if not, add it.
2. If the file shows a `prompter/` directory tree, add `design-system.md` to it.

Add or update the same block used for `AGENTS.md` (place it near a "Pre Implementation",
"Design System", or output-location section if one exists):

```markdown
## Design System

A project-level design system is generated and maintained at `prompter/design-system.md`.

- Generated by the `design-system-generator` skill (`prompter/skills/design-system-generator/`)
- In **wiki mode**, `design-system.md` is the index hub and `prompter/design-system/` holds the
  interlinked pages: `tokens/`, `components/` (one full contract per component), `patterns/`,
  `ai-agent-instructions.md`, and `log.md`
- **AI agents generating UI:** read `prompter/design-system/ai-agent-instructions.md` first, then
  the relevant `components/<name>.md` contract; use only token-referenced styles
- Consult it when building UI components or making styling decisions to ensure consistency
- Regenerate/update it by invoking the `design-system-generator` skill with updated source material
```

If a "Design System" section already exists (e.g. the project CLAUDE.md already references
`prompter/design-system.md`), merge into it rather than duplicating — keep any project-specific
lines the user added.

### 6.4 Skip gracefully if files don't exist

If any of these files (`AGENTS.md`, `prompter/AGENTS.md`, `CLAUDE.md`, `prompter/CLAUDE.md`) does
not exist in the target project, skip that file silently — do not create it.

---

## Edge Cases

- **Insufficient input**: If very little design information is extractable, note gaps and suggest what the user should provide additionally
- **Conflicting values**: When similar but not identical values exist (e.g., `#333` and `#2d2d2d`), consolidate and note the original values
- **No components found**: If input is pure CSS variables or a config file, skip the Components section
- **Screenshot-only input**: Mark all extracted values as "approximate" and recommend verification

---

## Resources

- **Wiki conventions**: [wiki-conventions.md](references/wiki-conventions.md) — directory layout, frontmatter schema, cross-linking, provenance, and the Ingest/Query/Lint operations for wiki mode
- **Wiki templates**: `assets/wiki/` — `index-`, `component-`, `token-`, `pattern-`, `ai-agent-instructions-`, and `log-template.md`
- **Single-file template**: [design-system-template.md](assets/design-system-template.md) — flat Markdown output template
- **HTML reference template**: [html-reference-template.html](assets/html-reference-template.html) — interactive single-file HTML output (optional; best generated from the wiki)
- **Extraction patterns**: [extraction-patterns.md](references/extraction-patterns.md) — CSS property-to-token mapping rules and regex patterns
