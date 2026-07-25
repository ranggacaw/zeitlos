---
name: focus-ring
type: pattern
status: inferred
updated: 2026-07-25
---

# Focus Ring

Shared focus-visible treatment for interactive controls, inferred from source tokens `--ring` and the base rule `@apply border-border outline-ring/50`.

Applies to: [[components/button]] · [[components/icon-button]] · [[components/link]] · [[components/input]] · [[components/form-field]]

## Rule

```yaml
trigger: focus-visible
color: var(--ring)
outline-color: color-mix(in oklab, var(--ring) 50%, transparent)
offset: framework default unless component source specifies otherwise
```

## When To Use

| Variant | When | Tokens / component |
|---------|------|--------------------|
| Control focus | Keyboard focus on buttons, links, inputs | `--ring`, component border token |
| Invalid focus | Invalid form control focus | `--destructive`, `--ring` if implementation uses both |

## Notes

- This is inferred because no concrete component markup was provided.
- Do not remove visible focus indication in custom components.
