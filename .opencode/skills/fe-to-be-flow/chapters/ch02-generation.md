# Generation

        ## Load When
        after changing any Data class used by frontend/forms.

        Run from project root:

```bash
php -d memory_limit=1G artisan typescript:transform
php -d memory_limit=1G artisan form-bridge:generate
```

Only commit generated changes when the DTO contract changed.
