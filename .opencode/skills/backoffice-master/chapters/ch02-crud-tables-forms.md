# CRUD, Tables & Forms

        ## Load When
        creating management tables, modals, edit pages, and relation managers.

        ## Rules
- Small entities: create/edit modals with reset-on-close.
- Aggregate roots: edit page with tabs/relation managers.
- Use package components: `Button`, `TextInput`, `Textarea`, `Select`, `Toggle`, `Modal`, `DataTable`, `Tabs`.
- No native `<button>`, `<input>`, `<select>` in app backoffice UI when package components exist.
- Destructive actions use confirmation modals, never `window.confirm`.

## Table Density
Put recognizable fields first, stack multi-value cells, use badges for status, keep actions minimal.
