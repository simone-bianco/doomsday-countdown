# Cheatsheet

```bash
php -d memory_limit=1G artisan typescript:transform
php -d memory_limit=1G artisan form-bridge:generate
php artisan test --stop-on-failure
```

Never: raw controller validation arrays, domain work in controllers, unverified raw Storage in package domains.
