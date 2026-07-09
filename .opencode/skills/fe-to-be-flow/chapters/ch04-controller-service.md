# Controller + Service

        ## Load When
        implementing backend endpoint for the form.

        Controllers receive DTOs and call services:

```php
public function store(CreateThingData $data): RedirectResponse
{
    $this->service->create($data);
    return back()->with('success', 'Saved.');
}
```

Use FormRequest only for HTTP-specific authorization/pre-validation.
