---
name: fe-to-be-flow
description: Use for frontend-backend flow DTOs, validation, requests, and form submission.
---

<!-- argument-hint: [topic, chapter, workflow, review, or command] -->

# Frontend-Backend Data Flow
**Purpose**: DTO-first request/validation/form pipeline from Laravel to Vue | **Chapters**: 4 | **Optimized**: 2026-07-09

## Core Mental Models

- Backend DTO is the contract source.
- Generated frontend rules/types are derived artifacts.
- Vue forms use SmartForm and generated rules.
- Controllers stay thin; services own behavior.
- No raw `axios`/`fetch` for standard mutations.

## Chapter Index

| # | Title | Load When |
|---|-------|-----------|
| [ch01](chapters/ch01-dto-contract.md) | DTO Contract | adding or changing request/response data shape |
| [ch02](chapters/ch02-generation.md) | Generation | after changing any Data class used by frontend/forms |
| [ch03](chapters/ch03-smartform-submit.md) | Frontend Form Submit | creating Vue form mutation UI |
| [ch04](chapters/ch04-controller-service.md) | Controller + Service | implementing backend endpoint for the form |

## Topic Index

- **dto** → ch01
- **validation** → ch01
- **typescript** → ch02
- **form-bridge** → ch02
- **smartform** → ch03
- **files** → ch03
- **controller** → ch04
- **service** → ch04

## Supporting Files

- [cheatsheet.md](cheatsheet.md) — fast rules and validation.
- [patterns.md](patterns.md) — reusable implementation patterns.
- [glossary.md](glossary.md) — terms only when needed.

## Scope Limits

Stay within the skill trigger. Verify current code before relying on paths, commands, package APIs, or generated files. Stop on conflicting user instructions, missing contracts, or unrelated working-tree changes.
