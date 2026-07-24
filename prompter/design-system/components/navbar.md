---
name: navbar
type: component
id: CMP.09
status: inferred
variants: [default, muted]
sizes: [responsive]
tokens: [--background, --foreground, --border, --accent, --accent-foreground, --ring, --shadow-sm]
updated: 2026-07-24
---

# Navbar

Present primary navigation.

Related: [[components/link]] · [[color]] · [[shadows]] · [[patterns/focus-ring]]

## Variants

| Variant | Surface token | Text token | Use for |
|---------|---------------|------------|---------|
| `default` | `--background` | `--foreground` | Page-level navigation |
| `muted` | `--muted` | `--foreground` | Secondary nav region |

## States

Default, hover, current, focus-visible, collapsed/expanded apply.

## Props

| Prop | Type | Description | Default |
|------|------|-------------|---------|
| `items` | array | Navigation links | required |
| `currentPath` | string | Current route | none |

## Anatomy

```text
nav
  brand
  links
  actions
  border: --border
  focus: --ring
```

## Responsive Behavior

| Breakpoint | Behavior |
|------------|----------|
| `< sm` | Collapse links into compact/mobile navigation. |
| `>= sm` | Show horizontal links when space allows. |

## Accessibility

- Use `<nav aria-label="Primary">`.
- Mark current link with `aria-current="page"`.

## Do / Don't

| Do | Don't |
|----|-------|
| Keep navigation labels stable. | Hide current page state from screen readers. |

## Code

```html
<nav class="navbar" aria-label="Primary"><a href="/">Dashboard</a></nav>
```

```css
.navbar { background: var(--background); color: var(--foreground); border-color: var(--border); box-shadow: var(--shadow-sm); }
.navbar a:focus-visible { outline-color: var(--ring); }
```

```tsx
<nav aria-label="Primary" className="bg-background text-foreground border-border">...</nav>
```
