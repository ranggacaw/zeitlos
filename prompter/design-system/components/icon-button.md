---
name: icon-button
type: component
id: CMP.02
status: inferred
variants: [primary, secondary, ghost, destructive]
sizes: [sm, md, lg]
tokens: [--primary, --primary-foreground, --secondary, --secondary-foreground, --destructive, --destructive-foreground, --accent, --accent-foreground, --ring, --radius-md]
updated: 2026-07-25
---

# IconButton

Trigger a compact icon-only action.

Related: [[color]] · [[borders]] · [[patterns/focus-ring]]

## Variants

| Variant | Surface token | Text token | Use for |
|---------|---------------|------------|---------|
| `primary` | `--primary` | `--primary-foreground` | Main compact action |
| `secondary` | `--secondary` | `--secondary-foreground` | Secondary compact action |
| `ghost` | transparent / hover `--accent` | `--foreground` | Header or toolbar action |
| `destructive` | `--destructive` | `--destructive-foreground` | Delete/remove action |

## States

Default, hover, focus-visible, active, disabled, and loading apply.

## Props

| Prop | Type | Description | Default |
|------|------|-------------|---------|
| `aria-label` | string | Required accessible name | none |
| `variant` | union | Visual treatment | `ghost` |
| `size` | union | Square control size | `md` |

## Anatomy

```text
button[aria-label]
  icon: centered
  radius: --radius-md
  focus: --ring
```

## Accessibility

- `aria-label` is required when there is no visible label.
- Use native button keyboard behavior.

## Do / Don't

| Do | Don't |
|----|-------|
| Use for repeated toolbar actions. | Use without an accessible name. |

## Code

```html
<button class="icon-btn" aria-label="Open menu"><svg aria-hidden="true"></svg></button>
```

```css
.icon-btn { border-radius: var(--radius-md); color: var(--foreground); }
.icon-btn:focus-visible { outline-color: var(--ring); }
```

```tsx
<Button variant="ghost" size="icon" aria-label="Open menu"><MenuIcon /></Button>
```
