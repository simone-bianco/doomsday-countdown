# Patterns

## Edit-Centric Aggregate
Use one edit page with tabs/managers when child relations are part of the management task.

## Modal CRUD
For small entities, use create/edit modals with reset-on-close and confirmation modals for deletes.

## Server Table with Row Click
Use a paginator-backed operational table with search/pagination/sort wired to query or server state. Keep `id` first, show `sort_order` when present, make row hover clearly interactive, and guard action cells from row-click handlers.

## Flat Relation Table Chrome
When a relation table lives inside an aggregate tab/panel that already provides the card shell, remove the extra inner table card chrome rather than nesting containers.

## Dedicated Relation Edit Page
Move complex child-record editing to dedicated pages instead of overloading inline forms. Keep Save/Cancel visible near the header and again at the bottom for long pages.

## Thin Controller + Service
Controller accepts request/DTO and delegates dashboard/CRUD composition to services.
