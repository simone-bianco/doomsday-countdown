---
name: toast-system
description: Use for implementing global success/error/warning/info toast notifications.
---

# Toast System

Toast notifications are handled globally via `ToastNotification` component in `AppLayout.vue`. No per-page setup needed.

## Backend (Preferred Method)
Use Laravel flash messages — `ToastNotification` watches `page.props.flash` automatically.

```php
// Success
return back()->with('success', 'Document saved successfully.');
return redirect()->route('projects.index')->with('success', 'Project created!');

// Error
return back()->with('error', 'An error occurred during saving.');

// Warning / Info
return back()->with('warning', 'Rate limit approaching.');
return back()->with('info', 'Processing started in background.');
```

## Frontend (When Needed)
For pure frontend actions (axios calls, WebSocket events):
```javascript
import { usePage } from '@inertiajs/vue3'
const page = usePage()
page.props.flash.success = 'Frontend action completed!'
```

Alternatively, use the `useToast` composable from `vue-ui-components` for manual toast triggers:
```javascript
import { useToast } from '@simone-bianco/vue-ui-components'
const toast = useToast()
toast.success('Operation completed!')
```

## Flash Types
- `success` — green, operation completed
- `error` — red, operation failed
- `warning` — orange, non-blocking alert
- `info` — blue, informational

## Rules
1. **Prefer backend approach** — `redirect()->with()` or `back()->with()`
2. **Use correct types** — `error` for failures only, `warning` for non-blocking
3. **No Vue imports needed** — `AppLayout` handles everything globally
4. **Never create custom toast implementations** — always use the project's `ToastNotification` system
5. **Avoid spam** — group batch operations: "10 files deleted" not 10 separate toasts
6. **Built-in dedupe** — 1000ms window for identical messages
