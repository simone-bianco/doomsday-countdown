---
name: production-readiness
description: "Use when preparing, reviewing, deploying, or approving this Laravel/Inertia/Vue application for production, including launch gates, SEO, sitemap and structured data, analytics and consent, first-visit locale resolution, favicon/app icons, security, performance, infrastructure, observability, backups, rollback, release evidence, and post-launch checks."
---

<!-- argument-hint: [gate, seo, locale, analytics, security, performance, favicon, deploy, observability, qa, or review] -->

# Production Readiness & Release
**Purpose**: Evidence-based production launch and growth-readiness for this project | **Chapters**: 9 | **Optimized**: 2026-07-12

## How to use this skill

- Read this root first.
- Load only the chapter matching the current concern.
- Load two chapters when a contract crosses boundaries, such as locale + SEO or Analytics + security headers.
- Use `cheatsheet.md` for the final gate review.
- Use `patterns.md` only when designing or reviewing an implementation.
- Use the templates to record evidence; do not treat unchecked boxes as proof.

## Core mental models

- **Production-ready means reversible, observable, secure and evidence-backed.** A green build alone is not a launch approval.
- **P0 findings are stop conditions.** Do not trade away authentication, legal facts, indexing correctness, backups or rollback for speed.
- **SEO is a rendering and URL contract, not a bag of meta tags.** Canonicals, locale URLs, initial HTML, content quality and performance must agree.
- **Analytics is consent-dependent infrastructure.** A tag must have a legal purpose, one owner, a consent rule, a CSP implication and a verified event contract.
- **First-visit locale is a hint, never permanent coercion.** Explicit URL and user choice beat browser or geolocation detection.
- **Location does not equal language.** Use trusted country information only as a fallback/tie-breaker, not as a reason to override an explicit preference.
- **A favicon is an asset family.** Generate tab, touch and manifest icons from the approved text-free source; never link `z-docs` directly.
- **Every release must be reconstructable and reversible.** The release artifact, environment, migration state, workers, scheduler, SSR and rollback evidence belong to one decision.
- **Do not invent readiness.** Mark missing vendor IDs, domains, legal details, infrastructure or measurements `[DA VERIFICARE]`.

## Chapter index

| # | Title | Load when |
|---|---|---|
| [ch01](chapters/ch01-gates-and-evidence.md) | Launch Gates & Evidence | starting a readiness audit or deciding GO/NO-GO |
| [ch02](chapters/ch02-seo-indexing-rendering.md) | SEO, Indexing & Initial Rendering | sitemap, canonical, hreflang, metadata, JSON-LD, robots, SSR |
| [ch03](chapters/ch03-locale-first-visit.md) | First-Visit Locale & Multilingual URLs | language detection, geolocation, persistence, `<html lang>` |
| [ch04](chapters/ch04-analytics-consent-legal.md) | Analytics, Consent & Legal Facts | GA4, GTM, Consent Mode, events, privacy/cookie policy |
| [ch05](chapters/ch05-security-access-control.md) | Security & Access Control | public routes, backoffice, rate limits, headers, secrets, uploads |
| [ch06](chapters/ch06-performance-assets-favicon.md) | Performance, Assets & Favicon | Core Web Vitals, images, bundles, CDN, tab/app icons |
| [ch07](chapters/ch07-infrastructure-deploy-recovery.md) | Infrastructure, Deploy & Recovery | environment, build, workers, scheduler, SSR, backups, rollback |
| [ch08](chapters/ch08-observability-content-operations.md) | Observability & Content Operations | logs, metrics, alerts, health, source refresh and freshness |
| [ch09](chapters/ch09-release-qa-post-launch.md) | Release QA & Post-Launch | CI gates, smoke tests, accessibility, Search Console and launch monitoring |

## Topic index

- **GO / NO-GO** → ch01, cheatsheet
- **production audit** → ch01, templates/release-evidence-matrix.md
- **sitemap.xml** → ch02
- **canonical / hreflang** → ch02, ch03
- **Schema.org / JSON-LD** → ch02
- **SSR / initial HTML** → ch02, ch07
- **first visit language** → ch03
- **Accept-Language** → ch03
- **country / IP geolocation** → ch03
- **favicon / browser tab icon** → ch06
- **GA4 / GTM / Consent Mode** → ch04
- **privacy policy / cookie policy** → ch04
- **RBAC / rate limit / CSP** → ch05
- **Core Web Vitals / AVIF / WebP / CDN** → ch06
- **queue / scheduler / backups / rollback** → ch07
- **monitoring / alerts / content refresh** → ch08
- **full suite / build / launch smoke** → ch09

## Project source-of-truth paths

Verify these paths before relying on them; paths may evolve:

- public shell: `resources/views/app.blade.php`
- public Inertia entrypoints: `resources/js/app.js`, `resources/js/ssr.js`
- public routes: `routes/web.php`
- public page/controllers: `app/Http/Controllers/Web/`
- public data/cache: `app/Services/Doomsday/`, `app/Services/Doomsday/Cache/`
- consent runtime: `resources/js/Consent/`, `resources/js/Components/Consent/`
- policy pages: `resources/js/Pages/Doomsday/LegalPolicy.vue`
- environment/config: `.env.example`, `config/`, `bootstrap/app.php`
- scheduler: `routes/console.php`
- approved favicon source: `z-docs/images/logo_without_text.png`
- production tests: `tests/Feature/`, `tests/Unit/`

## Supporting files

- [cheatsheet.md](cheatsheet.md) — compact final gate and diagnostic commands.
- [patterns.md](patterns.md) — locale resolver, SEO DTO, favicon matrix, noindex and release evidence patterns.
- [glossary.md](glossary.md) — production, SEO and operations terms.
- [references.md](references.md) — official sources checked for this skill.
- [templates/release-evidence-matrix.md](templates/release-evidence-matrix.md) — evidence record for GO/NO-GO.
- [templates/production-environment-matrix.md](templates/production-environment-matrix.md) — environment and process contract.

## Scope limits

This skill defines durable production gates and review contracts. It does not:

- replace qualified legal advice;
- choose a hosting, CDN, monitoring or analytics vendor without product approval;
- authorize production credentials, DNS changes, migrations or deployment;
- permit destructive commands or `migrate:fresh` in production;
- guarantee search ranking or rich results;
- authorize tracking before consent;
- treat an implementation report as proof without inspecting real files and runtime evidence.

Stop on missing production domain, legal entity, hosting topology, secret strategy, backup/restore evidence, authorization model or rollback contract.
