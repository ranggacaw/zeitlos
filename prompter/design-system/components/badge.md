---
name: badge
type: component
id: CMP.08
status: inferred
variants: [default, secondary, destructive, outline]
sizes: [sm]
tokens: [--primary, --primary-foreground, --secondary, --secondary-foreground, --destructive, --destructive-foreground, --border, --foreground, --radius-sm]
updated: 2026-07-24
---

# Badge

Label status, category, or metadata.

Related: [[color]] · [[borders]]

## Variants

| Variant | Surface token | Text token | Use for |
|---------|---------------|------------|---------|
| `default` | `--primary` | `--primary-foreground` | High-emphasis label |
| `secondary` | `--secondary` | `--secondary-foreground` | Secondary label |
| `destructive` | `--destructive` | `--destructive-foreground` | Error/danger label |
| `outline` | transparent + `--border` | `--foreground` | Neutral metadata |

## States

Default only unless used as an interactive filter.

## Props

| Prop | Type | Description | Default |
|------|------|-------------|---------|
| `variant` | union | Visual tone | `default` |
| `children` | node | Short label | required |

## Anatomy

```text
badge
  text: concise label
  radius: --radius-sm or pill if implementation supports it
```

## Accessibility

- Use text that remains meaningful without color.
- Do not make badges interactive unless implemented as buttons or links.

## Do / Don't

| Do | Don't |
|----|-------|
| Keep labels short. | Put paragraphs inside badges. |

## Code

```html
<span class="badge">Home</span>
```

```css
.badge { background: var(--primary); color: var(--primary-foreground); border-radius: var(--radius-sm); }
```

```tsx
<Badge variant="default">Home</Badge>
```
