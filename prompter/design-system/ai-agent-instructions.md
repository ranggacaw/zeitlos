---
name: ai-agent-instructions
type: contract
version: 1.0
updated: 2026-07-25
---

# AI Agent Instructions

Operating contract for any agent generating UI from this design system.

## Purpose

This wiki is the source of truth for UI generation. Read [[index]] and then the relevant [[components/...]] page before generating a component, layout, or style.

## Rules

1. Token resolution: use token references from [[color]], [[dark-mode]], [[typography]], [[spacing]], [[borders]], and [[shadows]]. Do not hardcode colors or systemized spacing in generated UI.
2. Source fidelity: all component pages are `status: inferred` because only `index.css` tokens were provided. Flag this in output summaries until real component source confirms them.
3. Pairing: every semantic surface token pairs with its matching `-foreground` token. Do not mix unrelated text and surface tokens without an accessibility reason.
4. Contrast: do not use failing pairs for normal text. Known failures include light `--accent` / `--accent-foreground`, light `--sidebar-accent` / `--sidebar-accent-foreground`, light `--sidebar-primary` / `--sidebar-primary-foreground`, dark `--primary` / `--primary-foreground`, and dark `--sidebar-primary` / `--sidebar-primary-foreground`.
5. Defaults: if variant or size is unspecified, use `primary` and `md` for actions, `default` for content surfaces, and note the assumption.
6. Accessibility: preserve semantic HTML first. Use native `button`, `a`, `input`, `label`, and landmark elements unless a component requires ARIA.
7. Output contract: list components used, variants selected, inferred status, tokens applied, and accessibility behavior included.

## Example Output

```html
<!-- APPLIED DEFAULTS: component=Button, variant=primary, size=md, status=inferred -->
<!-- tokens: --primary, --primary-foreground, --radius-md, --ring -->
<button class="btn btn-primary btn-md">Save</button>
```
