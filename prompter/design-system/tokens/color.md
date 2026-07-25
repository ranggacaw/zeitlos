---
name: color
type: token
category: color
status: source
updated: 2026-07-25
---

# Color Tokens

Source: `index.css` `:root` semantic shadcn variables. Values are documented as original OKLCH plus derived hex for browsing.

Used by: [[components/button]] · [[components/icon-button]] · [[components/link]] · [[components/input]] · [[components/form-field]] · [[components/alert]] · [[components/card]] · [[components/badge]] · [[components/navbar]] · [[components/sidebar]]

## Tokens

| Token | Source value | Derived hex | Usage | Status |
|-------|--------------|-------------|-------|--------|
| `--background` | `oklch(1.0000 0 0)` | `#ffffff` | App background | source |
| `--foreground` | `oklch(0.1884 0.0128 248.5103)` | `#0f1419` | App text | source |
| `--card` | `oklch(0.9784 0.0011 197.1387)` | `#f7f8f8` | Card surface | source |
| `--card-foreground` | `oklch(0.1884 0.0128 248.5103)` | `#0f1419` | Card text | source |
| `--popover` | `oklch(1.0000 0 0)` | `#ffffff` | Popover surface | source |
| `--popover-foreground` | `oklch(0.1884 0.0128 248.5103)` | `#0f1419` | Popover text | source |
| `--primary` | `oklch(0.6153 0.2518 29.0632)` | `#f80204` | Primary action surface | source |
| `--primary-foreground` | `oklch(1.0000 0 0)` | `#ffffff` | Primary action text | source |
| `--secondary` | `oklch(0.5191 0.2129 29.0381)` | `#c60002` | Secondary action surface | source |
| `--secondary-foreground` | `oklch(1.0000 0 0)` | `#ffffff` | Secondary action text | source |
| `--muted` | `oklch(0.9222 0.0013 286.3737)` | `#e5e5e6` | Muted surface | source |
| `--muted-foreground` | `oklch(0.1884 0.0128 248.5103)` | `#0f1419` | Muted text | source |
| `--accent` | `oklch(0.9392 0.0166 250.8453)` | `#e3ecf6` | Accent surface | source |
| `--accent-foreground` | `oklch(0.6723 0.1606 244.9955)` | `#1e9df1` | Accent text | source |
| `--destructive` | `oklch(0.6188 0.2376 25.7658)` | `#f4212e` | Destructive surface | source |
| `--destructive-foreground` | `oklch(1.0000 0 0)` | `#ffffff` | Destructive text | source |
| `--border` | `oklch(0.9317 0.0118 231.6594)` | `#e1eaef` | Default border | source |
| `--input` | `oklch(0.9809 0.0025 228.7836)` | `#f7f9fa` | Input background | source |
| `--ring` | `oklch(0.6818 0.1584 243.3540)` | `#1da1f2` | Focus ring | source |
| `--chart-1` | `oklch(0.6723 0.1606 244.9955)` | `#1e9df1` | Chart series 1 | source |
| `--chart-2` | `oklch(0.6907 0.1554 160.3454)` | `#00b87a` | Chart series 2 | source |
| `--chart-3` | `oklch(0.8214 0.1600 82.5337)` | `#f7b928` | Chart series 3 | source |
| `--chart-4` | `oklch(0.7064 0.1822 151.7125)` | `#17bf63` | Chart series 4 | source |
| `--chart-5` | `oklch(0.5919 0.2186 10.5826)` | `#e0245e` | Chart series 5 | source |
| `--sidebar` | `oklch(0.9784 0.0011 197.1387)` | `#f7f8f8` | Sidebar surface | source |
| `--sidebar-foreground` | `oklch(0.1884 0.0128 248.5103)` | `#0f1419` | Sidebar text | source |
| `--sidebar-primary` | `oklch(0.6723 0.1606 244.9955)` | `#1e9df1` | Sidebar selected item | source |
| `--sidebar-primary-foreground` | `oklch(1.0000 0 0)` | `#ffffff` | Sidebar selected text | source |
| `--sidebar-accent` | `oklch(0.9392 0.0166 250.8453)` | `#e3ecf6` | Sidebar hover/accent surface | source |
| `--sidebar-accent-foreground` | `oklch(0.6723 0.1606 244.9955)` | `#1e9df1` | Sidebar accent text | source |
| `--sidebar-border` | `oklch(0.9271 0.0101 238.5177)` | `#e1e8ed` | Sidebar border | source |
| `--sidebar-ring` | `oklch(0.6818 0.1584 243.3540)` | `#1da1f2` | Sidebar focus ring | source |

## Contrast

| Pair | Ratio | WCAG AA | Notes |
|------|-------|---------|-------|
| `--background` / `--foreground` | 18.51:1 | Pass | Body text passes. |
| `--card` / `--card-foreground` | 17.40:1 | Pass | Body text passes. |
| `--popover` / `--popover-foreground` | 18.51:1 | Pass | Body text passes. |
| `--primary` / `--primary-foreground` | 4.20:1 | Partial | Passes large text/UI; fails normal text. |
| `--secondary` / `--secondary-foreground` | 6.17:1 | Pass | Body text passes. |
| `--muted` / `--muted-foreground` | 14.71:1 | Pass | Body text passes. |
| `--accent` / `--accent-foreground` | 2.46:1 | Fail | Fails text and UI contrast; verify before using for text. |
| `--destructive` / `--destructive-foreground` | 4.11:1 | Partial | Passes large text/UI; fails normal text. |
| `--sidebar` / `--sidebar-foreground` | 17.40:1 | Pass | Body text passes. |
| `--sidebar-primary` / `--sidebar-primary-foreground` | 2.94:1 | Fail | Slightly below UI threshold; avoid small white text. |
| `--sidebar-accent` / `--sidebar-accent-foreground` | 2.46:1 | Fail | Same caveat as accent pair. |

## Notes

- Preserve the shadcn semantic pair vocabulary from source. Do not rename to a custom `--ds-*` scale unless the theme is intentionally migrated.
- Derived hex values are computed from the source OKLCH values for documentation only.
