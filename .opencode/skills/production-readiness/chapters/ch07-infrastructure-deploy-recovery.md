# Chapter 7: Infrastructure, Deploy & Recovery

## Load when

Defining or reviewing production environment, database/cache/queue/storage topology, build artifacts, Laravel optimization, workers, scheduler, SSR, backups, migration safety or rollback.

## Core idea

A production release is not complete until the application, background processes, data and assets are deployed coherently and can be restored or rolled back. Environment and process evidence must describe the real hosting platform, not local defaults.

## Production environment contract

Required facts:

- final domain and HTTPS termination;
- origin/proxy/CDN topology;
- application runtime and supported PHP/Node versions;
- database engine, region, backups and recovery objective;
- cache/session/queue stores;
- file/object storage;
- mail provider if used;
- secret source;
- environment-specific APP_URL/cookie/cache prefixes;
- logging/error tracking destinations;
- worker/scheduler/SSR process supervision;
- scaling and maintenance expectations.

`.env.example` documents required variable names with safe placeholders, not production secrets.

## Release artifact

A release must identify:

- immutable code revision;
- locked Composer/npm dependencies;
- production PHP vendor tree without dev dependencies;
- Vite production manifest/assets;
- generated TypeScript/form artifacts;
- SSR bundle if used;
- database migration/patch version;
- build timestamp/version;
- source maps policy;
- excluded local/dev files such as `public/hot`, `.env`, seed secrets and bytecode.

Do not deploy a mutable developer working tree as the release artifact.

## Laravel optimization contract

Verify production-safe use of optimized Composer autoloading, config/route/event/view caches where compatible, application optimize/clear commands, storage/bootstrap permissions, and restart/reload behavior for long-running workers and SSR.

Never cache production config while local/development values are loaded.

## Database and data patches

Production rules:

- no `migrate:fresh`;
- no destructive seed to repair release state;
- migrations reviewed for locks, duration, reversibility and compatibility;
- data patches idempotent/guarded according to project patch skill;
- backup exists before destructive/high-risk change;
- rolling deployment compatibility considered;
- production migration force flag only inside approved automation;
- schema/data version observable.

## Queue workers

If queue-backed behavior exists:

- choose backend/queue names;
- supervise workers;
- define timeout, retry, backoff and failed-job handling;
- restart workers on release;
- monitor queue depth, age, failures and throughput;
- protect dashboards;
- ensure retry-safe/idempotent jobs.

Laravel Horizon is relevant only if Redis/Horizon is intentionally adopted.

## Scheduler

Production scheduler requires:

- declared/reviewed tasks;
- reliable infrastructure invocation;
- overlap prevention;
- multi-node single-server/lock behavior;
- explicit timezone understanding;
- failure notifications;
- manual recovery path;
- content-refresh cadence aligned with source limits.

`schedule:list` must match the expected contract.

## SSR process

When SSR is part of SEO readiness:

- bundle built in release;
- process supervised;
- health/logs available;
- restart on release;
- resource use monitored;
- fallback behavior known;
- client/SSR versions identical;
- no secret/private props exposed.

## Backups and restore

Required evidence:

- automated off-host encrypted backups;
- database plus required object/file storage;
- retention/deletion policy;
- access controls/key ownership;
- restore procedure tested;
- restore validated for schema, records, media and credentials;
- RPO/RTO;
- alert for backup failure/staleness.

A backup without restore proof is not a pass.

## Rollback contract

Define triggers such as elevated 5xx/latency, auth/backoffice failure, broken canonical/indexing, consent violation, migration/data corruption, queue buildup, SSR/asset failure or critical UI regression.

Rollback evidence includes previous artifact, DB compatibility/forward-fix decision, asset/cache behavior, process restart, health/smoke confirmation and communication owner.

## Health and readiness

Separate liveness, readiness and protected deep diagnostics. Readiness may check DB, cache, queue backend, required storage, deployed build version, SSR and migration state without leaking secrets.

## Environment verification

Confirm:

- `APP_ENV=production` and `APP_DEBUG=false`;
- correct HTTPS URL and Secure cookies;
- no local mail/log-only assumptions where real services are required;
- dev packages/routes absent;
- `public/hot` absent;
- writable paths correct;
- cache prefix/environment isolation;
- proxy/trusted-header behavior correct;
- production route list reviewed.

## Stop conditions

- topology unknown;
- no immutable artifact;
- no restore proof;
- rollback incompatible with migration;
- worker/scheduler/SSR manual and unowned;
- release requires `migrate:fresh` or local secret seed file;
- debug/dev routes/hot file present;
- production cannot be distinguished from local/staging.

## Official basis

See `references.md`: Laravel deployment, queues and scheduling documentation.
