# Chapter 6: Performance, Assets & Favicon

## Load when

Optimizing Core Web Vitals, images, frontend bundles, caching/CDN, skeletons, fonts, LCP/CLS/INP or installing the text-free browser-tab/app icon.

## Core idea

Performance is a user-visible contract measured on real devices, not just a compressed bundle size. Assets must reserve geometry, load at the correct priority and be cached predictably. The favicon must be generated as a complete browser/platform set from an approved source.

## Performance budgets

Define budgets per public template for:

- LCP, INP and CLS;
- initial JS/CSS transfer;
- above-fold image transfer;
- critical request count;
- server response time;
- route/data latency;
- long tasks and hydration cost.

Use laboratory checks and real-user monitoring by page, locale, device and release. A budget needs a failure threshold and release owner.

## Images

Required strategy:

- AVIF and WebP variants where supported;
- PNG/JPEG fallback only where needed;
- responsive `srcset` and `sizes`;
- explicit width/height or aspect ratio;
- correct crop/object-fit;
- lazy loading below fold;
- only true LCP image preloaded/prioritized;
- local fallback for remote previews;
- immutable versioned URLs;
- CDN/image service if approved;
- no simultaneous unnecessary mobile+desktop hero download.

Do not alter approved artwork composition without product approval.

## Layout stability

Reserve final geometry for countdown images, Latest News carousel/skeleton, charts, consent banner, navigation loaders, fonts, embeds and dynamic evidence blocks. A spinner inside a collapsing container is not a layout-stability solution.

## JavaScript and CSS

Review main bundle/route chunks, motion/chart libraries, backoffice splitting, duplicate libraries, unused CSS, source-map policy, SSR/client parity, hydration work, consent-gated third parties, Brotli/Gzip and immutable asset headers.

Do not add a library when a small domain component can use existing dependencies.

## Fonts

If custom fonts are used:

- host/license correctly;
- subset needed ranges;
- preload only critical weights;
- use appropriate `font-display`;
- verify all eight locales;
- define fallback metrics to reduce shift.

## Public HTML/data caching

Application, browser and CDN cache are separate layers. Verify locale/auth/session variation, no public caching of authenticated responses, active countdown/news boundaries, asset immutability, purge/versioning, and no locale collapse at CDN level.

## Favicon source

Approved source currently exists at:

`z-docs/images/logo_without_text.png`

[DA VERIFICARE] Confirm the latest user-supplied file and visual approval before generating production assets.

Do not link `z-docs` directly from HTML. It is a source/reference location, not a public runtime asset.

## Favicon/app icon asset family

Generate from the text-free square-safe logo:

- `public/favicon.ico` with common embedded sizes where tooling supports it;
- `public/favicon.svg` when artwork remains clear/compatible;
- `public/favicon-16x16.png`;
- `public/favicon-32x32.png`;
- `public/apple-touch-icon.png` at 180×180;
- `public/icons/icon-192.png`;
- `public/icons/icon-512.png`;
- optional maskable variants with safe-zone padding;
- `public/site.webmanifest` when install/app metadata is in scope.

Exact filenames may follow an approved existing convention; keep head tags and manifest consistent.

## Favicon design constraints

- transparent or approved solid background;
- square canvas;
- no text;
- recognizable at 16×16;
- safe padding;
- no thin detail that disappears;
- visible in light/dark browser UI;
- no unapproved recoloring;
- correct MIME/dimensions;
- no oversized original served for every icon.

## Head integration

In `resources/views/app.blade.php` or approved server-rendered head include verified tags for SVG/PNG/ICO icon, Apple touch icon, manifest, approved theme color and optional mask icon only when a proper monochrome asset exists.

Use stable versioned paths when replacing icons to avoid browser cache confusion.

## Favicon validation

Verify Chrome, Firefox, Safari and Edge tabs; private windows; light/dark browser UI; mobile home-screen icon if in scope; readability at 16×16; 200/content types; production references only public assets; cache invalidation; and separation from social-preview images.

## Performance tests

Required evidence:

- asset inventory before/after;
- responsive selection at representative widths/DPR;
- no CLS from images/carousel/charts/consent;
- Lighthouse mobile/desktop;
- RUM Core Web Vitals;
- CDN/browser headers;
- bundle/chunk analysis;
- no dev server/hot file;
- favicon link and asset checks.

## Stop conditions

- multi-megabyte above-fold PNG without responsive alternative;
- missing dimensions cause shift;
- both hero variants download unnecessarily;
- skeleton differs from final geometry;
- favicon uses text, is clipped or unreadable at 16×16;
- `z-docs` referenced at runtime;
- production build uses Vite dev server;
- no Core Web Vitals baseline/budget for growth launch.

## Official basis

See `references.md`: MDN icon/preload references and Google mobile-first/Core Web Vitals/Search guidance.
