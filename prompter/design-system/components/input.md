---
name: input
type: component
id: CMP.04
status: inferred
variants: [default, invalid, disabled]
sizes: [md]
tokens: [--input, --foreground, --muted-foreground, --border, --ring, --destructive, --radius-md, --spacing]
updated: 2026-07-24
---

# Input

Collect single-line text input.

Related: [[color]] · [[borders]] · [[spacing]] · [[patterns/focus-ring]]

## Variants

| Variant | Surface token | Text token | Use for |
|---------|---------------|------------|---------|
| `default` | `--input` | `--foreground` | Standard text entry |
| `invalid` | `--input` + `--destructive` border | `--foreground` | Validation error |
| `disabled` | `--muted` | `--muted-foreground` | Non-editable field |

## States

Default, focus-visible, invalid, disabled, read-only, and placeholder apply.

## Validation

- Trigger: on blur or submit, depending on form flow.
- Invalid surface: `--destructive` border/ring.
- Message placement: below field in [[components/form-field]].
- ARIA: `aria-invalid="true"` and `aria-describedby` pointing at error text.

## Props

| Prop | Type | Description | Default |
|------|------|-------------|---------|
| `type` | string | Native input type | `text` |
| `disabled` | boolean | Prevent editing | `false` |
| `aria-invalid` | boolean | Invalid state | `false` |

## Anatomy

```text
input
  surface: --input
  text: --foreground
  placeholder: --muted-foreground
  radius: --radius-md
  focus: --ring
```

## Accessibility

- Always pair with a visible or programmatic label.
- Use `aria-describedby` for help and error text.

## Do / Don't

| Do | Don't |
|----|-------|
| Use semantic input types. | Rely on placeholder as the only label. |

## Code

```html
<input class="input" name="team" aria-describedby="team-help" />
```

```css
.input { background: var(--input); color: var(--foreground); border-color: var(--border); border-radius: var(--radius-md); }
.input:focus-visible { outline-color: var(--ring); }
.input[aria-invalid="true"] { border-color: var(--destructive); }
```

```tsx
<Input name="team" aria-describedby="team-help" />
```
