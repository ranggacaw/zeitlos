---
name: spacing
type: token
category: spacing
status: source
updated: 2026-07-24
---

# Spacing Tokens

Source: `index.css` Tailwind v4 `@theme inline` base spacing variable.

Used by: [[components/button]] · [[components/input]] · [[components/card]] · [[components/container]] · [[components/stack]]

## Tokens

| Token | Value | Usage | Status |
|-------|-------|-------|--------|
| `--spacing` | `0.25rem` | Base spacing unit; equivalent to 4px when root font size is 16px | source |

## Notes

- The source defines only the base unit. Component spacing below is inferred as Tailwind multiples of `--spacing` and should be ratified against real component markup.
