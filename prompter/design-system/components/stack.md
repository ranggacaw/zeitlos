---
name: stack
type: component
id: CMP.12
status: inferred
variants: [vertical, horizontal]
sizes: [sm, md, lg]
tokens: [--spacing]
updated: 2026-07-25
---

# Stack

Compose vertical or horizontal layout rhythm.

Related: [[spacing]]

## Variants

| Variant | Surface token | Text token | Use for |
|---------|---------------|------------|---------|
| `vertical` | none | inherited | Form fields, content groups |
| `horizontal` | none | inherited | Toolbars, action rows |

## Sizes

| Size | Height | Padding-x | Notes |
|------|--------|-----------|-------|
| `sm` | n/a | n/a | Tight gap from `--spacing` multiples |
| `md` | n/a | n/a | Default rhythm |
| `lg` | n/a | n/a | Section-level rhythm |

## States

No interactive states.

## Props

| Prop | Type | Description | Default |
|------|------|-------------|---------|
| `direction` | `vertical` or `horizontal` | Stack axis | `vertical` |
| `gap` | union | Gap size | `md` |
| `children` | node | Items to lay out | required |

## Anatomy

```text
stack
  display: flex
  direction: column or row
  gap: multiples of --spacing
```

## Responsive Behavior

| Breakpoint | Behavior |
|------------|----------|
| `< sm` | Horizontal stacks may wrap or become vertical. |
| `>= sm` | Use requested direction. |

## Accessibility

- Preserve DOM order; do not use visual order to change reading sequence.

## Do / Don't

| Do | Don't |
|----|-------|
| Use Stack for repeated rhythm. | Use arbitrary margins between siblings. |

## Code

```html
<div class="stack">...</div>
```

```css
.stack { display: flex; flex-direction: column; gap: calc(var(--spacing) * 4); }
```

```tsx
<div className="flex flex-col gap-4">...</div>
```
