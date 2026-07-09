---
name: frontend
description: Use for Vue frontend implementation across pages, components, and UI flows.
---

<!-- argument-hint: [topic, chapter, workflow, review, or command] -->

# Frontend Development
**Purpose**: Vue/Inertia implementation rules across pages, components, and UI flows | **Chapters**: 4 | **Optimized**: 2026-07-09

## Core Mental Models

- Component hierarchy: package base → package advanced → app domain components.
- Pages orchestrate; components render behavior.
- Use SmartForm for mutations and generated rules.
- Use `cn()`, Tailwind CSS variables, `defineModel`, lucide component icons.
- Guard stale realtime/Inertia payloads.

## Chapter Index

| # | Title | Load When |
|---|-------|-----------|
| [ch01](chapters/ch01-component-hierarchy.md) | Component Hierarchy | deciding where a component belongs or whether to reuse a package component |
| [ch02](chapters/ch02-page-pattern.md) | Inertia Page Pattern | building or refactoring page files |
| [ch03](chapters/ch03-forms-and-state.md) | Forms & State | connecting forms, Pinia, Inertia props, and realtime updates |
| [ch04](chapters/ch04-styling-icons.md) | Styling & Icons | styling components and binding icons |

## Topic Index

- **component** → ch01
- **page** → ch02
- **inertia** → ch02
- **form** → ch03
- **pinia** → ch03
- **reverb** → ch03
- **cn** → ch04
- **icons** → ch04

## Supporting Files

- [cheatsheet.md](cheatsheet.md) — fast rules and validation.
- [patterns.md](patterns.md) — reusable implementation patterns.
- [glossary.md](glossary.md) — terms only when needed.

## Scope Limits

Stay within the skill trigger. Verify current code before relying on paths, commands, package APIs, or generated files. Stop on conflicting user instructions, missing contracts, or unrelated working-tree changes.
