---
name: alert
type: component
id: CMP.06
status: inferred
variants: [default, destructive]
sizes: [md]
tokens: [--background, --foreground, --destructive, --destructive-foreground, --border, --radius-lg, --spacing]
updated: 2026-07-25
---

# Alert

Show contextual feedback or destructive messages.

Related: [[color]] · [[borders]] · [[spacing]]

## Variants

| Variant | Surface token | Text token | Use for |
|---------|---------------|------------|---------|
| `default` | `--background` | `--foreground` | Informational feedback |
| `destructive` | `--destructive` or tinted destructive surface | `--destructive-foreground` | Errors and dangerous outcomes |

## States

Default and dismissible apply if implementation provides a close action.

## Props

| Prop | Type | Description | Default |
|------|------|-------------|---------|
| `variant` | union | Alert tone | `default` |
| `title` | string | Short heading | none |
| `children` | node | Message body | required |

## Anatomy

```text
alert
  optional icon
  title
  description
  radius: --radius-lg
  border: --border
```

## Accessibility

- Use `role="status"` for passive updates and `role="alert"` for urgent errors.
- Keep dismiss button keyboard accessible when present.

## Do / Don't

| Do | Don't |
|----|-------|
| Use destructive only for errors or dangerous context. | Use color alone without text. |

## Code

```html
<div class="alert" role="status"><strong>Saved</strong><p>Changes are visible now.</p></div>
```

```css
.alert { background: var(--background); color: var(--foreground); border-color: var(--border); border-radius: var(--radius-lg); }
.alert-destructive { background: var(--destructive); color: var(--destructive-foreground); }
```

```tsx
<Alert><AlertTitle>Saved</AlertTitle><AlertDescription>Changes are visible now.</AlertDescription></Alert>
```
