# Feature UI Map: [Feature Name]

> Derived from the approved implementation plan (+ `roadmap.md` for multi-increment features).
> This is the feature's **page inventory and navigation contract**: every NEW page/screen this
> feature introduces, and where every button/link/element **goes** — another page, a modal, a
> drawer, a toast. It deliberately does NOT specify business logic (what "Save" persists,
> validation rules) — only the navigation *outcome* of each action.
>
> **Scope:** new UI only. Pages that already exist in the app are referenced as navigation
> targets (`→ /existing-route`) or listed under Entry Points, never re-specified here.
>
> **Primary consumer:** the `ui-ux-pro` / `ui-ux-max` skills read this file as their page
> inventory when building `.preview/` mockups — each page below becomes a preview page, and each
> navigation action below must actually work in the preview (links navigate, modals open, toasts
> appear).

## Action Vocabulary

Use exactly these forms in every **Action** column so previews can be wired mechanically:

| Form | Meaning |
|------|---------|
| `→ /route` | Navigate to that page (new or existing) |
| `→ back` | Navigate to the previous page |
| `open modal: <modal-id>` | Open a modal defined in the Modals section |
| `close modal` | Close the current modal |
| `open drawer: <drawer-id>` | Open a slide-over/drawer defined in the Drawers section |
| `toast: "<message>"` | Show a toast (define it in the Toasts section) |
| `→ external: <url>` | Open an external link (new tab) |
| `expand/collapse` | Toggle an in-page section (accordion, dropdown menu) |

Actions chain left to right: `Save → toast: "Webhook created" → /settings/webhooks` means the
button shows the toast and lands on that route. (What Save *stores* is out of scope here.)

---

## New Routes

> Every route this feature ADDS, with access level and (multi-increment) owning increment.

```
/settings/webhooks              Webhook List (index)        user      Increment 1
/settings/webhooks/new          Create Webhook              user      Increment 1
/settings/webhooks/:id          Webhook Detail + Deliveries user      Increment 2
```

## Entry Points from Existing UI

> How users reach the new pages — which existing screens/nav gain a link or button. These are
> the only edits to existing UI this map records (as one-line navigation additions, not
> redesigns). The implementer wires them; previews may stub the existing screen's shell.

| Existing location | New element | Action |
|-------------------|-------------|--------|
| `/settings` sidebar | "Webhooks" nav item | `→ /settings/webhooks` |
| [Existing page/menu] | [new link/button] | `→ /new-route` |

## App Shell

Reuse the app's existing shell (header/sidebar/nav) as-is — do not respecify it. List only
shell **additions** (they also appear in Entry Points above). In previews, reproduce a
representative version of the existing shell so the new pages read in context.

---

## Pages

> One block per NEW page in New Routes. Be exhaustive on the **Elements & Navigation** table —
> every clickable thing on the page gets a row, including row-level actions, breadcrumbs,
> pagination, and empty-state CTAs.

### `/settings/webhooks` — Webhook List *(Increment 1)*

- **Purpose:** [one line — what the user accomplishes here]
- **Access:** user, admin
- **Arrived from:** `/settings` sidebar "Webhooks" (entry point above)
- **Layout sections:** Page header (title + primary CTA) · Webhook table · Pagination

**Elements & Navigation**

| Element | Type | Location | Action |
|---------|------|----------|--------|
| "New Webhook" | primary button | page header | `→ /settings/webhooks/new` |
| Endpoint URL | link | table row | `→ /settings/webhooks/:id` |
| Delete | icon button | row actions | `open modal: confirm-delete-webhook` |
| "Settings" breadcrumb | link | breadcrumb | `→ /settings` |

**States**

| State | Shown when | Content / navigation |
|-------|------------|----------------------|
| Empty | no webhooks yet | Illustration + "Add your first webhook" button `→ /settings/webhooks/new` |
| Loading | data fetching | Table skeleton rows |
| Error | fetch failed | `toast: "Couldn't load webhooks"` + retry button (stays) |

[Repeat a block like the above for EVERY new page — index, detail, create, edit. No page is
too small to list.]

---

## Modals

> Every `open modal:` target above gets a row. Modals are in-page overlays, not routes.
> New modals only — existing app modals are referenced by name where reused.

| Modal id | Title | Opened from | Content (one line) | Buttons → outcome |
|----------|-------|-------------|--------------------|-------------------|
| `confirm-delete-webhook` | Delete webhook? | list/detail Delete | Irreversible-action warning | Cancel → `close modal` · Delete → `close modal` → `toast: "Webhook deleted"` → `/settings/webhooks` |

## Drawers

> Every `open drawer:` target above. Omit the section if the feature adds none.

| Drawer id | Side | Opened from | Content | Close behavior |
|-----------|------|-------------|---------|----------------|
| [drawer-id] | right | [trigger] | [one line] | X button / backdrop → `close` |

## Toasts

> Every `toast:` referenced above, so copy stays consistent across pages.

| Toast | Type | Triggered by |
|-------|------|--------------|
| "Webhook created" | success | Save on `/settings/webhooks/new` |
| "Webhook deleted" | success | Delete in `confirm-delete-webhook` |

---

## Pages by Increment

> Multi-increment features only — the slice ui-ux-pro designs per increment, matching the
> roadmap. Single-proposal features: replace with **Single proposal — all pages ship together.**

| Increment | Scope | Pages |
|-----------|-------|-------|
| 1 | Core schema + list/create UI | `/settings/webhooks`, `/settings/webhooks/new` |
| 2 | Deliveries | `/settings/webhooks/:id` |

---

## Next Step

Design these pages as clickable previews with the `ui-ux-pro` (or `ui-ux-max`) skill — it reads
this file as its page inventory and wires every action above into the `.preview/` mockups.
