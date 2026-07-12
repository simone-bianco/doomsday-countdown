# Chapter 5: Security & Access Control

## Load when

Reviewing production routes, authentication, backoffice authorization, rate limiting, uploads, AI endpoints, security headers, sessions, secrets, dependency exposure or production route allowlists.

## Core idea

Production security starts with reducing exposed behavior and enforcing least privilege. A route behind `web` middleware is public. A route behind `auth` is not automatically authorized. `robots.txt`, hidden navigation and obscurity are not security controls.

## Route exposure audit

Inspect the production route table built with production dependencies and environment. Classify routes as public read-only, public authenticated, administrator-only, signed/internal, local/testing only, or forbidden in production.

Current known high-risk surfaces must be re-verified:

- `/agent/demo` or other cost-bearing AI routes;
- testing-package component/demo APIs;
- upload endpoints;
- login and session endpoints;
- OpenAI key management;
- user/content administration;
- signed local-storage serving routes.

Production `composer install --no-dev` must remove dev-package providers/routes; verify rather than assume.

## Authentication and authorization

Required:

- explicit login rate limit;
- secure credential/session handling;
- role or permission model;
- controller/service policies for every backoffice domain;
- least privilege for keys, users and publication actions;
- protection against deleting/locking the final administrator;
- session invalidation after password/role/security changes;
- MFA or an approved strong-admin-authentication policy;
- audit events for security-sensitive actions.

Do not let “any authenticated user” manage users, secrets or provider keys.

## AI and cost-bearing endpoints

For any AI/provider route:

- remove it from production if not a product feature;
- otherwise require authentication and authorization;
- rate-limit by identity/IP as appropriate;
- set token/request/concurrency/cost budgets;
- validate input length/type;
- do not echo raw prompts, provider errors or stack details;
- log safe metadata, not secrets/sensitive content;
- monitor spend and abuse.

## Uploads and files

Required controls:

- authentication/authorization;
- size and MIME/extension validation;
- server-generated filenames;
- storage outside executable/public path where possible;
- malware/content scanning if risk warrants;
- image re-encoding where appropriate;
- signed access for private files;
- no raw exception messages;
- no request/file dumps containing secrets;
- retention/deletion rules.

Disable unused storage serving/upload features.

## Security headers

Design headers around actual dependencies; do not paste a wildcard CSP.

Review:

- `Content-Security-Policy` including `frame-ancestors`;
- `Strict-Transport-Security` after HTTPS is proven;
- `X-Content-Type-Options: nosniff`;
- `Referrer-Policy`;
- `Permissions-Policy`;
- clickjacking protection;
- cross-origin policies where appropriate;
- removal of unnecessary version headers such as `X-Powered-By`.

CSP inventory must account for Vite assets, GTM/GA4, YouTube embeds, remote images, inline/nonced scripts/styles, APIs and websockets. Prefer report-only evidence before enforcement on an existing app.

## HTTPS, proxies and sessions

Production must define:

- HTTPS-only access;
- trusted proxy configuration;
- origin protection behind CDN;
- correct URL/scheme generation;
- Secure and HttpOnly session cookies;
- SameSite for actual integrations;
- cookie domain/path;
- session lifetime and remember-me policy;
- CSRF behavior;
- HSTS rollout and subdomain implications.

Do not enable HSTS preload without owning all subdomains and understanding recovery cost.

## Secrets

Required:

- secret manager or controlled environment injection;
- no secrets in Git, build output, logs or client bundles;
- APP_KEY protection and recovery plan;
- provider-key encryption at rest;
- rotation/revocation procedure;
- environment separation;
- accidental-secret scanning;
- ignored local seed files absent from release artifacts.

## Dependencies and runtime

Verify:

- `composer audit --locked`;
- npm production and full audits;
- supported PHP/Node/package versions;
- production installation excludes dev dependencies;
- no abandoned critical package without mitigation;
- server/runtime security patch process;
- avoidable version disclosure minimized.

## Error handling

Production behavior:

- `APP_DEBUG=false`;
- generic public errors;
- detailed exceptions only in centralized tracking;
- stable 404/403/429/500 behavior;
- no stack traces/provider responses in JSON;
- correlation/release identifiers where safe.

## Tests

Automate production route deny/allow list, auth/role cases, login/AI throttling, upload validation/access, raw-error absence, security headers, cookie attributes, dev-route absence under `--no-dev`, and secret absence from built assets.

## Stop conditions

- public AI/upload/demo route without approved protection;
- backoffice only uses `auth` for privileged operations;
- login has no throttle;
- debug/dev route visible in production;
- no secret strategy;
- security headers omitted without a documented proxy-layer equivalent;
- origin accepts spoofable geo/proxy headers;
- raw exception or secret data reaches client/log.

## Official basis

See `references.md`: OWASP HTTP headers, CSP, HSTS, TLS, session and clickjacking guidance.
