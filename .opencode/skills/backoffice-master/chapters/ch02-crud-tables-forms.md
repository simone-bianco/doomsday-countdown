# CRUD, Tables & Forms

## Load When
creating management tables, modals, edit pages, and relation managers.

## Rules
- Small entities: create/edit modals with reset-on-close.
- Aggregate roots: edit page with tabs/relation managers.
- Use package components: `Button`, `TextInput`, `Textarea`, `Select`, `Toggle`, `Modal`, `DataTable`, `Tabs`.
- No native `<button>`, `<input>`, `<select>` in app backoffice UI when package components exist.
- Destructive actions use confirmation modals, never `window.confirm`.
- Prefer server-driven operational tables: use a paginator-backed package table such as `ServerDataTable` when the backend already exposes `data`, `meta`, and `links`.
- Avoid one global search across aggregate edit pages. Use one search surface per CRUD/relation tab and namespace query params when multiple lists share the same page.
- Put `id` as the first visible row data point/column for operational traceability.
- If an entity has `sort_order`, show `sort_order` in the row/table and wire sorting for it.
- Operational tables should support sorting at least by `id`, primary title/label, and `sort_order` when present.
- If the package table emits sort events, connect them to real query/server state. Never ship inert sortable headers.
- Default ordering must be deterministic. Use `id` as the baseline unless product defines another explicit operational default; when `sort_order` is the managed order, keep `id` as the tie-breaker.
- Rows that open edit/detail flows must visibly look clickable: clear hover treatment plus `cursor-pointer` in the real backoffice theme.
- Row action buttons must not trigger row-click navigation. Guard action cells/buttons with `data-no-row-click` or the package equivalent.
- Do not wrap relation tables in an extra card/container when the surrounding tab/panel is already boxed; flatten nested table chrome while preserving separators, search, pagination, row click, and actions.
- Save/Cancel actions belong at the top-right of create/edit pages and should also appear at the bottom-right on long forms.
- Add helper text for non-obvious operational fields such as `CTA label`, `Featured`, `sort_order`, schema/key fields, methodology/trend fields, source/image path, date semantics, and domain-specific scores.

## Table Density
Put recognizable fields first, stack multi-value cells, use badges for status, keep actions minimal.
