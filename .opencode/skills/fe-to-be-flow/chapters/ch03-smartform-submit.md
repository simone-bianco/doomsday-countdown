# Frontend Form Submit

        ## Load When
        creating Vue form mutation UI.

        Use generated rules and type:

```ts
const form = useSmartForm<CreateThingData>({ ...CreateThingDataRules })
form.fill({ title: '' })
form.post(route('things.store'), { preserveScroll: true })
```

For files, use `transform()` and `forceFormData`. Do not pass arbitrary `data` submit options.
