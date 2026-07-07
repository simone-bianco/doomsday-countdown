---
name: vue-ui-components
description: Use for building or refactoring reusable components in vue-ui-components packages.
---

# Vue UI Components Creator

You build strictly typed, accessible, and deeply stylable Vue 3 components for the `vue-ui-components` (basic) or `vue-ui-components-advanced` (advanced) packages.

## Target Packages

1. **BASIC** (Atoms/Molecules: Buttons, Inputs, Badges) → `packages/simone-bianco/vue-ui-components/src/components/`
2. **ADVANCED** (Organisms: DataTables, FileUploaders, Widgets) → `packages/simone-bianco/vue-ui-components-advanced/src/components/`

## Gold Standard (ALL components MUST follow)

### 1. Script Setup + TypeScript
- `<script setup lang="ts">`
- Use `interface` for Props and UI definitions
- NEVER use `any` — define specific types or generics

### 2. The `ui` Prop Pattern
```typescript
// In src/types/ or local file:
export interface MyComponentUI { root?: string; label?: string; input?: string; }

// In component:
const props = withDefaults(defineProps<{ ui?: MyComponentUI }>(), { ui: () => ({}) })
```
Template: `:class="cn('base-classes', ui.root)"` — ALWAYS use `cn()`.

### 3. Icons
- Source: `lucide-vue-next`
- Accept as `Component` type (raw object, not strings)
- Render: `<component :is="icon" />`

### 4. Class Merging
- `import { cn } from '../utils'` — ALWAYS wrap classes in `cn()` for Tailwind conflict resolution

### 5. v-model
- Use `defineModel` — NEVER raw emit pattern

## Creation Workflow (MANDATORY)

1. **Define types** in `src/types/` → `{Component}UI` interface
2. **Create component** in `src/components/{Component}.vue`
3. **Accept `ui` prop** with `withDefaults` and default `() => ({})`
4. **Use `cn()`** for ALL class attributes
5. **Add ARIA** accessibility attributes
6. **Export** in `src/index.ts`:
   ```ts
   export { default as MyComponent } from './components/MyComponent.vue'
   export type { MyComponentUI } from './types'
   ```
7. **Test page** — delegate to `vue-ui-test-creator` skill

## Reference Components
- `Button.vue` — prop defaults, variant system, loading state
- `TextInput.vue` — v-model, error binding, label integration
- `DataTable.vue` — complex generic component with slots
