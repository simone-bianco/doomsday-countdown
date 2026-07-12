# Production Rollback Runbook

Status: **BLOCKED until a previous immutable artifact, database compatibility decision and operator are identified.**

Rollback must use reviewed immutable artifacts. Never repair production by editing the live working tree.

## Rollback triggers

Initiate the rollback decision when any approved threshold is met, including:

- sustained elevated 5xx or latency;
- login/backoffice authorization failure;
- privacy/consent or analytics violation;
- broken canonical, locale, sitemap or noindex behavior;
- client/SSR asset mismatch or empty initial HTML;
- migration/data corruption;
- queue backlog or worker failure;
- critical public UI regression.

Exact thresholds, receiver and decision owner: `[DA VERIFICARE]` — **BLOCKED**.

## Required evidence before rollback

- current release identifier;
- previous immutable artifact identifier and checksum;
- database schema/data compatibility analysis;
- decision: application rollback, forward fix or coordinated data restore;
- cache/asset version and purge behavior;
- web/queue/SSR restart procedure;
- communication and incident owner.

Do not automatically reverse migrations. A previous application artifact may be incompatible with the current database; that decision requires Engineer/operator review.

## Application rollback sequence

1. Stop further release changes.
2. Preserve logs, release identifiers and incident evidence.
3. Select the previously verified immutable artifact.
4. Confirm database compatibility or approve a forward fix/restore plan.
5. Atomically reactivate the previous artifact using the hosting platform procedure `[DA VERIFICARE]`.
6. Reload web runtime and restart active queue/SSR processes using platform procedures `[DA VERIFICARE]`.
7. Apply only the reviewed cache/asset invalidation strategy.
8. Run the rollback smoke matrix.

## Rollback smoke matrix

Required checks:

- health/readiness;
- Home and alternate locale;
- direct countdown and timer;
- About/legal;
- sitemap/robots/canonical;
- login and non-admin denial;
- JSON noindex;
- client assets and SSR initial HTML;
- consent behavior and analytics inactivity/approved activation.

Real rollback drill and smoke outputs: `[DA VERIFICARE]` — **BLOCKED**.

## Data restore

Data restore is not an automatic rollback step. Use `backup-restore.md`, identify the restore point and obtain explicit approval. A restore without tested evidence remains **BLOCKED**.

## Completion rule

Rollback is complete only when the previous release is identified, health/smoke checks pass, monitoring is stable and the incident owner records the decision and residual data risk.
