---
name: container
type: component
id: CMP.11
status: inferred
variants: [default, narrow, wide]
sizes: [responsive]
tokens: [--spacing]
updated: 2026-07-24
---

# Container

Constrain page content width and side padding.

Related: [[spacing]]

## Variants

| Variant | Surface token | Text token | Use for |
|---------|---------------|------------|---------|
| `default` | none | inherited | Standard page content |
| `narrow` | none | inherited | Reading or form layouts |
| `wide` | none | inherited | Data-heavy layouts |

## States

No interactive states.

## Props

| Prop | Type | Description | Default |
|------|------|-------------|---------|
| `variant` | union | Max-width behavior | `default` |
| `children` | node | Page content | required |

## Anatomy

```text
container
  width: 100%
  max-width: inferred per layout
  padding-inline: multiples of --spacing
```

## Responsive Behavior

| Breakpoint | Behavior |
|------------|----------|
| `< sm` | Full width with safe side padding. |
| `>= sm` | Center content and constrain width. |

## Accessibility

- Use with semantic landmarks such as `<main>` when it wraps primary page content.

## Do / Don't

| Do | Don't |
|----|-------|
| Keep layout spacing tokenized. | Hardcode one-off page gutters. |

## Code

```html
<main class="container">...</main>
```

```css
.container { width: 100%; margin-inline: auto; padding-inline: calc(var(--spacing) * 4); }
```

```tsx
<main className="mx-auto w-full px-4">...</main>
```
