# Chapter 2: SEO, Indexing & Initial Rendering

## Load when

Implementing or reviewing sitemap XML, canonical URLs, hreflang, metadata, social previews, structured data, robots/noindex, public route rendering, SSR or Search Console readiness.

## Core idea

Search readiness is one coherent contract across URLs, initial HTML, content language, metadata, structured data and performance. Each public page must have one stable canonical identity and crawlable content. Sitemap submission is a discovery hint, not an indexing guarantee.

## Public URL inventory

Classify every route as one of:

- **indexable canonical page**: Home, About, published countdown detail and any approved editorial page;
- **public but noindex**: login, utility pages, JSON/data endpoints, previews or transient pages;
- **private**: backoffice, uploads, management, AI/demo endpoints;
- **removed/redirected**: legacy URLs with explicit redirect/canonical strategy.

The sitemap contains only canonical public 200 URLs.

## Sitemap XML contract

Required:

- dynamic `/sitemap.xml` or a sitemap index;
- correct XML response content type;
- absolute production HTTPS URLs;
- published records only;
- all intended localized canonical variants;
- accurate `lastmod` derived from owned content updates, not request time;
- deterministic output and cache/invalidation strategy;
- sitemap declaration in `robots.txt`;
- validation tests for XML, 200 responses and excluded private/data routes.

Do not include backoffice, login/logout, JSON sections, query variants that canonicalize elsewhere, unpublished countdowns, test/demo/upload routes, or non-200 URLs.

## Canonical and multilingual contract

Preferred long-term shape: stable locale-specific URLs. Query-based locale URLs may work, but every variant must still expose:

- self-referencing canonical;
- complete reciprocal `hreflang` alternates;
- `x-default`;
- matching sitemap alternates;
- matching internal links;
- correct `<html lang>` and content.

Never canonicalize all translated pages to English if each translation is intended to rank. Do not automatically hide alternate versions from crawlers through IP-only or browser-only adaptation. See ch03.

## Page metadata DTO

Backend should own a typed page metadata contract because route, locale, publication and canonical identity are backend facts.

Minimum fields:

- title and description;
- canonical URL and robots directive;
- locale and alternates;
- Open Graph title, description, URL, type and image;
- Twitter card values;
- structured-data blocks;
- factual `dateModified` where supported;
- primary image width, height and alt text.

The direct countdown route must use countdown-specific metadata, not the generic Home head.

## Initial HTML and SSR

A public page is not considered search-ready if the initial response contains only an empty Inertia shell and generic title.

Verify on the real production response:

- localized title/description/canonical/hreflang/robots;
- OG/Twitter values and JSON-LD;
- correct `<html lang>`;
- meaningful body text or verified crawler-rendered SSR output;
- no hydration mismatch.

If using Inertia SSR:

- build the SSR bundle explicitly;
- supervise and monitor the SSR process;
- mirror client plugin, locale, route and deterministic-time setup;
- fail safely without exposing secrets/private data;
- include SSR health in readiness checks.

Do not assume `npm run build` includes SSR unless the script proves it.

## Structured data

Use JSON-LD and only types the visible page supports.

Recommended project mappings:

- Home: `WebSite`, `CollectionPage`, `ItemList`;
- countdown detail: `WebPage` or `CreativeWork`, `BreadcrumbList`, possibly `Dataset` only if requirements are genuinely met;
- About: `AboutPage`;
- organization identity only after real legal/name/logo facts exist.

Do not claim outbound News snippets as owned full `NewsArticle` content. Validate structured data and ensure URLs, names, images and dates match visible content.

## Robots and noindex

`robots.txt` manages crawling load; it is not confidentiality or de-indexing.

Use meta robots for HTML, `X-Robots-Tag` for JSON/files, and authentication/authorization for private routes. Noindex targets include login/backoffice, public data endpoints, previews/test/demo surfaces and duplicate utility pages.

## Social preview contract

Each important page should have a stable absolute HTTPS image, suitable social crop, descriptive alt, predictable cache/versioning and fallback. Keep social preview assets separate from favicon assets.

## Search platform readiness

Required external evidence:

- Google Search Console property verification;
- sitemap submitted and parsed;
- URL Inspection on representative pages/locales;
- coverage/enhancement errors monitored;
- Bing Webmaster Tools if in scope;
- crawler-selected canonical matches intent;
- no unexpected parameter URLs indexed.

## Tests

Automate sitemap inclusions/exclusions, canonical, reciprocal hreflang, unique localized metadata, document language, noindex headers, JSON validity, initial HTML metadata, publication status and redirect rules.

## Stop conditions

- initial public HTML lacks page-specific metadata/content;
- translated page emits wrong language/canonical;
- sitemap contains private, duplicate or non-200 URLs;
- JSON endpoints are indexable;
- structured data contradicts visible content;
- production domain or locale URL strategy is undecided.

## Official basis

See `references.md`: Google Search Central sitemap, canonical, localized versions, locale-adaptive pages, structured data, snippets, robots and noindex guidance.
