---
name: backoffice-master
description: Use when designing or implementing an enterprise backoffice/admin panel with Vue/Inertia/Laravel or comparable full-stack architectures.
---

<!-- argument-hint: [topic, chapter, workflow, review, or command] -->

# Backoffice Master
**Purpose**: Operations-grade admin/backoffice architecture and review | **Chapters**: 4 | **Optimized**: 2026-07-09

## Core Mental Models

- A backoffice is an operations product: clarity, density, edit speed, and safe destructive flows beat marketing polish.
- Use one primary navigation surface, usually a vertical sidebar with real counts.
- Pages orchestrate; domain components and services do the work.
- Use project UI packages first; native primitives are a review smell.
- Edit surfaces often replace read-only show pages unless the show page has real operational value.

## Chapter Index

| # | Title | Load When |
|---|-------|-----------|
| [ch01](chapters/ch01-shell-dashboard.md) | Shell, Navigation & Dashboard | building the admin shell, sidebar, route naming, metrics, and dashboard cards |
| [ch02](chapters/ch02-crud-tables-forms.md) | CRUD, Tables & Forms | creating management tables, modals, edit pages, and relation managers |
| [ch03](chapters/ch03-backend-contracts.md) | Backend Contracts | changing controllers, routes, DTOs, shared props, or backend data composition |
| [ch04](chapters/ch04-review-qa.md) | Review & QA | final review of a backoffice implementation |

## Topic Index

- **sidebar** → ch01
- **dashboard** → ch01
- **crud** → ch02
- **table** → ch02
- **modal** → ch02
- **forms** → ch02
- **routes** → ch03
- **shared props** → ch03
- **review** → ch04

## Supporting Files

- [cheatsheet.md](cheatsheet.md) — fast rules and validation.
- [patterns.md](patterns.md) — reusable implementation patterns.
- [glossary.md](glossary.md) — terms only when needed.

## Scope Limits

Stay within the skill trigger. Verify current code before relying on paths, commands, package APIs, or generated files. Stop on conflicting user instructions, missing contracts, or unrelated working-tree changes.
