# Shell, Navigation & Dashboard

        ## Load When
        building the admin shell, sidebar, route naming, metrics, and dashboard cards.

        ## Rules
- Prefer a single vertical sidebar; avoid duplicate top nav.
- Use route helpers/configurable admin paths, not hardcoded `/backoffice`.
- Counts/metrics must come from backend shared props/services, not frontend guesses.
- Dashboard cards show operational status, recent records, and quick actions.

## Stop
Stop if auth/role boundaries or admin path config are unclear.
