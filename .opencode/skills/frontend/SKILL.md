---
name: frontend
description: Use for Vue frontend implementation across pages, components, and UI flows.
---

# Frontend Development Guidelines

## Component Hierarchy (STRICT)

1. **Base components** → `packages/simone-bianco/vue-ui-components` (Button, TextInput, Modal, Card, DataTable, Select, etc.)
   - If needed component doesn't exist, create it following the `vue-ui-components` skill guidelines
2. **Advanced components** → `packages/simone-bianco/vue-ui-components-advanced` (TagsManager, FiltersWidget, MagicTextarea, UserWidget)
   - If needed component doesn't exist, create it following the `vue-ui-components` skill guidelines
3. **Domain components** → `resources/js/Components/{Domain}/` (project-specific business logic)
   - Create here for components only used in this application

## Rules (ALWAYS)

1. **Use library components** — NEVER native HTML (`<button>`, `<input>`, `<select>`)
2. **Keep files small** — Pages < 100 lines, break into sub-components
3. **Use SmartForm** for all form interactions — `useSmartForm` from `@simone-bianco/vue-form-core`
4. **Import validation rules** from `@/generated/form-rules` — NEVER write manual rules
5. **Use `cn()`** for all class attributes (clsx + twMerge)
6. **Use `defineModel`** for v-model bindings — never raw emit pattern
7. **Icons from `lucide-vue-next`** — accept as `Component` type
8. **Styling via `ui` prop pattern** — `ui?: Partial<{Component}UI>` for deep class injection
9. **Tailwind CSS variables** — `ui-background`, `ui-primary`, `ui-foreground`, `ui-border`, etc.

## Page Pattern
```vue
<script setup>
import { Head, Deferred } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
// Pinia store
const store = useStore()
store.init(props)
watch(() => props, (p) => store.init(p), { deep: true })
onUnmounted(() => store.$reset())
</script>

<template>
  <Head :title="title" />
  <AppLayout>
    <Deferred data="asyncData">
      <template #fallback><Skeleton /></template>
      <!-- Sub-components, NOT inline logic -->
    </Deferred>
  </AppLayout>
</template>
```

## State Management
- Inertia props → Pinia store (single source of truth) → Components
- Reverb events → Store handlers → Reactive UI updates
- Guard pattern for Reverb + Inertia coexistence (see `reverb-websocket` skill)
