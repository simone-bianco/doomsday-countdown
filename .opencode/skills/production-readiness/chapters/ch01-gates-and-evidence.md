# Chapter 1: Launch Gates & Evidence

## Load when

Starting a production-readiness audit, deciding GO/NO-GO, reviewing a release candidate, or translating findings into evidence requirements.

## Core idea

Production readiness is a set of independently verifiable gates. A release is approved only when every required gate has an owner, evidence, a current result and a rollback or stop condition. “Implemented”, “works locally” and “tests pass” are inputs, not approval.

## Gate model

### P0 — launch blockers

A P0 blocks public production when any of these is unknown or failing:

- public unauthenticated cost-bearing, destructive or upload routes;
- missing backoffice authorization or brute-force protection;
- debug mode, local URLs, development servers or dev packages in release;
- missing legal controller/contact/vendor facts while tracking is enabled;
- no canonical/indexing strategy for public pages;
- wrong document language or non-crawlable initial public content;
- no backup/restore evidence;
- no rollback path;
- no health/alert path for critical processes;
- missing secure production secrets and HTTPS/session configuration;
- destructive seed/fresh-migration dependency for deployment.

### P1 — mandatory for the stated organic-growth objective

P1 is required before calling the application search/growth-ready:

- sitemap and Search Console submission;
- self-canonical and alternate locale URLs;
- localized metadata and social previews;
- accurate structured data;
- SSR or an equivalent verified initial-render strategy;
- responsive optimized images and Core Web Vitals budgets;
- scheduled content refresh and freshness alerts;
- editorial identity, methodology and correction policy;
- consent-tested GA4/GTM and RUM.

### P2 — maturity and scaling

P2 becomes necessary based on traffic or business needs:

- multi-node cache/queue/storage topology;
- high availability and advanced disaster recovery;
- server-side tagging;
- experimentation platform;
- automatic social-preview generation;
- advanced content/search analytics;
- IndexNow, feeds and distribution automation.

## Evidence rules

For every claim record:

- requirement;
- environment;
- source file/config/route;
- command or runtime probe;
- result and exit status;
- timestamp;
- owner or responsible system;
- residual risk;
- stop condition;
- rollback or mitigation.

A report from another agent is context, not definitive proof. The codebase, deployed environment and external service consoles are the source of truth.

## Required pre-audit discovery

Inspect at minimum:

- `AGENTS.md` and relevant skills;
- current routes with middleware;
- `.env.example` and production config contract;
- Composer/npm dependencies and audits;
- public shell/head/SSR entry;
- consent runtime and policies;
- cache, observers, queues and scheduler;
- public assets and build output;
- authentication/authorization policies;
- tests and CI/deploy definitions;
- backup, monitoring and rollback documentation;
- active execution artifacts and unrelated working-tree state.

## Anti-patterns

- **Percentage theater**: a readiness score without gate evidence.
- **Green-build approval**: approving production because `npm run build` passes.
- **Checklist laundering**: marking an item complete because a file exists.
- **Local-only proof**: assuming local Herd/SQLite/array cache behavior represents production.
- **Vendor assumption**: inventing GTM IDs, cloud regions, legal entity facts or alert destinations.
- **Security by robots.txt**: using crawler directives as access control.
- **SEO by metadata volume**: adding every possible tag without a canonical/rendering contract.

## GO criteria

A GO decision requires:

- all P0 rows PASS with current evidence;
- P1 rows required by launch objective PASS or explicitly deferred with approved impact;
- production route allowlist confirmed;
- production build artifact identified;
- database migration/patch state known;
- backups and restore evidence current;
- rollback decision and trigger documented;
- monitoring and alert receivers active;
- legal/consent facts signed off by the correct business/legal owner;
- final public smoke checks from the real domain.

## NO-GO criteria

Return NO-GO when:

- critical evidence is unavailable;
- an environment differs materially from what was tested;
- a required process is manual but undocumented/unowned;
- a production secret is in repository/build artifacts;
- the app depends on dev routes/packages;
- SEO language/canonical behavior is contradictory;
- tracking fires before consent;
- no one can restore or roll back safely.

## Connects to

- ch02 for search/indexing gates.
- ch04 for analytics/legal gates.
- ch05 for security P0s.
- ch07 for deploy/backup/rollback evidence.
- ch09 for final release verification.
