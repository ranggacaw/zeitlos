---
name: {{token-category-slug}}
type: token
category: {{color | typography | spacing | borders | shadows | motion | z-index | dark-mode | effects | sizing}}
status: {{source | inferred | extension}}
updated: {{YYYY-MM-DD}}
---

# {{Token Category}}

> {{One-line description of what this category governs.}}

Used by: {{[[components/...]] · [[components/...]] — components that reference these tokens}}

## Tokens

| Token | Value | Usage | Status |
|-------|-------|-------|--------|
| `{{--token}}` | `{{value}}` | {{semantic usage}} | {{source/inferred}} |

<!-- For color, include a swatch column:
| `{{--token}}` | `{{value}}` | <span style="background:{{value}};width:24px;height:24px;display:inline-block;border-radius:4px;vertical-align:middle"></span> | {{usage}} | {{status}} |
-->

## Contrast (color pages only)
<!-- Omit for non-color categories -->

| Pair | Ratio | WCAG AA | Notes |
|------|-------|---------|-------|
| `{{--surface}}` / `{{--surface-foreground}}` | {{n.n:1}} | {{✅ ≥4.5:1 (text) / ≥3:1 (large/UI) · ❌ fails}} | {{flag failures; suggest a darker/lighter -foreground}} |
<!-- Verify every surface/-foreground pair. Body text ≥ 4.5:1, large text & UI/borders ≥ 3:1. -->

## Notes

- {{Scale rationale, base unit, pairing rules (e.g. every surface token pairs with its `-foreground`), or provenance caveats.}}
- {{Tag any `inferred`/`extension` tokens explicitly — they are not in the source theme.}}
