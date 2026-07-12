# Production Environment Matrix

Status: **NO-GO — external production evidence is BLOCKED**  
Canonical application origin: `https://doomsday-clock.com`  
Evidence owner: `[DA VERIFICARE]`  

Do not record secrets in this document. Record only the secret source, responsible system and validation evidence.

| Concern | Production contract | Repository source | Validation required | Owner/system | Status |
|---|---|---|---|---|---|
| Domain / HTTPS | Exact origin `https://doomsday-clock.com` | `config/production.php` | Real-domain TLS and apex response | `[DA VERIFICARE]` | BLOCKED |
| DNS / CDN / proxy | No provider selected in repository | external infrastructure | DNS, proxy headers, origin protection and `www` behavior | `[DA VERIFICARE]` | BLOCKED |
| PHP / web server | PHP `^8.4`; server topology unknown | `composer.json` | Runtime/version and web-server evidence | `[DA VERIFICARE]` | BLOCKED |
| Node/build runtime | Node version and build worker unknown | `package.json` | Reproducible client + SSR build environment | `[DA VERIFICARE]` | BLOCKED |
| Application secret source | `APP_KEY` and credentials supplied externally | environment contract | Secret source, rotation and access review | `[DA VERIFICARE]` | BLOCKED |
| Database / region | Provider, engine and region not selected | `.env.example` is local-only | Connectivity, encryption, migration and backup evidence | `[DA VERIFICARE]` | BLOCKED |
| Cache | Explicit production-isolated prefix required | `config/cache.php`, `config/production.php` | Store, region, availability and prefix proof | `[DA VERIFICARE]` | BLOCKED |
| Sessions | Secure and HTTP-only cookies required | `config/session.php` | Production response cookie inspection | `[DA VERIFICARE]` | BLOCKED |
| Queue / workers | Run only for actual queued jobs | queue config | Backend, queues, supervisor, retry/failure evidence | `[DA VERIFICARE]` | BLOCKED |
| Scheduler | Backup at 03:00 Europe/Rome; cleanup 03:30; monitor 04:00 | `routes/console.php`, `config/backup.php` | system cron every minute plus `schedule:list` and run evidence | hosting/operator `[DA VERIFICARE]` | IMPLEMENTED / BLOCKED |
| SSR process | Bundle required; supervised process required for GO | `vite.config.js`, Stream C output | Bundle, process supervisor, logs and real initial HTML | `[DA VERIFICARE]` | BLOCKED |
| Object/file storage | Provider and region unknown | filesystem config | Storage access, encryption and restore evidence | `[DA VERIFICARE]` | BLOCKED |
| Mail provider | No production provider selected | mail config | Product need and provider evidence | `[DA VERIFICARE]` | BLOCKED |
| GA4 / GTM | Both IDs stay blank until legal facts and consent QA pass | `.env.example`, consent runtime | Legal sign-off, Tag Assistant, DebugView and network matrix | `[DA VERIFICARE]` | BLOCKED |
| Error tracking | Vendor/receiver unknown | none | Live test event and protected access | `[DA VERIFICARE]` | BLOCKED |
| Logs / metrics | Destination unknown | logging config | Centralized release-tagged logs and metrics | `[DA VERIFICARE]` | BLOCKED |
| Uptime / alerts | Receiver/runbook unknown | none | External probes and delivered test alert | `[DA VERIFICARE]` | BLOCKED |
| Backups / retention | Spatie v10 configured; production requires encrypted off-host disk | `config/backup.php`, `backup-restore.md` | scheduled archive, notification, retention and restore drill | `[DA VERIFICARE]` | IMPLEMENTED / BLOCKED |
| RPO / RTO | No approved values | none | Business/infrastructure approval | `[DA VERIFICARE]` | BLOCKED |
| Rollback artifact | Previous immutable artifact required | `docs/production/rollback.md` | Artifact identifier and drill evidence | `[DA VERIFICARE]` | BLOCKED |
| Release version | Immutable revision/artifact required | release pipeline | Revision, checksum and build timestamp | `[DA VERIFICARE]` | BLOCKED |
| Security headers | Repository contract may exist; real proxy behavior unknown | security stream | Real-domain header capture | `[DA VERIFICARE]` | BLOCKED |
| Trusted geo header | Disabled until proxy trust is proven | locale stream | Trusted proxy/origin evidence | `[DA VERIFICARE]` | N/A/BLOCKED |
| Favicon/public assets | Repository stream supplies assets | asset stream | Browser/mobile smoke on real domain | `[DA VERIFICARE]` | BLOCKED |

## Process inventory

| Process | Release command/artifact | Supervisor | Health evidence | Restart on release | Alerts | Status |
|---|---|---|---|---|---|---|
| PHP web app | immutable application artifact | `[DA VERIFICARE]` | `/up` plus public smoke | `[DA VERIFICARE]` | `[DA VERIFICARE]` | BLOCKED |
| Queue worker | only if queues are active | `[DA VERIFICARE]` | queue depth/failures | `[DA VERIFICARE]` | `[DA VERIFICARE]` | BLOCKED |
| Scheduler | Laravel backup schedule | hosting cron `[DA VERIFICARE]` | `schedule:list`, latest backup and monitor | deploy restart N/A | backup failure receiver `[DA VERIFICARE]` | IMPLEMENTED / BLOCKED |
| Inertia SSR | `bootstrap/ssr/ssr.js` and SSR manifest | `[DA VERIFICARE]` | process + initial HTML probe | `[DA VERIFICARE]` | `[DA VERIFICARE]` | BLOCKED |
| Content refresh | no approved cadence | N/A | no scheduled task | N/A | N/A | BLOCKED |

## Release exclusions

`production:validate` requires the immutable runtime artifact to exclude local environment files, `public/hot`, bytecode, local key files, tests, `node_modules`, local agent artifacts, `z-docs`, development Composer packages and debug/test output.

Repository checkouts are expected to fail this release-artifact gate. Passing it requires a separately assembled immutable runtime artifact, not deletion from a developer working tree.
