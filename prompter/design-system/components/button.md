---
name: button
type: component
id: CMP.01
status: inferred
variants: [primary, secondary, destructive, outline, ghost, link]
sizes: [sm, md, lg, icon]
tokens: [--primary, --primary-foreground, --secondary, --secondary-foreground, --destructive, --destructive-foreground, --background, --foreground, --border, --ring, --radius-md, --spacing]
updated: 2026-07-25
---

# Button

Trigger a primary, secondary, destructive, ghost, or link action.

Related: [[color]] · [[borders]] · [[spacing]] · [[patterns/focus-ring]] · [[ai-agent-instructions]]

## Variants

| Variant | Surface token | Text token | Use for |
|---------|---------------|------------|---------|
| `primary` | `--primary` | `--primary-foreground` | Main page action |
| `secondary` | `--secondary` | `--secondary-foreground` | Secondary action with filled emphasis |
| `destructive` | `--destructive` | `--destructive-foreground` | Irreversible or dangerous action |
| `outline` | `--background` + `--border` | `--foreground` | Lower-emphasis action |
| `ghost` | transparent / hover `--accent` | `--foreground` | Toolbar or subtle action |
| `link` | transparent | `--primary` | Inline action styled as text |

## Sizes

| Size | Height | Padding-x | Notes |
|------|--------|-----------|-------|
| `sm` | inferred from framework | inferred from framework | Compact action |
| `md` | inferred from framework | inferred from framework | Default |
| `lg` | inferred from framework | inferred from framework | Prominent action |
| `icon` | square control | `0` | Icon-only; provide accessible label |

## States

Default, hover, focus-visible via [[patterns/focus-ring]], active, disabled, and loading apply.

## Props

| Prop | Type | Description | Default |
|------|------|-------------|---------|
| `variant` | union | Visual treatment | `primary` |
| `size` | union | Control size | `md` |
| `disabled` | boolean | Prevent interaction | `false` |
| `loading` | boolean | Shows pending state and prevents duplicate action | `false` |

## Anatomy

```text
button
  content: label and optional leading/trailing icon
  radius: --radius-md
  focus: --ring
  spacing: multiples of --spacing
```

## Accessibility

- Use native `<button>` for actions and `<a>` only for navigation.
- Support Enter/Space automatically via native button.
- Icon-only buttons need `aria-label`.

## Do / Don't

| Do | Don't |
|----|-------|
| Use one primary action per view. | Use failing contrast pairs for small button text. |
| Mark inferred usage in summaries. | Use `--destructive` for non-destructive emphasis. |

## Code

```html
<button class="btn btn-primary">Save</button>
```

```css
.btn { border-radius: var(--radius-md); }
.btn:focus-visible { outline-color: var(--ring); }
.btn-primary { background: var(--primary); color: var(--primary-foreground); }
```

```tsx
<Button variant="primary" size="md">Save</Button>
```
