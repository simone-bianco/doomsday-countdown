---
name: smart-form
description: Use for forms built with useSmartForm or useStoreForm patterns.
---

# SmartForm Expert

Use this skill for Vue forms that submit to Laravel routes through generated rules, `useSmartForm`, or `useStoreForm`.

## Core rules

- Generated rules initialize the form.
- Always call `fill()` with intended defaults after init.
- Use package inputs with `v-model`, `:error`, and `form.processing`.
- Use `transform()` for payload changes/files; submit options do not accept arbitrary `data`.
- `form.errors` merges client and server errors; server errors win.
- Use StoreForm only when fields must mirror Pinia state.
- Do not use raw `axios`/`fetch` for standard backend mutations.

## Standard form

```ts
const form = useSmartForm<MyData>({ ...MyDataRules })
form.fill({ title: '' })
form.post(route('things.store'), { preserveScroll: true })
```

Template pattern:

```vue
<TextInput
    v-model="form.title"
    :error="form.errors.title"
    :disabled="form.processing"
    @blur="form.validateField('title')"
/>
```

## Files and transforms

```ts
form.transform(data => ({ ...data, image: file.value }))
form.post(route('profile.avatar'), { forceFormData: true })
```

Important: `transform()` is persistent until changed/reset. Set it close to the submit that needs it.

## StoreForm

Use `useStoreForm(store, key, fieldMap, { bidirectional: true })` only when form state must sync with Pinia store paths. It returns the standard SmartForm API.

Avoid StoreForm when a local form is enough.

## Gotchas

- generated defaults may be `null`; fill them explicitly;
- `nullable` short-circuits other rules;
- server errors override same-field client errors;
- do not pass arbitrary `data` in submit options;
- do not use raw `axios/fetch` for normal mutations.
