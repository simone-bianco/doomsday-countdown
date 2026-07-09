# Component Creation Contract

## Load When
Creating, refactoring, exporting, or reviewing a package component.

## Contract

- Use `<script setup lang="ts">`.
- Define typed props/interfaces; avoid `any`.
- Use `defineModel` for v-model.
- Use `cn()` for Tailwind class merging.
- Expose typed `ui?: Partial<ComponentUI>` when deep styling is needed.
- Use lucide icons as component bindings, not lowercase string names.
- Add ARIA/keyboard behavior where interactive.
- Export component and public types from `src/index.ts`.
- Add/update types in `src/types/*` when the component exposes public UI/type contracts.

## Stop
Do not add package components for app-only/domain-only use. Put those under `resources/js/Components/{Domain}`.
