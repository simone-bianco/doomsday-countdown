---
name: laragent-rotable-agents
description: "Project knowledge base for LarAgent agents with SimoneBianco Laravel Key Rotator. Use when building or reviewing rotable agents, attribute tools, forced tool calls, structured JSON output, deterministic service boundaries, and agent test harnesses."
---

<!-- argument-hint: [topic, chapter, workflow, review, or command] -->

# LarAgent Rotable Agents
**Purpose**: Generic LarAgent + key-rotator agent architecture for this project | **Chapters**: 6 | **Optimized**: 2026-07-09

## Core Mental Models

- Rotable agents inject a managed key before LarAgent provider setup.
- Tools are narrow Laravel service boundaries, not business-logic dumps.
- Forced tool call uses exact PHP method name.
- Structured output constrains final LLM response, not the PHP tool result.
- Every operational agent needs tool-only validation without API keys.

## Chapter Index

| # | Title | Load When |
|---|-------|-----------|
| [ch01](chapters/ch01-runtime-provider.md) | Runtime Provider | configuring LarAgent providers, drivers, base URLs, and namespaces |
| [ch02](chapters/ch02-key-rotation.md) | Key Rotation | creating/reviewing rotable base agents |
| [ch03](chapters/ch03-tools-forced-calls.md) | Tools & Forced Calls | adding `#[Tool]`, `toolRequired`, or `forceTool` |
| [ch04](chapters/ch04-structured-output.md) | Structured Output | designing final JSON response contracts |
| [ch05](chapters/ch05-test-harness.md) | Test Harness | validating agents without live LLM calls |
| [ch06](chapters/ch06-review.md) | Review | reviewing an agent implementation |

## Topic Index

- **provider** → ch01
- **OpenAiCompatible** → ch01
- **key rotator** → ch02
- **usage** → ch02
- **tool** → ch03
- **forceTool** → ch03
- **structured output** → ch04
- **tool-only** → ch05
- **review** → ch06

## Supporting Files

- [cheatsheet.md](cheatsheet.md) — fast rules and validation.
- [patterns.md](patterns.md) — reusable implementation patterns.
- [glossary.md](glossary.md) — terms only when needed.

## Scope Limits

Stay within the skill trigger. Verify current code before relying on paths, commands, package APIs, or generated files. Stop on conflicting user instructions, missing contracts, or unrelated working-tree changes.
