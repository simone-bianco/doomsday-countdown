# Release Evidence Matrix

Release candidate: `[revision / artifact]`  
Environment: `[staging / production]`  
Production domain: `[DA VERIFICARE]`  
Review timestamp: `[UTC]`

| Gate | Priority | Expected evidence | Result | Evidence path/output | Residual risk | Stop condition |
|---|---|---|---|---|---|---|
| Production route allowlist | P0 | route list + middleware/policy review under `--no-dev` | BLOCKED | | | |
| Backoffice authorization | P0 | admin/non-admin tests | BLOCKED | | | |
| Login/AI/upload throttling | P0 | rate-limit tests/runtime | BLOCKED | | | |
| Legal/privacy facts | P0 | approved controller/vendor/retention details | BLOCKED | | | |
| Secure environment | P0 | APP_ENV/debug/URL/cookies/proxies | BLOCKED | | | |
| Secrets | P0 | secret source + build scan | BLOCKED | | | |
| Backup restore | P0 | recent restore proof | BLOCKED | | | |
| Rollback | P0 | previous artifact + DB compatibility + smoke | BLOCKED | | | |
| Monitoring/alerts | P0 | live receivers/test alerts | BLOCKED | | | |
| Sitemap/canonical/hreflang | P1 | initial HTML/XML/runtime | BLOCKED | | | |
| SSR/initial rendering | P1 | bundle/process/HTML/health | BLOCKED | | | |
| Analytics/consent | P1 | Tag Assistant/DebugView/network matrix | BLOCKED | | | |
| Locale first visit | P1 | explicit/browser/country/persistence tests | BLOCKED | | | |
| Performance | P1 | Lighthouse + RUM budgets | BLOCKED | | | |
| Accessibility | P1 | keyboard/zoom/AT/contrast evidence | BLOCKED | | | |
| Content freshness | P1 | scheduler/source alert/freshness | BLOCKED | | | |
| Favicon/app icons | P1 | tab/mobile asset verification | BLOCKED | | | |
| Full tests/build/audits | P0 | commands + exit status | BLOCKED | | | |
| Production smoke | P0 | real-domain checks | BLOCKED | | | |

## Decision

Status: `NO-GO / GO`  
Approver/evidence owner: `[DA VERIFICARE]`  
Open P0: `[...]`  
Approved residual risks: `[...]`  
Rollback trigger: `[...]`
