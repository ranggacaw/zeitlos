---
name: dark-mode
type: token
category: dark-mode
status: source
updated: 2026-07-25
---

# Dark Mode Tokens

Source: `index.css` `.dark` overrides.

Used by: [[components/button]] · [[components/card]] · [[components/input]] · [[components/sidebar]] · [[patterns/focus-ring]]

## Tokens

| Token | Source value | Derived hex | Usage | Status |
|-------|--------------|-------------|-------|--------|
| `--background` | `oklch(0 0 0)` | `#000000` | Dark app background | source |
| `--foreground` | `oklch(0.9328 0.0025 228.7857)` | `#e7e9ea` | Dark app text | source |
| `--card` | `oklch(0.2097 0.0080 274.5332)` | `#17181c` | Dark card surface | source |
| `--card-foreground` | `oklch(0.8853 0 0)` | `#d9d9d9` | Dark card text | source |
| `--popover` | `oklch(0 0 0)` | `#000000` | Dark popover surface | source |
| `--popover-foreground` | `oklch(0.9328 0.0025 228.7857)` | `#e7e9ea` | Dark popover text | source |
| `--primary` | `oklch(0.6692 0.1607 245.0110)` | `#1c9cf0` | Dark primary action | source |
| `--primary-foreground` | `oklch(1.0000 0 0)` | `#ffffff` | Dark primary text | source |
| `--secondary` | `oklch(0.9622 0.0035 219.5331)` | `#f0f3f4` | Dark secondary action | source |
| `--secondary-foreground` | `oklch(0.1884 0.0128 248.5103)` | `#0f1419` | Dark secondary text | source |
| `--muted` | `oklch(0.2090 0 0)` | `#181818` | Dark muted surface | source |
| `--muted-foreground` | `oklch(0.5637 0.0078 247.9662)` | `#72767a` | Dark muted text | source |
| `--accent` | `oklch(0.1928 0.0331 242.5459)` | `#061622` | Dark accent surface | source |
| `--accent-foreground` | `oklch(0.6692 0.1607 245.0110)` | `#1c9cf0` | Dark accent text | source |
| `--destructive` | `oklch(0.6188 0.2376 25.7658)` | `#f4212e` | Dark destructive surface | source |
| `--destructive-foreground` | `oklch(1.0000 0 0)` | `#ffffff` | Dark destructive text | source |
| `--border` | `oklch(0.2674 0.0047 248.0045)` | `#242628` | Dark border | source |
| `--input` | `oklch(0.3020 0.0288 244.8244)` | `#22303c` | Dark input background | source |
| `--ring` | `oklch(0.6818 0.1584 243.3540)` | `#1da1f2` | Dark focus ring | source |
| `--chart-1` | `oklch(0.6723 0.1606 244.9955)` | `#1e9df1` | Dark chart series 1 | source |
| `--chart-2` | `oklch(0.6907 0.1554 160.3454)` | `#00b87a` | Dark chart series 2 | source |
| `--chart-3` | `oklch(0.8214 0.1600 82.5337)` | `#f7b928` | Dark chart series 3 | source |
| `--chart-4` | `oklch(0.7064 0.1822 151.7125)` | `#17bf63` | Dark chart series 4 | source |
| `--chart-5` | `oklch(0.5919 0.2186 10.5826)` | `#e0245e` | Dark chart series 5 | source |
| `--sidebar` | `oklch(0.2097 0.0080 274.5332)` | `#17181c` | Dark sidebar surface | source |
| `--sidebar-foreground` | `oklch(0.8853 0 0)` | `#d9d9d9` | Dark sidebar text | source |
| `--sidebar-primary` | `oklch(0.6818 0.1584 243.3540)` | `#1da1f2` | Dark sidebar selected item | source |
| `--sidebar-primary-foreground` | `oklch(1.0000 0 0)` | `#ffffff` | Dark sidebar selected text | source |
| `--sidebar-accent` | `oklch(0.1928 0.0331 242.5459)` | `#061622` | Dark sidebar hover/accent | source |
| `--sidebar-accent-foreground` | `oklch(0.6692 0.1607 245.0110)` | `#1c9cf0` | Dark sidebar accent text | source |
| `--sidebar-border` | `oklch(0.3795 0.0220 240.5943)` | `#38444d` | Dark sidebar border | source |
| `--sidebar-ring` | `oklch(0.6818 0.1584 243.3540)` | `#1da1f2` | Dark sidebar ring | source |

## Contrast

| Pair | Ratio | WCAG AA | Notes |
|------|-------|---------|-------|
| `--background` / `--foreground` | 17.24:1 | Pass | Body text passes. |
| `--card` / `--card-foreground` | 12.57:1 | Pass | Body text passes. |
| `--popover` / `--popover-foreground` | 17.24:1 | Pass | Body text passes. |
| `--primary` / `--primary-foreground` | 2.97:1 | Fail | Slightly below UI threshold; avoid white text on dark primary. |
| `--secondary` / `--secondary-foreground` | 16.60:1 | Pass | Body text passes. |
| `--muted` / `--muted-foreground` | 3.88:1 | Partial | Passes large text/UI; fails normal body text. |
| `--accent` / `--accent-foreground` | 6.17:1 | Pass | Body text passes. |
| `--destructive` / `--destructive-foreground` | 4.11:1 | Partial | Passes large text/UI; fails normal text. |
| `--sidebar` / `--sidebar-foreground` | 12.57:1 | Pass | Body text passes. |
| `--sidebar-primary` / `--sidebar-primary-foreground` | 2.83:1 | Fail | Fails UI and text threshold. |
| `--sidebar-accent` / `--sidebar-accent-foreground` | 6.17:1 | Pass | Body text passes. |

## Notes

- The theme uses class-based dark mode: `.dark { ... }`.
- Chart tokens are repeated in `.dark` with the same values as light mode.
