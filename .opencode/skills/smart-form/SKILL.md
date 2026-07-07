---
name: smart-form
description: Use for forms built with useSmartForm or useStoreForm patterns.
---

# SmartForm Expert

You know exactly how `useSmartForm` and `useStoreForm` work in this project.

## Key Paths
- Composable: `packages/simone-bianco/vue-form-core/src/composables/useSmartForm.ts`
- Types: `packages/simone-bianco/vue-form-core/src/types.ts`
- Validation engine: `packages/simone-bianco/vue-form-core/src/validation/engine.ts`
- Generated TS types: `resources/js/types/generated.d.ts`
- Generated form rules: `resources/js/generated/form-rules.ts`
- StoreForm composable: `resources/js/composables/useStoreForm.js`

## useSmartForm — Standard Form

### Init Pattern (ALWAYS use generated rules)
```ts
import { useSmartForm } from '@simone-bianco/vue-form-core'
import { XxxDataRules } from '@/generated/form-rules'
import type { XxxData } from '@/types/generated'

const form = useSmartForm<XxxData>({ ...XxxDataRules })
form.fill({
    field_a: props.entity?.field_a ?? '',
    field_b: props.entity?.field_b ?? false,
    array_field: props.entity?.array_field ?? [],
})
```

### API
```ts
form.field_name = newValue     // Direct field access (reactive)
form.fill({ ... })             // Set multiple fields (chainable)
form.validate()                // Validate all, returns boolean
form.validateField('name')     // Validate single field
form.errors                    // Record<string, string> — merged client + server
form.processing                // boolean — request in flight
form.isDirty / form.hasErrors  // State checks
form.post(url, options?)       // Submit (also .put, .patch, .delete)
form.transform(data => ({...data, image: file.value}))  // Mutate before send
form.onSuccess(cb) / form.onError(cb) / form.onFinish(cb)  // Callbacks
form.reset() / form.clearErrors()  // Reset
```

### Template Binding
```vue
<TextInput v-model="form.field_name" :error="form.errors.field_name" @blur="form.validateField('field_name')" />
<Button type="submit" :loading="form.processing" :disabled="form.processing" />
```

### File Uploads
```ts
const imageFile = ref<File | null>(null)
form.transform(data => ({ ...data, image: imageFile.value }))
    .post(route('resource.store'), { forceFormData: true })
```

## useStoreForm — Pinia-Synced Form

For forms that must stay in sync with a Pinia store:
```ts
import { useStoreForm } from '@/composables/useStoreForm'

const form = useStoreForm(store, 'storeKey', {
    first_name: { storePath: 'user.name', rules: ['required', 'string', 'min:2'] },
    email: { storePath: 'email', rules: ['required', 'email'] },
}, { bidirectional: true })
```
Returns standard SmartForm — same API applies.

## Critical Gotchas
1. Generated defaults are `null` — ALWAYS `form.fill({})` after init
2. `data` is NOT a SubmitOption — use `form.transform()` instead
3. `transform()` is persistent — set just before `.post()`/`.put()`
4. `nullable` short-circuits ALL other rules for that field
5. `forceFormData: true` required when payload contains `File` objects
6. `form.errors` merges client + server (server wins for same field)
