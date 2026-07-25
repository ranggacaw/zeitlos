# Design System Change Log

Append-only. One entry per ingest, update, render, or lint pass.

## [2026-07-25] ingest | index.css shadcn/tweakcn CSS variables

- Re-ingested `index.css` as the source CSS input for wiki output mode.
- Confirmed the shadcn semantic token vocabulary, inferred component coverage, focus-ring pattern, and agent operating contract remain valid.
- Added the `.dark` chart token rows to [[dark-mode]] because the source repeats `--chart-1` through `--chart-5` inside the dark theme block.

## [2026-07-24] ingest | index.css shadcn/tweakcn CSS variables

- Created [[index]], [[color]], [[dark-mode]], [[typography]], [[spacing]], [[borders]], [[shadows]], [[patterns/focus-ring]], and [[ai-agent-instructions]].
- Created inferred component contracts for [[components/button]], [[components/icon-button]], [[components/link]], [[components/input]], [[components/form-field]], [[components/alert]], [[components/card]], [[components/badge]], [[components/navbar]], [[components/sidebar]], [[components/container]], and [[components/stack]].
- Preserved shadcn semantic token vocabulary. All component pages are inferred because the source was CSS variables only.
