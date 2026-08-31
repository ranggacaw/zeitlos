---
name: apply
description: Implement and apply an approved change proposal
---

<!-- PROMPTER:START -->
**Guardrails**
- Favor straightforward, minimal implementations first and add complexity only when it is requested or clearly required.
- Keep changes tightly scoped to the requested outcome.
- Refer to `prompter/AGENTS.md` (located inside the `prompter/` directory—run `ls prompter` if you don't see it) if you need additional Prompter conventions or clarifications.

**Steps**
Track these steps as TODOs and complete them one by one.
1. Read `changes/<id>/proposal.md`, `design.md` (if present), and `tasks.md` to confirm scope and acceptance criteria.
2. Work through tasks sequentially, keeping edits minimal and focused on the requested change.
3. Immediately after finishing each task, edit `tasks.md` to mark that task `- [x]` before starting the next one. Never batch checklist updates for the end of the run—`tasks.md` must reflect progress in real time.
4. After all work is done, do a final pass over `tasks.md` to confirm every checkbox reflects reality (nothing marked done that isn't, nothing finished but unmarked).
5. Reference `prompter list` or `prompter show <item>` when additional context is required.

**Manual Testing Guide**
Only on demand: the user invokes this skill and explicitly asks for the guide (e.g. "generate the testing guide for change `<id>`", "create guide.md for `<id>`"). When triggered this way, skip the implementation steps—identify the target change `<id>` (ask if it's ambiguous), then produce the guide directly from what changed in that apply. Never offer or ask to generate this guide at the end of an apply run.

When generating:
- Base the guide strictly on what actually changed in this apply—the tasks completed and the files touched.
- Save it to `changes/<id>/guide.md`.
- Structure it as concrete, step-by-step scenarios a person can follow by hand, each with: preconditions/setup, the exact steps to perform, and the expected result to verify against.
- Cover the primary happy path plus any notable edge cases or error states introduced by the change.
- Note any required setup (env vars, seed data, accounts, commands to start the app) so the tester can reproduce the steps from a clean state.

**Reference**
- Use `prompter show <id> --json --deltas-only` if you need additional context from the proposal while implementing.
<!-- PROMPTER:END -->

<!-- LOCAL (kept outside the PROMPTER block so it survives regeneration) -->
**Task Checklist Discipline (overrides any batching in the steps above)**
- Update `changes/<id>/tasks.md` the moment a task is completed: mark it `- [x]` right away, then move to the next task.
- Do not defer checklist updates to the end of the run. If the run is interrupted, `tasks.md` must still show exactly which tasks are done.

**Design System (UI changes only)**
When a task builds or modifies UI, before implementing:
- Check whether a project design system exists at `prompter/design-system/ai-agent-instructions.md`.
  - If it exists: read it first, then read the relevant `prompter/design-system/components/<name>.md` contract(s) for the components you touch, and use only token-referenced styles from `prompter/design-system/tokens/`.
  - If it does **not** exist (or the file is missing): skip this step and implement UI normally—do not block, and do not invent a design system.
- This step is a no-op for non-UI tasks; keep it lightweight and only load design-system files you actually need.

