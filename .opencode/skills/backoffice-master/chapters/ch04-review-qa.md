# Review & QA

## Load When
final review of a backoffice implementation.

## Static Smells
- hardcoded `/backoffice`
- native form primitives
- `window.confirm`
- ad-hoc `axios`/`fetch` mutations
- stale show/view actions
- lowercase string icons
- headers marked sortable without a wired sort handler or backend sort contract
- plain `DataTable` used where backend already returns paginator/search metadata
- clickable rows without visible hover/cursor affordance

## Validate
Run targeted tests/build when code changed. Check visual density, sidebar overflow, modal focus, and table readability.

- Validate every CRUD table: server pagination/search contract is consumed; `id` is visible; `sort_order` is visible when present; sorting works for `id`, primary title/label, and `sort_order`; row-click guards work; default ordering is deterministic; empty/loading/paginated states stay readable.
- Validate aggregate pages: tabs stay outside the content card, relation tables do not add a second nested card/container, per-CRUD search/query state stays isolated, and create/edit/delete/cancel flows preserve active tab/query context.
- Validate forms: long pages expose Save/Cancel both near the header and at the bottom, dense modals are wide enough, helper text exists for non-obvious fields, and Save/Cancel routes do not 404.
- Validate media previews: relative stored paths resolve correctly in backoffice/admin previews.
