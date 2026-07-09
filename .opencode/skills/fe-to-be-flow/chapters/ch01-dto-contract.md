# DTO Contract

        ## Load When
        adding or changing request/response data shape.

        ## Backend
Create `app/Data/{Name}Data.php` with Spatie Data and `#[TypeScript]`.
Use validation attributes on constructor promoted properties.

```php
#[TypeScript]
final class CreateThingData extends Data
{
    public function __construct(
        #[Required, StringType, Max(255)] public string $title,
    ) {}
}
```

## Rule
Do not duplicate validation arrays in controllers.
