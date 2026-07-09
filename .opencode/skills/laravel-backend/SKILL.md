---
name: laravel-backend
description: Use for Laravel backend services, controllers, DTOs, and domain logic.
mcp:
  laravel_boost:
    command: php
    args: ["artisan", "boost:mcp"]
---

<!-- argument-hint: [topic, chapter, workflow, review, or command] -->

# Laravel Backend Architect
**Purpose**: DTO-first service-oriented Laravel backend implementation and review | **Chapters**: 5 | **Optimized**: 2026-07-09

## Core Mental Models

- DTO first; generated TypeScript/form rules follow DTOs.
- Controllers are thin; services own domain logic.
- Mutations must use the same domain scope visible in reads/lists.
- Async work returns `202` and jobs broadcast completion/failure.
- Package-owned domains must use package services, not raw framework shortcuts.

## Chapter Index

| # | Title | Load When |
|---|-------|-----------|
| [ch01](chapters/ch01-dto-controller-service.md) | DTO → Controller → Service | creating or reviewing backend mutation/query flows |
| [ch02](chapters/ch02-domain-scope-security.md) | Domain Scope & Security | writing queries/mutations with ownership or project/domain boundaries |
| [ch03](chapters/ch03-events-jobs.md) | Events & Jobs | adding async work, broadcasts, or queues |
| [ch04](chapters/ch04-files-package-boundaries.md) | Files & Package Boundaries | working with package-owned file/RAG domains or large services |
| [ch05](chapters/ch05-validation-review.md) | Validation & Review | final backend review before handoff |

## Topic Index

- **dto** → ch01
- **controller** → ch01
- **service** → ch01
- **scope** → ch02
- **auth** → ch02
- **jobs** → ch03
- **events** → ch03
- **files** → ch04
- **review** → ch05

## Supporting Files

- [cheatsheet.md](cheatsheet.md) — fast rules and validation.
- [patterns.md](patterns.md) — reusable implementation patterns.
- [glossary.md](glossary.md) — terms only when needed.

## Scope Limits

Stay within the skill trigger. Verify current code before relying on paths, commands, package APIs, or generated files. Stop on conflicting user instructions, missing contracts, or unrelated working-tree changes.
