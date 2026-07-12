# Production Readiness Patterns

## Evidence-backed gate row

```text
Gate: Public production route allowlist
Severity: P0
Environment: release candidate with APP_ENV=production and --no-dev dependencies
Evidence: route:list output + middleware review
Expected: only approved public/private routes
Result: PASS / FAIL / BLOCKED
Residual risk: ...
Stop condition: demo/upload/AI route unexpectedly public
Rollback/mitigation: remove provider/route or protect before release
```

## Central locale resolver

```text
resolve(request):
  if explicit valid locale URL: return explicit
  if valid persisted user locale: return persisted
  if supported Accept-Language match: return browser match
  if trusted edge country maps to supported locale: return country fallback
  return configured default
```

Explicit/user selection always wins. Country header is accepted only from trusted proxy infrastructure. Resolver runs before Blade, metadata, DTO and cache composition.

## Trusted geo adapter

```text
interface VisitorCountryResolver
  resolve(Request): ?ISO3166Alpha2

CloudflareCountryResolver:
  require configured trusted proxy/origin protection
  read CF-IPCountry
  reject XX, T1 and malformed values
  return country or null
```

Do not call remote IP geolocation during rendering. Do not store raw IP for language choice.

## SEO page DTO

```text
SeoPageData
  title
  description
  canonical_url
  robots
  locale
  alternates[] { locale, url }
  open_graph { type, title, description, url, image, dimensions, alt }
  twitter { card, title, description, image }
  structured_data[]
  date_modified?
```

Backend owns route/locale/publication facts. Vue/Blade renders the contract.

## Indexability policy

```text
indexable:
  Home, About, published countdown detail, approved editorial pages

noindex:
  login, backoffice HTML, public JSON/data, previews/demo/test

private:
  mutations, uploads, keys, admin APIs
```

Use meta robots for HTML and `X-Robots-Tag` for JSON/files. Authorization is separate.

## Favicon asset matrix

| Asset | Purpose | Required property |
|---|---|---|
| `favicon.svg` | modern tab | clear vector, no text |
| `favicon.ico` | fallback tab | common embedded sizes |
| `favicon-16x16.png` | small tab | recognizable at 16px |
| `favicon-32x32.png` | standard tab | transparent/safe padding |
| `apple-touch-icon.png` | iOS home screen | 180×180 |
| `icon-192.png` | manifest | square |
| `icon-512.png` | manifest | square/high quality |
| maskable variant | install surfaces | safe-zone padding |

Source expected: `z-docs/images/logo_without_text.png`. Generate public assets; never reference `z-docs` at runtime.

## One page-view owner

```text
initial page view: one GTM/GA owner
Inertia navigation: one explicit owner
Enhanced Measurement history changes: disabled or proven non-duplicating
local tabs/carousel: events only, not page_view
before consent: no optional event
revocation: no future optional event
```

## Private cache expiry envelope

```text
effective_expiry = min(
  configured_ttl,
  active_countdown_boundary,
  activity_window_boundary,
  next_scheduled_publication
)
```

Domain service owns visibility queries; cache coordinates timestamps/keys. Private metadata never leaks into DTOs.

## Production route allowlist test

Build/install with production dependencies, then assert dev routes absent, AI/upload protected, backoffice authorized, JSON noindex and health non-leaking.

## Release artifact exclusions

```text
.env
public/hot
node_modules
Composer dev packages
z-docs source assets
local key seed files
__pycache__ / *.pyc
debug dumps/logs/test screenshots
```

## Rollback evidence

```text
Trigger: sustained 5xx, privacy/security breach or critical regression
Application: previous immutable artifact available
Database: previous code compatible or forward-fix required
Assets/cache: version/purge known
Processes: worker and SSR restart known
Validation: health + Home + countdown + login + sitemap
Owner/system: named and reachable
```

## Anti-slop rules

- no ranking guarantees;
- no fabricated risk/probability score;
- no legal placeholder marked complete;
- no GA/GTM ID assumption;
- no wildcard CSP to silence errors;
- no country=language certainty;
- no `lastmod=now()` per request;
- no unrelated JSON-LD;
- no production approval from agent report alone.
