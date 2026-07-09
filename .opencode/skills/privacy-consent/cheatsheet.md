# Cheatsheet

Never load analytics/marketing/functional trackers before consent.

```bash
npm run build
php artisan test tests/Feature/PrivacyConsentPagesTest.php
php artisan route:list --path=privacy
php artisan route:list --path=cookie-policy
```
