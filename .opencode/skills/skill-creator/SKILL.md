---
name: skill-creator
description: "Use when creating, refactoring, reviewing, or converting .opencode skills. Produces book-to-skill style multi-file skills with SKILL.md, chapters, glossary, patterns, cheatsheet, templates, validation checks, and anti-slop rules."
---

<!-- argument-hint: [topic, chapter, workflow, review, or command] -->

# Skill Creator
**Purpose**: Metaskill for creating token-efficient book-to-skill skills | **Chapters**: 5 | **Optimized**: 2026-07-09

## Core Mental Models

- Skills must be operational, not documentation dumps.
- Root file is an index and routing layer, not a book chapter.
- Agents must cherry-pick chapters; bulk-read is forbidden unless reviewing the skill.
- Every instruction comes from verified evidence or is marked `[DA VERIFICARE]`.
- Prefer fewer, sharper chapters over many copied sections.

## Chapter Index

| # | Title | Load When |
|---|-------|-----------|
| [ch01](chapters/ch01-scope-evidence.md) | Scope & Evidence | creating a new skill or deciding what belongs in it |
| [ch02](chapters/ch02-token-efficient-structure.md) | Token-Efficient Structure | designing the book-to-skill file layout |
| [ch03](chapters/ch03-content-extraction.md) | Content Extraction | turning docs/code/source material into useful skill content |
| [ch04](chapters/ch04-quality-review.md) | Quality Review | reviewing a skill for slop or bad discovery behavior |
| [ch05](chapters/ch05-validation.md) | Validation | finishing a skill creation/conversion task |

## Topic Index

- **scope** → ch01
- **evidence** → ch01
- **structure** → ch02
- **token** → ch02
- **extraction** → ch03
- **slop** → ch04
- **validation** → ch05
- **template** → ch04

## Supporting Files

- [cheatsheet.md](cheatsheet.md) — fast rules and validation.
- [patterns.md](patterns.md) — reusable implementation patterns.
- [glossary.md](glossary.md) — terms only when needed.

## Bookization threshold

- `>= 4000` total characters: use book-to-skill by default.
- `< 4000` total characters: keep single-file by default.
- Exception: bookize below 4000 only for catalog/reference skills where cherry-pick chapters materially help.
- Exception: keep single-file above 4000 only for a linear procedure with no independently useful sections.

## Scope Limits

Stay within the skill trigger. Verify current code before relying on paths, commands, package APIs, or generated files. Stop on conflicting user instructions, missing contracts, or unrelated working-tree changes.
