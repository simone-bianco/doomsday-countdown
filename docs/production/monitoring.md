# Production Monitoring and Alert Contract

Status: **BLOCKED — monitoring vendors, receivers, thresholds and runbooks are not configured.**

This file specifies signals and evidence. It does not claim that external monitoring, SSR health or alert delivery exists.

## Required observability

| Surface | Signal | Required evidence | Receiver/runbook | Status |
|---|---|---|---|---|
| Web availability | external Home and health probes with stable marker/latency | live probe history and test alert | `[DA VERIFICARE]` | BLOCKED |
| HTTP errors | 4xx/5xx rate and grouped exceptions | centralized release-tagged errors | `[DA VERIFICARE]` | BLOCKED |
| Latency | p50/p95/p99 by route | dashboard and alert threshold | `[DA VERIFICARE]` | BLOCKED |
| Database | availability, latency, connections | platform metrics and failure alert | `[DA VERIFICARE]` | BLOCKED |
| Cache | errors, hit/miss and regeneration | metrics and outage runbook | `[DA VERIFICARE]` | BLOCKED |
| Queue | depth, age, runtime and failures if active | worker/backend metrics | `[DA VERIFICARE]` | BLOCKED |
| Scheduler | backup run/cleanup/monitor cadence | system cron, `schedule:list` and last-run evidence | `[DA VERIFICARE]` | IMPLEMENTED / BLOCKED |
| SSR | process availability, latency and initial HTML | supervised process probe and logs | `[DA VERIFICARE]` | BLOCKED |
| Assets/CDN | manifest/asset errors | real-domain asset smoke | `[DA VERIFICARE]` | BLOCKED |
| Content freshness | last successful refresh and stale threshold | approved cadence and alert | `[DA VERIFICARE]` | BLOCKED |
| Backups | age, success, cleanup, health and restore drill | Spatie monitor plus off-host platform evidence | `[DA VERIFICARE]` | IMPLEMENTED / BLOCKED |
| Domain/certificate | expiry and redirect behavior | external certificate/domain probe | `[DA VERIFICARE]` | BLOCKED |
| Search | sitemap/canonical/indexing errors | Search Console evidence | `[DA VERIFICARE]` | BLOCKED |
| Analytics | duplicate/missing consented page views | Tag Assistant/DebugView evidence | `[DA VERIFICARE]` | BLOCKED |

## External synthetic surface

Required probes should cover:

- Home default and alternate locale;
- representative published countdown;
- About and legal page;
- sitemap and robots;
- health/readiness endpoint;
- JSON endpoint with noindex header;
- meaningful SSR initial HTML and metadata when SSR is required.

A `200` alone is insufficient; each probe needs a stable content/header marker and an approved latency threshold.

## Alert evidence

Every alert needs:

- condition and threshold/window;
- severity;
- receiver and escalation;
- runbook and expected action;
- silence/maintenance behavior;
- delivered test-alert evidence;
- release/environment tags.

No receiver or threshold is invented here. All remain `[DA VERIFICARE]` and **BLOCKED**.

## Logging constraints

Do not log passwords, tokens, provider keys, consent identifiers, raw uploaded content or unnecessary IP/location history. Logs must identify environment and release without exposing secrets.

## Content operations

No content-refresh task is scheduled because cadence, rate limits, cost ceiling, overlap policy and failure receiver are not approved. `php artisan schedule:list` must contain only the approved backup run/cleanup/monitor tasks. Content freshness remains **BLOCKED** until its separate facts exist.

## Completion rule

Monitoring passes only with live external probes, centralized errors/logs, accountable receivers, tested alerts and runbooks. `php artisan inertia:check-ssr` is a diagnostic, not proof of supervised production SSR health.
