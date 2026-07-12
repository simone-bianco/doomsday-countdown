# Production Environment Matrix

Do not record secrets. Record source/location and validation evidence.

| Concern | Production choice | Source of truth | Validation | Owner/system | Status |
|---|---|---|---|---|---|
| Domain / HTTPS | | | | | BLOCKED |
| DNS / CDN / proxy | | | | | BLOCKED |
| Origin protection | | | | | BLOCKED |
| PHP / web server | | | | | BLOCKED |
| Node/build runtime | | | | | BLOCKED |
| Application secret source | | | | | BLOCKED |
| Database / region | | | | | BLOCKED |
| Cache / sessions | | | | | BLOCKED |
| Queue / workers | | | | | BLOCKED |
| Scheduler | | | | | BLOCKED |
| SSR process | | | | | BLOCKED |
| Object/file storage | | | | | BLOCKED |
| Mail provider | | | | | N/A/BLOCKED |
| GTM / GA4 | | | | | BLOCKED |
| Error tracking | | | | | BLOCKED |
| Logs / metrics | | | | | BLOCKED |
| Uptime / alerts | | | | | BLOCKED |
| Backups / retention | | | | | BLOCKED |
| Restore evidence | | | | | BLOCKED |
| Rollback artifact | | | | | BLOCKED |
| Release version | | | | | BLOCKED |
| Cache prefix | | | | | BLOCKED |
| Secure cookies | | | | | BLOCKED |
| Security headers | | | | | BLOCKED |
| Trusted geo header | | | | | N/A/BLOCKED |
| Default/locale URLs | | | | | BLOCKED |
| Favicon/public assets | | | | | BLOCKED |

## Process inventory

| Process | Command/artifact | Supervisor | Health | Restart on release | Alerts | Status |
|---|---|---|---|---|---|---|
| Web app | | | | | | BLOCKED |
| Queue worker | | | | | | N/A/BLOCKED |
| Scheduler | | | | | | BLOCKED |
| SSR | | | | | | N/A/BLOCKED |
| Content refresh | | | | | | BLOCKED |

## Release exclusions

Confirm absent:

- `.env`
- `public/hot`
- dev Composer providers/routes
- `z-docs` source files in public runtime
- local seed key files
- `__pycache__` / `*.pyc`
- debug dumps/logs/test screenshots
- unapproved source maps
