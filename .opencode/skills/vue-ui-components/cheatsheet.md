# Cheatsheet

## Before creating a component

1. Check catalog chapters first.
2. If package-worthy, place it under `packages/simone-bianco/vue-ui-components/src/components`.
3. If app/domain-specific, place it under `resources/js/Components/{Domain}`.

## Must-have contract

- typed props
- no `any`
- `defineModel` for v-model
- `cn()` class merging
- `ui` prop for deep styling when needed
- package export in `src/index.ts`
- type export when public
- accessibility/keyboard review

## Catalog shortcuts

- Controls/forms → ch01
- Tables/select/upload/media → ch02
- Layout/status/modals/nav/organisms → ch03
- New component contract → ch04
- Demo/testing package → ch05
