# Forms & State

        ## Load When
        connecting forms, Pinia, Inertia props, and realtime updates.

        - Mutations use SmartForm, not raw fetch.
- Props initialize Pinia stores.
- Watch prop changes and reset stores on unmount.
- Reverb events update stores.
- Guard stale responses when active entity changed before payload resolves.
