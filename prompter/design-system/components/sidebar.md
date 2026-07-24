---
name: sidebar
type: component
id: CMP.10
status: inferred
variants: [default]
sizes: [responsive]
tokens: [--sidebar, --sidebar-foreground, --sidebar-primary, --sidebar-primary-foreground, --sidebar-accent, --sidebar-accent-foreground, --sidebar-border, --sidebar-ring]
updated: 2026-07-24
---

# Sidebar

Present persistent secondary navigation.

Related: [[color]] · [[dark-mode]] · [[patterns/focus-ring]]

## Variants

| Variant | Surface token | Text token | Use for |
|---------|---------------|------------|---------|
| `default` | `--sidebar` | `--sidebar-foreground` | Secondary app navigation |

## States

Default, hover/accent, selected/primary, focus-visible, collapsed/expanded apply.

## Props

| Prop | Type | Description | Default |
|------|------|-------------|---------|
| `items` | array | Sidebar navigation items | required |
| `collapsed` | boolean | Icon-only or compact display | `false` |

## Anatomy

```text
aside/nav
  header
  item list
  selected item: --sidebar-primary / --sidebar-primary-foreground
  hover item: --sidebar-accent / --sidebar-accent-foreground
  border: --sidebar-border
  focus: --sidebar-ring
```

## Responsive Behavior

| Breakpoint | Behavior |
|------------|----------|
| `< sm` | Prefer drawer or bottom navigation. |
| `>= sm` | Persistent sidebar can be shown. |

## Accessibility

- Use `<nav aria-label="Secondary">` where the sidebar contains navigation.
- Mark selected item with `aria-current="page"`.

## Do / Don't

| Do | Don't |
|----|-------|
| Use sidebar-specific tokens. | Reuse generic `--primary` for selected sidebar items. |

## Code

```html
<aside class="sidebar"><nav aria-label="Secondary"><a aria-current="page">Roster</a></nav></aside>
```

```css
.sidebar { background: var(--sidebar); color: var(--sidebar-foreground); border-color: var(--sidebar-border); }
.sidebar [aria-current="page"] { background: var(--sidebar-primary); color: var(--sidebar-primary-foreground); }
```

```tsx
<aside className="bg-sidebar text-sidebar-foreground border-sidebar-border">...</aside>
```
