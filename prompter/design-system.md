---
name: index
type: index
project: zeitlos
source: index.css shadcn/tweakcn CSS variables
updated: 2026-07-24
---

# zeitlos Design System Wiki

Interlinked, AI-agent-consumable design system generated from `index.css`.

Agents: read [[ai-agent-instructions]] first, then resolve the component contract you need.

`status: source` = read from source material. `status: inferred` = synthesized from source tokens and must be flagged before relying on it.

## Tokens

| Page | Covers | Status |
|------|--------|--------|
| [[color]] | shadcn semantic colors, charts, sidebar colors, contrast checks | source |
| [[dark-mode]] | `.dark` semantic color overrides | source |
| [[typography]] | font family variables and tracking | source |
| [[spacing]] | Tailwind spacing base variable | source |
| [[borders]] | radius scale and border/input/ring tokens | source |
| [[shadows]] | shadow variables and elevation aliases | source |

## Components

No component markup was provided in the source CSS. The pages below are inferred from the shadcn token vocabulary and common shadcn usage patterns.

| Page | Purpose | Status |
|------|---------|--------|
| [[components/button]] | Trigger a primary, secondary, destructive, ghost, or link action | inferred |
| [[components/icon-button]] | Trigger a compact icon-only action | inferred |
| [[components/link]] | Navigate or expose inline actions | inferred |
| [[components/input]] | Collect single-line text input | inferred |
| [[components/form-field]] | Pair a control with label, help, and error text | inferred |
| [[components/alert]] | Show contextual feedback or destructive messages | inferred |
| [[components/card]] | Group related content on a raised surface | inferred |
| [[components/badge]] | Label status, category, or metadata | inferred |
| [[components/navbar]] | Present primary navigation | inferred |
| [[components/sidebar]] | Present persistent secondary navigation | inferred |
| [[components/container]] | Constrain page content width and side padding | inferred |
| [[components/stack]] | Compose vertical or horizontal layout rhythm | inferred |

## Production Coverage

Component tally: 0 source / 12 inferred / 39 not covered.

Not covered (absent from source): ButtonGroup, Textarea, Select, Combobox, Checkbox, Radio, Toggle/Switch, Slider, DatePicker, FileUpload, Toast/Snackbar, Banner, ProgressBar, Spinner, Skeleton, EmptyState, ErrorState, Modal/Dialog, Drawer/Sheet, Dropdown, Popover, Tooltip, ContextMenu, Table, List, Tag, Avatar, Tabs, Accordion, Pagination, Stepper, Breadcrumb, Menu, Header/PageHeader, Footer, Grid, Divider/Separator, Section/Hero.

## Patterns

| Page | Covers |
|------|--------|
| [[patterns/focus-ring]] | Shared focus-visible treatment using `--ring` |

## Operating Contract

- [[ai-agent-instructions]] — rules every agent must follow when generating UI from this system.
- [[log]] — chronological record of ingests and updates.

> Generated and maintained by the `design-system-generator` skill. Re-ingest source UI to promote inferred components to source when real component markup is available.
