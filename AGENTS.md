# AGENTS.md — doomsday-countdown

Quickstart and guardrails for AI coding agents working in this repository.

## Project snapshot

- Laravel 13 / PHP 8.4 public Inertia + Vue 3 application.
- Main product area: public Doomsday Countdown dashboard.
- Frontend entrypoint: `resources/js/app.js`.
- Public Inertia page: `resources/js/Pages/Doomsday/Home.vue`.
- Public domain components: `resources/js/Components/Doomsday/`.
- Backend public controllers: `app/Http/Controllers/Web/`.
- Doomsday data service/cache: `app/Services/Doomsday/` and `app/Services/Doomsday/Cache/`.
- Routes: `routes/web.php`.
- Tests: `tests/Feature/*Doomsday*`, `tests/Unit/DoomsdayPublicCopyTest.php`.

## Read first

Before touching code, read the relevant local skills.

Skill loading protocol:

1. Read the selected skill `SKILL.md` first.
2. Load only the chapter(s) matching the task; normally 1 or 2 chapters, but feel free to read more if necessary.
3. Use `cheatsheet.md` only for final checks.
4. Use `patterns.md` only when designing/refactoring.
5. Use `glossary.md` only for unclear terms.
6. Do not bulk-read an entire skill directory unless explicitly reviewing the skill itself.

Skill maintenance rule:

- Treat skills as living project knowledge. Update the relevant skill when the project adds packages, changes architecture/contracts/workflows, introduces new reusable patterns, or removes old conventions.
- Update skills when an AI agent makes a mistake because required project knowledge was missing, stale, ambiguous, or buried in the wrong place.
- Keep updates narrow and evidence-based: record current file paths, commands, contracts, stop conditions, and anti-patterns that would have prevented the mistake or helped future work.
- Do not dump task-specific implementation notes into skills; extract only durable rules, reusable patterns, catalog updates, or review/QA checks.

## Public Doomsday architecture

- Keep Laravel controllers thin; place public countdown composition in services.
- Use DTO/Data classes for FE/BE contracts and regenerate TypeScript when DTOs change.
- Use `CountdownCache` and centralized cache keys for public Doomsday data.
- Cache invalidation belongs in model observers; do not rely only on TTL for stable public data.
- Keep direct public pages routeable, including `/`, `/about`, and `/countdowns/{slug}`.
- Keep public UI free from AI/OpenAI/Agent/Login/Backoffice copy or links.

## Public Doomsday data-loading conventions

Backend expectations:

- Use read-only JSON endpoints for in-page public Doomsday data loading when a full Inertia page visit would hurt UX.
- Name public data routes and generate frontend URLs with Ziggy `route()`.
- Normalize locale through `CountdownPublicDataService::normalizeLocale()`.
- Return 404 JSON for unpublished or missing slugs.
- Reuse `CountdownCache`; do not add scattered `Cache::remember()` calls.
- Do not weaken observer-based cache invalidation.

Frontend expectations:

- Use axios only for read-only public Doomsday data loading.
- Backend mutations and forms must use project form/Inertia conventions, not ad-hoc axios/fetch.
- Do not change `window.location`, route URL, or browser history for in-page countdown switching.
- Section loaders should use local cache keyed by `slug:locale:section`.
- Guard stale responses: if the user clicks A then B and A resolves later, do not apply A to B.
- Guard stale tab payloads: section payload `countdown_slug` must match the active countdown slug before rendering.

## Ziggy and app shell requirements

`resources/views/app.blade.php` should expose Ziggy routes with `@routes`.

`resources/js/app.js` should:

- Import `ZiggyVue` from `../../vendor/tightenco/ziggy`.
- Register Ziggy before the Inertia plugin unless a verified technical blocker exists.
- Keep `ThemeProvider` with `defaultTheme: 'doomsday'`.
- Keep the CSS import order that preserves the Doomsday red theme override.
- Render a custom app-level navigation loader for real Inertia page changes.
- Keep local selection/tab skeletons separate from global page navigation loading.

## UI/component rules

- Prefer components from `@simone-bianco/vue-ui-components` over native form/button primitives.
- Icons must come from `lucide-vue-next` as component bindings, for example `:icon="ExternalLink"`, never lowercase string icons such as `icon="external-link"`.
- Home/About labels remain invariant across locales.
- Keep pages as orchestrators; move domain UI into `resources/js/Components/Doomsday/`.
- Keep premium dark/red Doomsday visual language; avoid generic gray skeletons.
- Countdown visual assets should be art-only: no baked-in localized text and no UI icons. Render title/subtitle in Vue from backend-localized data.
- Do not replace product-approved visual assets without explicit approval; optimize images only when requested.

## Commands

Run from repository root.

```bash
php artisan test --stop-on-failure
npm run build
php artisan route:list
```

Run these when DTO/Data classes change:

```bash
php -d memory_limit=1G artisan typescript:transform
php -d memory_limit=1G artisan form-bridge:generate
```

If package dependencies change, keep `package.json` and `package-lock.json` consistent.

## Environment notes

- Preferred local URL when Herd is healthy: `https://doomsday-countdown.test`.
- Vite dev server alone is not the Laravel app; `npm run dev` only serves Vite assets.

## Do not do

- Do not commit or push unless explicitly asked.
- Do not use Opencode or browser automation unless explicitly allowed by the user.
- Do not introduce JSON endpoints for mutations.
- Do not bypass `CountdownCache` for Doomsday public data.
- Do not remove direct `/countdowns/{slug}` page support.
- Do not replace or optimize product-approved images unless explicitly asked.
