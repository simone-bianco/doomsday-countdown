# Testing Package & Demo Pages

## Load When
Validating a package component or adding examples/demos.

## Testing package

Use `packages/simone-bianco/vue-ui-components-testing` for visual/manual component pages.

Important areas:

- `src/Resources/js/Pages/*` — component demo pages.
- `src/Resources/js/Components/PropsEditor.vue` — props editor helper.
- `src/Http/Controllers/ComponentTestController.php` — test page controller.
- `src/Routes/web.php` — testing package routes.

## Review checks

- exported from package `src/index.ts`;
- demo/test page exists or is intentionally unnecessary;
- keyboard/focus/ARIA behavior works;
- disabled/loading/error states are covered;
- dark/theme variants render correctly;
- `ui` overrides merge correctly;
- no app-domain logic leaked into the package.
