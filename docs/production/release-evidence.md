# Production Release Evidence Matrix

Release candidate: `[revision / immutable artifact — DA VERIFICARE]`  
Environment: `production`  
Production domain: `https://doomsday-clock.com`  
Review timestamp: `[UTC — DA VERIFICARE]`  
Decision: **NO-GO**

Repository implementation is evidence, not public launch approval.

| Gate | Priority | Expected evidence | Current result | Evidence/source | Residual risk / stop condition |
|---|---|---|---|---|---|
| Production validation command | P0 | `production:validate` exit 0 on immutable no-dev artifact | IMPLEMENTED / runtime BLOCKED | `config/production.php`, command tests | Local checkout intentionally contains exclusions/manifests missing |
| Exact secure environment | P0 | production env, debug false, exact HTTPS origin, key, secure cookies, cache prefix | BLOCKED | validator contract | Real secret/proxy/cookie evidence absent |
| Client + SSR artifacts | P1 | non-empty valid manifests and SSR bundle from same revision | BLOCKED | validator contract | Integrated builds not run by Stream E |
| Generated artifacts | P0 | DTO/form outputs present with reviewed markers | IMPLEMENTED / final generation BLOCKED | validator contract | Engineer must regenerate and compare after all streams |
| Production route allowlist | P0 | no demo/dev route marker under production no-dev runtime | BLOCKED | validator + `route:list` diagnostic | Security stream/integrated artifact pending |
| Release exclusions | P0 | no env/hot/pyc/local keys/dev packages/dev artifacts | BLOCKED | validator contract | Current developer checkout contains known exclusions |
| Backoffice authorization | P0 | admin/non-admin/final-admin evidence | BLOCKED | security stream | Initial admin identity and rollout unknown |
| Login/rate limiting | P0 | focused tests and production route middleware | BLOCKED | security stream | Integrated review pending |
| Legal/privacy facts | P0 | approved controller/contact/vendor/retention | BLOCKED | Legal policy still contains placeholders | GA/GTM must remain blank |
| Analytics/consent | P1 | no pre-consent tags, one page view, revoke, DebugView | BLOCKED | consent runtime approved; external QA absent | Validator fails closed if an ID is active with placeholders |
| Locale/SEO/sitemap | P1 | canonical/hreflang/initial HTML/XML on real domain | BLOCKED | locale/SEO stream | Real-domain evidence absent |
| SSR/initial rendering | P1 | bundle, supervised process, health/logs, meaningful HTML | BLOCKED | SSR stream + operations | No production supervisor/probe |
| Responsive assets/favicon | P1 | generated assets and browser/mobile smoke | BLOCKED | asset stream | Integrated build/browser evidence absent |
| Full tests/build/audits | P0 | Engineer integrated commands with exits | BLOCKED | baseline predates production hardening | Stream E did not run full suite/build |
| Migration readiness | P0 | reviewed migration set, backup and compatibility | BLOCKED | deploy runbook | No production DB/backup/approver |
| Backup restore | P0 | daily encrypted off-host backup plus restore drill | IMPLEMENTED / BLOCKED | Spatie v10, schedule and `backup-restore.md` | Off-host disk, cron, notification, RPO/RTO and restore proof unknown |
| Rollback | P0 | previous artifact, DB decision and smoke drill | BLOCKED | `rollback.md` | Artifact/operator/drill absent |
| Monitoring/alerts | P0 | live probes, receivers and test alerts | BLOCKED | `monitoring.md` | Vendor/receivers/runbooks unknown |
| Content freshness | P1 | approved refresh cadence and failure alert | BLOCKED | scheduler contains backup tasks only | Content cadence/cost ceiling unapproved |
| Production smoke | P0 | real-domain route/SEO/security/consent/assets smoke | BLOCKED | deploy runbook | No deployment or external probe |

## Approved acquisition set

The immutable acquisition must preserve these approved concurrent deltas:

| File | Acquisition role | Runtime artifact |
|---|---|---|
| `resources/js/Components/Doomsday/CommunityLinks.vue` | approved `header | about` Discord/Telegram component; icon-only 36×36 header branch and full About cards | REQUIRED |
| `resources/js/Components/Doomsday/SiteHeader.vue` | approved `<CommunityLinks placement="header" />` before Patreon/language and outside primary `<nav>` | REQUIRED |
| `resources/js/Components/Doomsday/AboutClosingBand.vue` | approved Patreon followed by full CommunityLinks cards | REQUIRED |
| `tests/Unit/DoomsdayPublicCopyTest.php` | regression evidence for header/About placement, exact URLs, secure attributes, 36×36 icons and primary-nav exclusion | EVIDENCE ONLY; runtime artifact may exclude tests |

Approved static URLs:

- `https://discord.gg/NmKXDzwzK`
- `https://t.me/doomsdayclockofficial`
- official Discord icon: `/images/community/discord.png`
- official Telegram icon: `/images/community/telegram.png`

These are **static HTTPS links**. Each is **not a secret**, **not an analytics integration**, and **not an external health dependency** for application readiness. No credential, bot, webhook, network probe or tracking event is part of this acquisition gate.

The approved placement is additive in both surfaces: icon-only controls in the public topbar and full cards in the About closing band. The header integration is exactly `<CommunityLinks placement="header" />`; it remains outside the primary `<nav>`.

Acquisition validation must confirm:

- both exact URLs;
- `_blank` and `noopener noreferrer`;
- `CommunityLinks.vue` retains the `header | about` contract, icon-only `h-9 w-9` header targets and labels hidden only in header mode;
- `SiteHeader.vue` retains `<CommunityLinks placement="header" />` outside the primary `<nav>`;
- CommunityLinks remains below Patreon in AboutClosingBand;
- the PublicCopy regression delta remains in the reviewed evidence set.

## Diagnostics to retain with the release decision

Record output and exit status for:

```text
php artisan production:validate
php artisan route:list --except-vendor
php artisan schedule:list
php artisan inertia:check-ssr
```

The SSR diagnostic is not a substitute for real supervised-process evidence.

## Remaining P0 blockers

- immutable artifact identifier and checksum;
- real production environment/secret source;
- production route/admin/security integrated proof;
- initial admin identity and migration rollout;
- controller/contact/vendor/retention legal facts;
- backup creation and restore drill;
- rollback artifact and drill;
- centralized monitoring, receivers and test alerts;
- real-domain smoke and secure header/cookie evidence;
- integrated full suite, client/SSR builds and audits.

## Remaining P1 blockers

- supervised SSR and meaningful real-domain initial HTML;
- real-domain canonical/hreflang/sitemap/Search Console evidence;
- consent network matrix, Tag Assistant and DebugView;
- responsive/browser/favicon QA and performance/accessibility evidence;
- approved content-refresh cadence and freshness alerting.

## Final decision

Status: **NO-GO**  
Approver/evidence owner: `[DA VERIFICARE]`  
Rollback trigger/owner: `[DA VERIFICARE]`  

GO requires every P0 row to pass with current production evidence and required P1 rows to pass or be explicitly approved for deferral.
