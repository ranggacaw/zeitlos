---
name: ai-agent-instructions
type: contract
version: 1.0
updated: {{YYYY-MM-DD}}
---

# AI Agent Instructions

> Operating contract for any agent generating UI from this design system. These rules are
> consistent with — and enforce — every token and component page in this wiki.

## Purpose of this wiki

This wiki is the single source of truth for all UI generation tasks. Always read
[[index]] and the relevant [[components/...]] page before generating any component,
layout, or style.

## Rules

1. **Token resolution.** Always replace raw values with token references. Never hardcode
   colors, spacing, or font sizes. Use only token names defined in the token pages. Raw
   hex/px values in this wiki are documentation only.

2. **Component selection.** Match the user's request to the closest component page. If no
   exact match exists, compose from existing components using defined tokens. Never invent
   visual patterns not represented in this system.

3. **Variant & state.** Apply the correct variant and state for the context. If ambiguous,
   default to the `primary` variant and `md` size, and note the assumption.

4. **Accessibility.** Every generated component must include the ARIA roles, keyboard
   support, and focus management specified on its page. Pairing rule: every surface token
   pairs with its `-foreground` token.

5. **Ambiguity handling.** If size/variant/state is unspecified, apply system defaults
   (`primary` / `md` / `default`) and append a comment block listing the applied defaults.

6. **Source fidelity.** Tokens/components marked `status: inferred` or `status: extension`
   are **not** in the source theme. Use them, but flag them in the output summary so they
   can be ratified. Never overload `--primary` or `--destructive` for missing feedback states.

7. **Theme integrity.** Style only with semantic tokens that resolve in every theme the
   system defines (see [[dark-mode]]). Never hardcode a value that only works in one theme.
   If a needed token is flagged `no dark value` on the dark-mode page, surface that in the
   output summary instead of guessing a dark value. Do not rely on box shadows alone for
   elevation in dark mode — follow the system's dark elevation strategy (surface steps or
   borders) when one is documented.

8. **Output contract.** Deliver generated UI with:
   - token-referenced styles only;
   - inline comments mapping each value to its token source;
   - a summary block listing components used, tokens applied, variants selected, and
     accessibility features included.

## Example output

```html
<!-- APPLIED DEFAULTS: variant=primary, size=md, state=default -->
<!-- tokens: --primary, --primary-foreground, --radius-md, --spacing -->
<button class="btn btn-primary btn-md">Save</button>
<!-- SUMMARY: components=[Button], tokens=[--primary, --primary-foreground, --radius-md],
     variant=primary, size=md, a11y=[native button, focus-visible ring, Enter/Space] -->
```
