---
name: laravel-patches
description: Use when creating, reviewing, or running SimoneBianco Laravel data patches under database/patches, especially when replacing bulky Laravel seeders or splitting large seed payloads into small, ordered, maintainable patches.
---

# Laravel Patches

Use this skill for replayable/reversible data patches in `database/patches`, especially when seeders become bulky or ordered data operations need rollback.

## When to use patches

Use patches for substantial, ordered, replayable data steps. Keep seeders small; move large or evolving seed payloads into patches when they need explicit run/rollback behavior.

Executable formats:

- timestamped PHP patch file;
- module directory with `patch.php`.

Tracked patch state lives in `sb_patches`; run package migrations before tracked patch execution.

## Module file design

Prefer a module directory when payload is large:

```text
database/patches/2026_01_01_000001_example/
├── patch.php
├── data.php
└── optional-support.json
```

Rules:

- only `patch.php` executes;
- keep `patch.php` behavior-focused: lookup, validation, upsert/delete, rollback;
- put large data in `data.php`, JSON, markdown, or helper files beside it;
- do not bury huge arrays directly in executable logic.

## Rollback and identifiers

Rollback runs in reverse recorded order. Do not rename/remove already-applied patch identifiers without a plan for `sb_patches.patch`.

When converting a classic patch file to a module, preserve the identifier by moving the file to directory/`patch.php` rather than changing the logical patch path.

Rollback must delete/update only data owned by the patch. Never truncate unrelated domain data.

## Commands

```bash
php artisan make:patch path/name --module --data
php artisan patch:run
php artisan patch:rollback --step=1
php artisan patch:fresh
```

## Review checklist

- patch discovery path is valid;
- order is intentional;
- rollback is safe and scoped;
- transactions are used when needed;
- identifiers are stable after application;
- seed compatibility wrappers are intentional;
- no unrelated domain data is modified.
