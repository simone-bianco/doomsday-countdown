# Chapter 4: Analytics, Consent & Legal Facts

## Load when

Connecting Google Analytics, Google Tag Manager, consent mode, events, Core Web Vitals reporting, marketing tags, privacy/cookie policy or consent QA.

## Core idea

Analytics is not ready because a script loads. It is ready when purpose, consent, event ownership, privacy disclosures, retention, debugging and revocation are all proven in production-like conditions.

Always load the dedicated `privacy-consent` skill for implementation details.

## Existing project boundaries

Verify current paths:

- `resources/js/Consent/tracking.ts`
- `resources/js/Consent/googleConsent.ts`
- `resources/js/Consent/consentStorage.ts`
- `resources/js/Components/Consent/CookieConsentBanner.vue`
- `resources/js/Pages/Doomsday/LegalPolicy.vue`

The current architecture already supports consent-gated GTM/GA4 and Inertia page views. Preserve it unless a verified redesign is approved.

## Production identifiers

Required external facts:

- final production domain;
- GA4 account/property/web stream;
- GTM container if used;
- data retention setting;
- Google Signals/ads personalization decision;
- internal traffic filters;
- referral exclusions/cross-domain settings if applicable;
- Basic or Advanced Consent Mode decision;
- legal entity/controller and vendor disclosures.

Never invent IDs or place real IDs in committed source.

## One page-view owner

The project must have exactly one documented page-view strategy.

Verify:

- one initial page view;
- one page view for each true Inertia URL navigation;
- no duplicate Enhanced Measurement/history-change event;
- local tab/slider state does not become a page view unless intentionally modeled as another event;
- no event before required consent;
- revocation blocks future events.

When GTM owns GA configuration, avoid also enabling an independent direct GA initial page view.

## Event taxonomy

Every event needs business question, event name, trigger, parameters/cardinality, consent category, owner, reporting destination, retention and QA case.

Useful low-cardinality events may include:

- `countdown_open`
- `countdown_tab_view`
- `projection_source_click`
- `news_source_click`
- `latest_news_slide_view`
- `latest_news_click`
- `initiative_click`
- `language_change`
- `methodology_click`
- Core Web Vitals measurements.

Do not send prompts/free text, secrets, backoffice values, personal identifiers, uncontrolled high-cardinality URLs or anything without a reporting use case.

## Consent Mode contract

Before optional tags load:

- default consent state is established;
- necessary storage remains available only as required;
- analytics/advertising storage follow user choice;
- GTM/GA tags respect current consent;
- updates occur after Accept/Reject/Customize;
- revocation prevents future optional tracking;
- cookies are removed where technically possible and documented.

Basic vs Advanced Consent Mode is a business/legal/privacy decision. Do not silently change modes.

## Legal facts gate

Privacy/cookie policies cannot contain placeholders in production.

Required verified facts:

- controller/legal entity name;
- address/contact/privacy email or DPO;
- hosting and key subprocessors;
- Google services actually enabled;
- purposes and lawful bases;
- cookie/storage inventory;
- retention periods;
- international transfer safeguards;
- withdrawal/revocation method;
- rights and complaint authority;
- policy version/effective date;
- translation policy for all supported locales.

Engineering verifies technical behavior; qualified business/legal owners approve legal accuracy.

## GTM governance

A production container needs least-privilege access, workspace/version naming, review/publish approval, environment separation, rollback, tag inventory, consent settings, custom-HTML controls and release-linked change logs.

## QA matrix

Use clean profiles and verify network/cookies:

- no optional request before choice;
- Reject blocks analytics/marketing;
- Analytics-only loads only analytics;
- Marketing behavior matches policy;
- Accept All updates expected signals;
- revocation prevents future events;
- refresh persists choice;
- Tag Assistant sees correct state;
- GA4 DebugView receives exactly intended events;
- Inertia navigation does not duplicate page views;
- consent/settings work in supported locales;
- CSP permits only approved endpoints.

## Search/measurement integration

After production domain verification, connect Search Console and GA4 where useful. Do not treat Analytics installation as a ranking factor. Use RUM to observe Core Web Vitals.

## Stop conditions

- policy placeholders;
- vendor/retention facts unknown;
- trackers fire before consent;
- duplicate page views;
- no revocation path;
- GTM publish governance absent;
- environment IDs mixed;
- CSP requires unsafe wildcard expansion without review.

## Official basis

See `references.md`: Google Consent Mode setup/debugging, GA4 privacy guidance and the project `privacy-consent` skill.
