# Testing Guide: [Feature Name]

## Before you start

1. Run migration: `[exact command]`
2. Seed data: `[exact command]`
3. Add to `.env.local`: `[KEY=value]`
4. Start the app: `[exact command]`
5. Sign in as: [role/account, e.g. a user with at least 10 credits]

<!-- Only list steps the feature actually needs. Delete the rest. No explanations. -->

---

## 1. [Scenario name — what the user is doing]

1. Go to `[/route]`
2. Click **[Button label]**
3. Fill **[Field label]**: `[example value]`
4. Click **[Button label]**

✅ [One sentence: what the tester should see on screen]

## 2. [Next scenario]

1. Go to `[/route]`
2. ...

✅ [Expected result]

## 3. [Error case the user would actually hit]

1. ...
2. Submit with **[Field label]** empty

✅ [Error message shown, e.g. "Name is required" under the field]

<!--
Rules for this file:
- Every step is one line: Go to / Click / Fill / Type / Select / Wait for. Nothing else.
- Every field gets a literal example value in backticks.
- Exactly one ✅ line per scenario, one sentence, describing what is visible on screen.
- No file paths, no architecture, no "why", no diagnostics, no reference constants, no coverage notes.
- Happy path first, then only the error states a real user would run into.
- Aim for 3–7 scenarios, each 3–8 steps.
-->
