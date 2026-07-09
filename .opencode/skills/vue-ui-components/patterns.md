# Patterns

## Catalog Before Creation
Search existing exports in `src/index.ts` and load the matching catalog chapter before adding a new component.

## Typed UI Contract
Expose `ui?: Partial<ComponentUI>` for deep styling and merge defaults with `cn()`.

## Package Export
Export component and public types from `packages/simone-bianco/vue-ui-components/src/index.ts`.

## Demo Page
Use `vue-ui-components-testing` for manual visual validation when component behavior is non-trivial.
