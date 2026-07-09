---
name: toast-system
description: Use for implementing global success/error/warning/info toast notifications.
---

# Toast System

Use this small skill when adding or reviewing toast notifications.

## Core rules

- Prefer Laravel flash messages; the layout-level `ToastNotification` watches `page.props.flash` globally.
- Manual frontend toast is only for frontend-only actions or realtime/local events.
- Never create a custom toast system or local duplicate toast component.
- Batch repeated messages to avoid spam.

## Backend preferred

```php
return back()->with('success', 'Saved.');
return redirect()->route('x.index')->with('error', 'Failed.');
```

Supported types:

```text
success
error
warning
info
```

Use `error` for blocking failures, `warning` for non-blocking issues, `info` for neutral status.

## Frontend only

Use frontend/manual toast only when no backend flash exists, for example a local copy action or realtime event. Prefer the package `useToast` helper if available; do not invent a new global store.
