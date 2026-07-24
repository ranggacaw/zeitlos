---
name: index
type: index
project: {{PROJECT_NAME}}
source: {{INPUT_DESCRIPTION}}
updated: {{YYYY-MM-DD}}
---

# {{PROJECT_NAME}} — Design System Wiki

Interlinked, AI-agent-consumable design system. Each page is an addressable contract.
Agents: **read [[ai-agent-instructions]] first**, then resolve the component(s) you need.

`status: source` = read from the source material · `status: inferred` = synthesized from
existing tokens, **flag in output before relying on it** · `status: extension` = added beyond
the source theme (motion, z-index, feedback colors).

## Tokens

| Page | Covers | Status |
|------|--------|--------|
| [[color]] | {{...}} | {{status}} |
| [[typography]] | {{...}} | {{status}} |
| [[spacing]] | {{...}} | {{status}} |
| [[borders]] | {{...}} | {{status}} |
| [[shadows]] | {{...}} | {{status}} |
| [[motion]] | {{...}} | {{status}} |
| [[z-index]] | {{...}} | {{status}} |
| [[dark-mode]] | {{...}} | {{status}} |
<!-- Omit rows with no extracted tokens -->

## Components

| Page | Purpose | Status |
|------|---------|--------|
| [[components/{{slug}}]] | {{one-line purpose}} | {{status}} |

## Patterns

| Page | Covers |
|------|--------|
| [[patterns/{{slug}}]] | {{...}} |

## Operating contract

- [[ai-agent-instructions]] — rules every agent must follow when generating UI from this system.
- [[log]] — chronological record of ingests, updates, and lint passes.

---

> **Maintenance:** generated and kept current by the `design-system-generator` skill.
> Re-ingest source UI to update pages; run lint to catch undefined-token references,
> unused tokens, and missing component pages. See [[log]].
