---
name: card
type: component
id: CMP.07
status: inferred
variants: [default, muted]
sizes: [md]
tokens: [--card, --card-foreground, --muted, --muted-foreground, --border, --radius-lg, --shadow]
updated: 2026-07-24
---

# Card

Group related content on a raised or bounded surface.

Related: [[color]] · [[borders]] · [[shadows]] · [[spacing]]

## Variants

| Variant | Surface token | Text token | Use for |
|---------|---------------|------------|---------|
| `default` | `--card` | `--card-foreground` | Standard content grouping |
| `muted` | `--muted` | `--muted-foreground` | Low-emphasis panel |

## States

Default, hoverable, selected, and disabled apply when cards are interactive.

## Props

| Prop | Type | Description | Default |
|------|------|-------------|---------|
| `variant` | union | Surface treatment | `default` |
| `interactive` | boolean | Enables hover/focus affordance | `false` |

## Anatomy

```text
card
  header
  content
  footer
  radius: --radius-lg
  border: --border
  optional shadow: --shadow
```

## Accessibility

- Use semantic headings inside card headers.
- If the whole card is clickable, expose one clear interactive target.

## Do / Don't

| Do | Don't |
|----|-------|
| Use cards to group related content. | Nest many cards when simple sections suffice. |

## Code

```html
<section class="card"><h2>Next match</h2><p>Saturday, 15:00</p></section>
```

```css
.card { background: var(--card); color: var(--card-foreground); border-color: var(--border); border-radius: var(--radius-lg); box-shadow: var(--shadow); }
```

```tsx
<Card><CardHeader><CardTitle>Next match</CardTitle></CardHeader><CardContent>Saturday, 15:00</CardContent></Card>
```
