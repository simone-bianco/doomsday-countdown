# Production Readiness Cheatsheet

## P0 stop conditions

- public demo/AI/upload route unprotected
- backoffice has no authorization
- login has no throttle
- debug/dev dependencies/routes/hot file in release
- legal/controller/vendor facts incomplete while tracking enabled
- wrong locale/canonical/initial HTML
- no backup restore proof
- no rollback
- no monitoring/alerts for critical processes
- secret strategy or production topology unknown

## SEO

- sitemap XML: canonical public 200 URLs only
- robots contains sitemap; robots is not access control
- self canonical + reciprocal hreflang + x-default
- correct `<html lang>` and page-specific initial metadata
- JSON/data/login/backoffice noindex
- accurate JSON-LD only
- SSR bundle/process or equivalent crawlable initial rendering

## First-visit locale

```text
explicit URL > persisted user choice > Accept-Language > trusted country fallback > default
```

- never override explicit choice
- trusted edge header only
- no live IP-geo API
- all locale URLs crawlable
- locale drives Blade, metadata, DTOs and cache keys

## Analytics

- real GA4/GTM IDs from environment
- one page-view owner
- no optional tag before consent
- Reject/Customize/Accept/revoke verified
- Tag Assistant + DebugView
- final policy facts, retention and vendors

## Favicon

Source: `z-docs/images/logo_without_text.png` — verify approval.

Generate public ICO/SVG/16/32/180/192/512 assets, safe padding, no text, link in server head, never reference `z-docs` at runtime.

## Security

- production route allowlist
- RBAC/policies and rate limits
- secure cookies/proxies/HTTPS
- CSP/HSTS/nosniff/referrer/permissions/frame policy
- no raw errors/secrets
- dev routes absent under `--no-dev`

## Operations

- immutable release artifact
- production environment matrix
- migrations without fresh/seed dependency
- workers/scheduler/SSR supervised
- centralized errors/logs/metrics
- external uptime checks
- backup + restore proof
- rollback artifact and trigger

## Diagnostics

```bash
composer audit --locked --no-interaction
npm audit --omit=dev --audit-level=moderate
npm audit --audit-level=high
php artisan route:list --except-vendor
php artisan schedule:list
php artisan inertia:check-ssr
php artisan test --stop-on-failure
npm run build
php -d memory_limit=1G artisan typescript:transform
php -d memory_limit=1G artisan form-bridge:generate
php vendor/bin/pint --test
git diff --check
```

[DA VERIFICARE] Use the approved SSR build command; current `npm run build` must not be assumed to generate SSR.

## Final evidence

- real production domain smoke
- metadata/sitemap/noindex/security headers
- consent network matrix
- Core Web Vitals/Lighthouse/accessibility
- route/config/build version
- backup/restore/rollback
- monitoring/alerts receiving data
- Search Console sitemap/URL Inspection
