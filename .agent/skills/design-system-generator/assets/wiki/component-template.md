---
name: {{component-slug}}
type: component
id: {{CMP.NN}}
status: {{source | inferred}}
variants: [{{variant, ...}}]
sizes: [{{size, ...}}]
tokens: [{{token-slug, ...}}]
updated: {{YYYY-MM-DD}}
---

# {{ComponentName}}

> {{One-line purpose — what this component is for.}}

Related: {{[[token-page]] · [[token-page]] · [[patterns/...]] · [[ai-agent-instructions]]}}

## Variants

| Variant | Surface token | Text token | Use for |
|---------|---------------|------------|---------|
| `{{variant}}` | `{{--token}}` | `{{--token}}` | {{when to use}} |
<!-- Omit this table if the component has no visual variants -->

## Sizes

| Size | Height | Padding-x | Notes |
|------|--------|-----------|-------|
| `{{size}}` | {{value}} | {{value}} | {{notes; mark the default}} |
<!-- Omit if single-size -->

## States

{{default · hover · focus · active · disabled · loading · error/invalid · selected ·
 read-only · indeterminate — list those that apply, with token/behavior notes}}

## Validation
<!-- Omit for non-input components -->

- **Trigger:** {{on blur / on submit}}
- **Invalid surface:** {{--token (e.g. --destructive border + ring)}}
- **Message placement:** {{below field, token: --destructive-foreground}}
- **ARIA:** {{aria-invalid="true", aria-describedby → error message id}}

## Props

| Prop | Type | Description | Default |
|------|------|-------------|---------|
| `{{prop}}` | {{type}} | {{description}} | {{default}} |

## Anatomy

```
{{ASCII/keyed breakdown of the component's parts, referencing tokens by name —
 spacing, gaps, radius, focus behavior. Reference tokens, never raw values.}}
```

## Responsive behavior
<!-- Omit if the component is breakpoint-invariant -->

| Breakpoint | Behavior |
|------------|----------|
| `< sm`  | {{e.g. full-width; nav collapses to drawer; table → stacked cards}} |
| `sm–lg` | {{intermediate behavior, referencing breakpoint tokens}} |
| `≥ lg`  | {{default desktop layout}} |

## Accessibility

- {{Role / semantic element}}
- {{Keyboard interaction}}
- {{Focus behavior — link to [[patterns/focus-ring]]}}
- {{ARIA requirements}}

## Do / Don't

| ✅ Do | ❌ Don't |
|-------|----------|
| {{rule}} | {{anti-rule}} |

## Code

**HTML + token-referenced CSS** (copy-anywhere):

```html
{{minimal markup using token-driven classes}}
```

```css
{{CSS that references var(--token) — NEVER hardcoded hex/px for systemized values}}
```

**React ({{framework}})** — token-referenced, never hardcoded:

```tsx
{{idiomatic component usage in the project's framework}}
```
<!-- Keep both code forms by default. Drop one only if the project standardizes on it. -->
