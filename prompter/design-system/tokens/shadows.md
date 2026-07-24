---
name: shadows
type: token
category: shadows
status: source
updated: 2026-07-24
---

# Shadow Tokens

Source: `index.css` shadow variables.

Used by: [[components/card]] · [[components/navbar]] · [[components/sidebar]]

## Tokens

| Token | Value | Usage | Status |
|-------|-------|-------|--------|
| `--shadow-color` | `rgba(29,161,242,0.15)` | Light-mode shadow color source | source |
| `--shadow-x` | `0px` | Shadow x offset source | source |
| `--shadow-y` | `2px` | Shadow y offset source | source |
| `--shadow-blur` | `0px` | Shadow blur source | source |
| `--shadow-spread` | `0px` | Shadow spread source | source |
| `--shadow-opacity` | `0` | Shadow opacity source | source |
| `--shadow-2xs` | `0px 2px 0px 0px hsl(202.8169 89.1213% 53.1373% / 0.00)` | 2xs elevation | source |
| `--shadow-xs` | `0px 2px 0px 0px hsl(202.8169 89.1213% 53.1373% / 0.00)` | xs elevation | source |
| `--shadow-sm` | `0px 2px 0px 0px hsl(202.8169 89.1213% 53.1373% / 0.00), 0px 1px 2px -1px hsl(202.8169 89.1213% 53.1373% / 0.00)` | sm elevation | source |
| `--shadow` | `0px 2px 0px 0px hsl(202.8169 89.1213% 53.1373% / 0.00), 0px 1px 2px -1px hsl(202.8169 89.1213% 53.1373% / 0.00)` | Default elevation | source |
| `--shadow-md` | `0px 2px 0px 0px hsl(202.8169 89.1213% 53.1373% / 0.00), 0px 2px 4px -1px hsl(202.8169 89.1213% 53.1373% / 0.00)` | md elevation | source |
| `--shadow-lg` | `0px 2px 0px 0px hsl(202.8169 89.1213% 53.1373% / 0.00), 0px 4px 6px -1px hsl(202.8169 89.1213% 53.1373% / 0.00)` | lg elevation | source |
| `--shadow-xl` | `0px 2px 0px 0px hsl(202.8169 89.1213% 53.1373% / 0.00), 0px 8px 10px -1px hsl(202.8169 89.1213% 53.1373% / 0.00)` | xl elevation | source |
| `--shadow-2xl` | `0px 2px 0px 0px hsl(202.8169 89.1213% 53.1373% / 0.00)` | 2xl elevation | source |

## Notes

- The named elevation shadows have `0.00` alpha in source, so they render transparent unless the source theme changes opacity.
- Dark mode changes `--shadow-color` to `rgba(29,161,242,0.25)` but keeps named shadow alpha at `0.00`.
