---
name: typography
type: token
category: typography
status: source
updated: 2026-07-24
---

# Typography Tokens

Source: `index.css` font and tracking custom properties.

Used by: [[components/button]] · [[components/link]] · [[components/input]] · [[components/card]] · [[components/navbar]]

## Tokens

| Token | Value | Usage | Status |
|-------|-------|-------|--------|
| `--font-sans` | `Open Sans, sans-serif` | Default UI and body font stack | source |
| `--font-serif` | `Georgia, serif` | Serif fallback for editorial content | source |
| `--font-mono` | `Menlo, monospace` | Code and tabular technical content | source |
| `--tracking-normal` | `0em` | Default letter spacing | source |

## Font Loading

No `@font-face`, Google Fonts link, `next/font`, or `@fontsource` import was present in the provided `index.css`. The `Open Sans` family is referenced but loading provenance is not confirmed by this source.

## Notes

- No explicit font-size, weight, or line-height scale was provided in the source CSS. Use the project/Tailwind defaults unless a component source later confirms additional values.
