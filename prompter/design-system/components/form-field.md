---
name: form-field
type: component
id: CMP.05
status: inferred
variants: [default, invalid, disabled]
sizes: [md]
tokens: [--foreground, --muted-foreground, --destructive, --spacing]
updated: 2026-07-25
---

# FormField

Pair a control with label, help, and error text.

Related: [[components/input]] · [[color]] · [[spacing]]

## Variants

| Variant | Surface token | Text token | Use for |
|---------|---------------|------------|---------|
| `default` | transparent | `--foreground` | Normal field |
| `invalid` | transparent | `--destructive` | Validation error |
| `disabled` | transparent | `--muted-foreground` | Non-interactive field |

## States

Default, invalid, disabled, required, and optional apply.

## Validation

- Trigger: on blur or submit.
- Message placement: below control.
- ARIA: label `for` matches control `id`; help/error IDs connect via `aria-describedby`.

## Props

| Prop | Type | Description | Default |
|------|------|-------------|---------|
| `label` | string | Visible label | required |
| `help` | string | Optional guidance | none |
| `error` | string | Validation message | none |
| `required` | boolean | Required indicator | `false` |

## Anatomy

```text
field
  label: --foreground
  control: child component
  help: --muted-foreground
  error: --destructive
  gap: multiples of --spacing
```

## Accessibility

- Keep label visible for most fields.
- Error text must be announced through `aria-describedby`.

## Do / Don't

| Do | Don't |
|----|-------|
| Put help and error text near the control. | Show an error color without text. |

## Code

```html
<label class="field" for="team-name">
  <span>Team name</span>
  <input id="team-name" class="input" aria-describedby="team-help" />
  <span id="team-help" class="field-help">Visible to public pages.</span>
</label>
```

```css
.field { color: var(--foreground); }
.field-help { color: var(--muted-foreground); }
.field-error { color: var(--destructive); }
```

```tsx
<FormField label="Team name" help="Visible to public pages"><Input name="team" /></FormField>
```
