# Backend Contracts

        ## Load When
        changing controllers, routes, DTOs, shared props, or backend data composition.

        ## Rules
- Controllers stay thin; services compose dashboard/shared data.
- Shared props must be guarded, lazy when possible, and scoped to backoffice/auth context.
- Removing a route requires deleting frontend page/action references together.
- Preserve public routes/DTOs unless explicitly scoped.

## Evidence
Check route list, controller actions, Inertia page consumers, and service producers.
