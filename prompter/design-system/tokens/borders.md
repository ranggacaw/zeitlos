---
name: borders
type: token
category: borders
status: source
updated: 2026-07-24
---

# Border And Radius Tokens

Source: `index.css` radius, border, input, and ring variables.

Used by: [[components/button]] · [[components/icon-button]] · [[components/input]] · [[components/card]] · [[components/badge]] · [[patterns/focus-ring]]

## Tokens

| Token | Value | Usage | Status |
|-------|-------|-------|--------|
| `--radius` | `1.3rem` | Base corner radius | source |
| `--radius-sm` | `calc(var(--radius) - 4px)` | Small radius alias | source |
| `--radius-md` | `calc(var(--radius) - 2px)` | Medium control radius | source |
| `--radius-lg` | `var(--radius)` | Large surface radius | source |
| `--radius-xl` | `calc(var(--radius) + 4px)` | Extra-large surface radius | source |
| `--border` | `oklch(0.9317 0.0118 231.6594)` | Default border color | source |
| `--input` | `oklch(0.9809 0.0025 228.7836)` | Input background/border context | source |
| `--ring` | `oklch(0.6818 0.1584 243.3540)` | Focus ring color | source |

## Notes

- Border width is not specified as a custom property. Use the framework default `1px` only where the component implementation already does so.
