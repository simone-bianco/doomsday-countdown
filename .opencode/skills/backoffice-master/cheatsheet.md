# Cheatsheet

## Never
- hardcode `/backoffice`
- use native form primitives when package components exist
- use `window.confirm`
- mutate with raw `axios`/`fetch` for standard forms
- ship inert sortable headers

## Must Have
- `id` visible near the start of every operational row
- `sort_order` visible for entities that expose it
- sorting works at least for `id`, primary title/label, and `sort_order` when present
- paginator-backed tables consume `data`, `meta`, and `links`
- relative media paths resolve in admin/backoffice previews

## Grep
```bash
grep -R "/backoffice\|window.confirm\|axios\.\|fetch(\|sortable\|sort_order" resources/js/Pages resources/js/Components
```
