# Chapter 9: Release QA & Post-Launch

## Load when

Reviewing a final release candidate, defining CI/release evidence, executing production smoke checks, validating SEO/consent/accessibility/performance, or monitoring launch.

## Core idea

Final QA combines automated, runtime and external-service evidence. It must run against the immutable release candidate and repeat as focused smoke on the real production domain. Manual “looks good” is useful but insufficient for security, indexing, consent, backup or operations.

## Release-candidate automated gates

Required checks include:

- full PHP suite;
- production frontend build;
- DTO/generated TypeScript/form generation when contracts changed;
- lint/Pint and TypeScript baseline-aware review;
- dependency audits;
- route list/middleware exposure;
- scheduler list;
- SSR build/check when enabled;
- diff/conflict markers;
- asset/hot/dev-file exclusions;
- migration/patch review;
- production config validation.

Do not hide new failures inside a pre-existing baseline.

## SEO checks

Verify initial production HTML localized metadata, canonical, reciprocal hreflang/x-default, correct language, OG/Twitter, valid JSON-LD, meaningful content, sitemap/robots, noindex on JSON/login/backoffice, publication behavior and social images.

## Locale checks

Test first visit with browser locale, trusted country fallback, explicit URL override, persisted manual choice, unknown/Tor fallback, spoofed country ignored, correct URL/cache/head metadata and visible switch without loops.

## Analytics/consent checks

Use network tools, Tag Assistant and DebugView to prove no optional tag before choice, correct Reject/Customize/Accept/revoke behavior, one initial and one Inertia page view, no accidental slider/tab page views, event contract, persistence, legal facts and CSP compatibility.

## Security checks

Verify production route allowlist, dev/demo absence, authorization matrix, rate limits, secure cookies/headers, safe errors, secrets absent, uploads/signed storage, dependency audits and debug disabled.

## Performance and accessibility checks

At representative widths/locales verify Lighthouse/PageSpeed, LCP/INP/CLS, image selection/priority, no layout shift, keyboard/focus, reduced motion, landmarks/labels, contrast, 200% zoom, short viewport, fully visible Key Indicators content and favicon/tab/mobile icon.

## Data/cache/content checks

Verify countdown ordering/rollover, scheduled News publication boundary, locale cache isolation, latest News dedup/order, Signal Activity semantics, observer invalidation, content refresh/alerts, accurate sitemap `lastmod` and no production fresh seed.

## Infrastructure and recovery checks

Evidence immutable release, production dependencies, dependency connectivity, supervised workers/scheduler/SSR, health/readiness, backup/restore, rollback artifact, migration compatibility, live logs/metrics/alerts and external uptime.

## Production smoke surface

On final domain check Home in default/alternate locale, direct countdown, About/legal, language resolution, sitemap/robots, favicon assets, consent, one analytics event after consent, login/backoffice permission, JSON noindex, safe errors, headers and release version.

## Post-launch monitoring

Watch 5xx/latency, auth errors, queue/scheduler/content refresh, SSR, cache regeneration, CSP reports, analytics duplicates, Core Web Vitals, Search Console canonical/mobile/indexing, sitemap parsing, source freshness, provider spend and backups.

Define rollback triggers before launch.

## Evidence retention

Store release version, build/test/audit outputs, route/scheduler/SSR evidence, non-secret environment matrix, migration/backup/restore evidence, Lighthouse/security/consent results, Search Console verification, decision/residual risks and post-launch observations.

## Final GO conditions

GO only when all P0 and required P1 gates pass, no unexplained red result remains, production config is verified, backup/rollback/monitoring are credible, legal/consent facts complete, locale/canonical/initial HTML correct, security passes, performance/accessibility within budgets and responders are available.

## Stop conditions

- release changes after verification without rerun;
- full suite/build red;
- manual QA only evidence;
- production differs from reviewed environment;
- no rollback/backup/monitoring;
- tracking/legal or SEO locale unresolved;
- favicon/runtime references `z-docs`;
- dev package/hot/secret artifact in release.
