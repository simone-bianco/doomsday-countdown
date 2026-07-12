# Chapter 8: Observability & Content Operations

## Load when

Adding or reviewing error tracking, logs, metrics, alerts, uptime, health checks, queue/scheduler monitoring, external-source refresh, editorial freshness or incident response.

## Core idea

A production system is ready only when failures are visible before users report them and operators know what action to take. Content freshness is an operational dependency for this application, not a one-time seed concern.

## Logs

Centralize structured logs for request failures, auth/authorization events, content refresh/source failures, queue jobs, cache regeneration, SSR and provider/API failures, always tagged with environment/release.

Do not log passwords, tokens, provider keys, raw consent identifiers, uploaded file contents, sensitive prompts/form content or unnecessary IP/location history.

## Errors

Error tracking must include environment/release, server-side stack, route/component context, lawful/minimized user context, grouping/alerts, deploy correlation, source-map policy and protected access.

## Metrics

Measure:

- request rate and p50/p95/p99 latency;
- 4xx/5xx rate;
- DB query latency/connections;
- cache hit/miss/error/regeneration;
- queue depth/age/runtime/failures;
- scheduled task success/duration;
- source refresh success/candidates/rejects;
- SSR health/latency;
- asset/CDN errors;
- Core Web Vitals;
- backup age/success;
- sitemap/Search Console errors.

## Alerts

Every alert needs condition, threshold/window, severity, receiver, runbook, expected action, silence/escalation and test evidence.

Essential alerts:

- site/health down;
- sustained 5xx or latency;
- DB/cache unavailable;
- queue backlog/failed jobs;
- scheduler missed;
- content refresh stale/failing;
- SSR down if required;
- backup failed/stale;
- certificate/domain expiry;
- disk/storage pressure;
- analytics/Search Console anomaly;
- provider cost/abuse anomaly.

## Uptime and synthetic checks

External checks should cover Home, representative countdown, sitemap, robots, health, a noindex JSON header, SSR/initial metadata if critical, and optional protected backoffice synthetic.

A 200 alone is insufficient; assert a stable marker and latency threshold.

## Content-source operations

The content-source refresh command needs a production contract:

- schedule/cadence;
- source timeout/rate limit;
- retry/backoff;
- overlap prevention;
- dedup;
- approval/auto-publish rule;
- stale-source threshold;
- failure alert;
- last successful refresh;
- inserted/rejected audit;
- rollback/correction path;
- source terms/licensing constraints.

Do not fetch external sources during Home rendering.

## Editorial freshness

Record or expose factual last content update, source refresh, methodology review, current assessment timestamp, stale warning and correction history where appropriate.

Do not set `lastmod` or “updated” because a page was requested or cache regenerated.

## Incident readiness

For each P0 service establish detection, triage owner, safe mitigation, rollback/failover, communication, evidence preservation and post-incident correction. Security, privacy and data-loss incidents need distinct escalation.

## Dashboards

Useful dashboards cover availability/latency/errors, cache/DB, queue/scheduler, content freshness, Core Web Vitals, search health, analytics, provider usage and backups. Avoid dashboards with no decision or alert attached.

## Tests and drills

Verify alert delivery, backup restore, failed-job handling, missed scheduler detection, source timeout/failure, SSR failure behavior, release correlation, non-leaking health response and rollback smoke.

## Stop conditions

- no external uptime check;
- no centralized errors;
- scheduler/content refresh unmonitored;
- silent backup failure;
- no receiver/runbook for critical alert;
- source freshness unmeasurable;
- logs contain secrets;
- production release version unidentified.

## Connects to

- ch07 for process/backup topology.
- ch09 for post-launch monitoring.
- ch04 for analytics/RUM privacy.
