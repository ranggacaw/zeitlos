---
name: link
type: component
id: CMP.03
status: inferred
variants: [default, muted, nav]
sizes: [inline]
tokens: [--primary, --foreground, --muted-foreground, --ring]
updated: 2026-07-25
---

# Link

Navigate or expose inline actions.

Related: [[color]] · [[typography]] · [[patterns/focus-ring]]

## Variants

| Variant | Surface token | Text token | Use for |
|---------|---------------|------------|---------|
| `default` | transparent | `--primary` | Inline navigation |
| `muted` | transparent | `--muted-foreground` | Secondary metadata links |
| `nav` | transparent / hover `--accent` | `--foreground` | Navigation items |

## States

Default, hover, focus-visible, active/current, and disabled where applicable.

## Props

| Prop | Type | Description | Default |
|------|------|-------------|---------|
| `href` | string | Navigation target | required |
| `current` | boolean | Marks current route | `false` |

## Anatomy

```text
a
  text: tokenized color
  focus: --ring
```

## Accessibility

- Use an anchor for navigation.
- Use `aria-current="page"` for current navigation links.

## Do / Don't

| Do | Don't |
|----|-------|
| Keep link text descriptive. | Use anchors for non-navigation actions. |

## Code

```html
<a class="link" href="/schedule">Schedule</a>
```

```css
.link { color: var(--primary); }
.link:focus-visible { outline-color: var(--ring); }
```

```tsx
<Link className="text-primary" href="/schedule">Schedule</Link>
```
