# Chapter 3: First-Visit Locale & Multilingual URLs

## Load when

Implementing or reviewing first-visit language switching, country/geolocation hints, `Accept-Language`, locale persistence, URL strategy, `<html lang>`, `Content-Language`, cache keys or language-switch UX.

## Core idea

The application may select a useful language on the first visit, but explicit user and URL choices always win. Browser language is a language hint; IP country is a location hint. Both can be wrong. The application must remain crawlable in every supported locale and must never trap a user in an inferred language.

## Project locale set

Current public content supports `en`, `it`, `fr`, `de`, `es`, `nl`, `sv`, `pl`. Verify the current backend source of truth before hardcoding this list in new code.

## Resolution precedence

Use one centralized server-side locale resolver with this precedence:

1. explicit valid locale in the canonical URL or route;
2. explicit user selection persisted in a first-party locale cookie/session;
3. first-visit browser `Accept-Language` match against supported locales;
4. trusted edge country code mapped to a supported locale as fallback/tie-breaker;
5. configured default locale, currently expected to be English unless product decides otherwise.

An explicit choice must never be overwritten by later browser or country detection.

## First-visit definition

Treat a request as first visit only when:

- no explicit locale URL/parameter is present;
- no valid locale preference cookie/session exists;
- the route is a public locale-adaptive entry route;
- the request is not an internal JSON, asset, health or backoffice route.

The language selector must remain visible on every public page.

## Browser language

Use request `Accept-Language` server-side so the initial Blade/Inertia response has the right locale and `<html lang>`.

Rules:

- parse quality values correctly;
- match exact tags first, then language-only fallback (`it-IT` → `it`);
- ignore unsupported or malformed tags;
- do not use `navigator.language` as the sole initial-render source;
- do not override explicit user choice;
- remember that privacy modes may expose a reduced language list.

## Country/geolocation hint

The user requested switching based on where the visitor connects from. Implement this only through a trusted infrastructure adapter.

Allowed pattern:

- hosting/CDN supplies a verified country header, for example `CF-IPCountry` behind Cloudflare;
- application trusts the header only when the request actually comes through the configured trusted proxy/origin protection;
- country-to-locale mapping is explicit, small and testable;
- unknown, `XX`, Tor or unsupported countries fall through to browser/default;
- no raw IP is stored merely to choose language;
- no third-party geolocation HTTP request occurs during page rendering.

Do not trust a client-supplied country header on a directly reachable origin.

## Country-to-locale mapping

Country does not reliably identify language. Use country only as fallback or tie-breaker.

Example durable mapping:

- Italy, San Marino, Vatican City → `it`;
- France and selected French-default territories → `fr` only after product review;
- Germany/Austria → `de`;
- Spain → `es`;
- Netherlands → `nl`;
- Sweden → `sv`;
- Poland → `pl`;
- all other countries → browser language or `en`.

[DA VERIFICARE] Belgium, Switzerland, multilingual territories and diaspora cases require product decisions; never guess silently.

## URL and redirect behavior

Best SEO behavior uses stable localized URLs with all variants independently reachable.

For first visit to a neutral entry URL:

- resolve locale once;
- prefer a temporary user-routing redirect to the stable locale URL when the URL architecture supports it;
- do not make permanent canonical redirects based on IP or `Accept-Language`;
- do not redirect crawlers into only one locale;
- preserve path/query and avoid loops;
- after manual language choice, navigate to the explicit locale URL and persist it.

If the current architecture retains `?lang=xx`:

- validate and canonicalize it consistently;
- ensure each locale variant has correct canonical/hreflang;
- set app locale before rendering Blade;
- include locale in cache keys;
- avoid infinite parameter combinations.

## Response contract

The resolved locale must drive:

- Laravel application locale before Blade rendering;
- `<html lang>` and `Content-Language` where appropriate;
- Inertia shared locale and backend DTO localization;
- cache key;
- metadata/canonical/hreflang;
- date/number formatting;
- consent/policy copy where available.

If the same URL varies by `Accept-Language`, use an appropriate `Vary: Accept-Language` strategy and verify CDN/cache behavior. Stable locale URLs are preferable.

## Persistence and privacy

The locale preference can be a necessary first-party cookie because it stores an explicit usability choice, not tracking.

Requirements:

- minimal locale value;
- reasonable duration;
- Secure, SameSite and path/domain appropriate to production;
- no personal profile or IP in the value;
- manual selection updates it;
- invalid/removed locales fall back safely;
- policy documentation explains the functional preference if required.

## Crawler behavior

Google documents that locale-adaptive pages may not expose all variants because Googlebot commonly crawls from US IPs and may omit `Accept-Language`.

Therefore:

- every localized page needs its own crawlable URL;
- internal language links must be ordinary crawlable links;
- sitemap/hreflang must enumerate variants;
- inferred locale is a UX enhancement, not the only access path;
- do not return different canonical content to crawlers and users.

## Tests

Cover:

- explicit URL wins over cookie/header/country;
- persisted user choice wins later;
- `Accept-Language` quality parsing and region fallback;
- trusted country fallback;
- spoofed country header ignored on untrusted origin path;
- unknown/Tor country falls through;
- no redirect loop;
- JSON/backoffice/assets unaffected;
- correct `<html lang>` and `Content-Language`;
- locale cache isolation and canonical/hreflang consistency;
- crawler/no-header request reaches default while all alternates remain linked.

## Anti-patterns

- external IP-geo API on each request;
- storing raw IP for language choice;
- country as permanent language identity;
- overriding explicit selection;
- client-only switch after English HTML rendered;
- permanent 301 based on inferred locale;
- trusting edge headers on a directly reachable origin;
- one URL hiding all other locale variants.

## Stop conditions

- URL/canonical locale strategy undecided;
- trusted proxy/edge source unknown;
- explicit user choice can be overwritten;
- Italian content emits `<html lang="en">`;
- locale cache keys collide;
- crawlers can reach only one inferred language;
- country mapping contains unreviewed multilingual assumptions.

## Official basis

See `references.md`: Google locale-adaptive and multilingual guidance, MDN `Accept-Language`, and trusted edge-country header documentation.
