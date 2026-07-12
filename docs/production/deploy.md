# Production Deploy Runbook

Status: **BLOCKED until hosting, secret source, database, process supervision and operators are identified.**

This runbook defines command order and evidence. It does not authorize a deployment, production migration or infrastructure change.

## 1. Build an immutable release candidate

Build in an isolated CI/build workspace, never in the live application directory.

1. Acquire the reviewed revision and record revision, checksum and build timestamp.
2. Install locked PHP dependencies needed by generators and tests in the build workspace.
3. Install locked Node dependencies with `npm ci`.
4. Generate frontend contracts in this order:

   ```text
   php -d memory_limit=1G artisan typescript:transform
   php -d memory_limit=1G artisan form-bridge:generate
   ```

5. Verify generated diffs are expected and reviewed.
6. Build client assets with `npm run build`.
7. Build SSR assets with the reviewed Stream C SSR script (`npm run build:ssr` expected by design; confirm the final script name before acquisition).
8. Run the Engineer-owned integrated test/build/audit gates.
9. Assemble a clean runtime artifact with production Composer dependencies only:

   ```text
   composer install --no-dev --prefer-dist --no-interaction --optimize-autoloader
   ```

10. Include generated DTO/form artifacts, client manifest/assets, SSR manifest/bundle, migrations/patches and approved runtime source.
11. Explicitly acquire the approved runtime community sources:
    - `resources/js/Components/Doomsday/CommunityLinks.vue`, including the `header | about` placement contract and icon-only `h-9 w-9` header branch;
    - `resources/js/Components/Doomsday/SiteHeader.vue`, including `<CommunityLinks placement="header" />` outside the primary navigation;
    - `resources/js/Components/Doomsday/AboutClosingBand.vue`, retaining Patreon followed by the full CommunityLinks cards.
12. Retain `tests/Unit/DoomsdayPublicCopyTest.php` as release evidence, not as a required runtime file.
13. Exclude local/dev files listed in `environment-matrix.md`.

Artifact builder/platform: `[DA VERIFICARE]` — **BLOCKED**.

## 2. Supply production environment

The secret/environment system must supply values before Laravel optimization:

- `APP_ENV=production`;
- `APP_DEBUG=false`;
- exact `APP_URL=https://doomsday-clock.com`;
- non-empty application key from the approved secret source;
- secure and HTTP-only session cookie settings;
- explicit cache prefix containing the production environment identity;
- real database/cache/session/queue/storage connections;
- encrypted off-host backup disk, archive password and monitored notification email;
- backup schedule timezone/time fixed to `Europe/Rome` / `03:00`;
- GA/GTM IDs blank unless legal/controller/contact/vendor/retention facts and consent QA are complete.

Secret source and operator: `[DA VERIFICARE]` — **BLOCKED**.

## 3. Validate the release artifact

Run inside the immutable runtime artifact after the production environment is loaded:

```text
php artisan production:validate
```

A non-zero exit blocks deployment. The command validates repository/release facts only. It does not prove external backup, monitoring, TLS or SSR health.

## 4. Backup and migration gate

Before any reviewed schema/data change:

1. identify the exact migration/patch set;
2. review lock duration, compatibility and rollback/forward-fix strategy;
3. verify the system cron invokes `php artisan schedule:run` every minute;
4. run `php artisan backup:run` and retain archive/monitor evidence;
5. obtain current restore evidence;
6. record approval and maintenance/rolling strategy;
7. only then run the reviewed production migration command:

   ```text
   php artisan migrate --force
   ```

No fresh migration workflow and no secret-bearing production seed are allowed.

Migration approver, backup evidence and database compatibility: `[DA VERIFICARE]` — **BLOCKED**.

## 5. Optimize and activate

After production environment validation and approved migrations:

```text
php artisan optimize
```

Then perform platform-specific atomic activation and process restarts:

- web/PHP runtime reload: `[DA VERIFICARE]`;
- queue worker restart if active: `[DA VERIFICARE]`;
- Inertia SSR process restart: `[DA VERIFICARE]`;
- scheduler: install the hosting cron for `schedule:run`; verify backup 03:00, cleanup 03:30 and monitor 04:00 in Europe/Rome; content-refresh scheduling remains disabled.

Do not invent supervisor commands in this repository runbook.

## 6. Health and smoke evidence

Before traffic is accepted, capture current evidence for:

- application liveness/readiness;
- Home in default and alternate locale;
- a direct published countdown;
- About and legal pages;
- sitemap and robots;
- JSON noindex response;
- login and backoffice authorization;
- client asset loading and favicon;
- SSR meaningful initial HTML and metadata;
- secure cookies and headers;
- analytics absent before consent and one event after consent only when activated;
- `backup:list`, `backup:monitor` and current off-host archive evidence.

Real-domain probes and evidence owner: `[DA VERIFICARE]` — **BLOCKED**.

## 7. Completion rule

A repository-green validator is not launch approval. Deployment remains **BLOCKED** until environment, migration, backup/restore, process supervision, monitoring, legal/consent and real-domain smoke rows are current and approved.
