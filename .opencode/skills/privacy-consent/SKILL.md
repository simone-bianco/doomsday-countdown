---
name: privacy-consent
description: Use when implementing, reviewing, or extending GDPR/ePrivacy cookie consent, Google Consent Mode v2, analytics, advertising tags, tracking pixels, privacy/cookie policy surfaces, or consent-dependent third-party embeds in this Laravel/Inertia/Vue project.
---

<!-- argument-hint: [topic, chapter, workflow, review, or command] -->

# Privacy Consent & Tracking Compliance
**Purpose**: Consent-gated tracking and policy surfaces; technical guidance, not legal advice | **Chapters**: 5 | **Optimized**: 2026-07-09

## Core Mental Models

- Never load non-essential tracking before consent.
- Use categories: necessary, analytics, marketing, functional.
- Basic Consent Mode first: optional storage denied until choice.
- All trackers load through the consent runtime or consent-aware GTM rules.
- Revocation must update consent state and prevent future optional loads.

## Chapter Index

| # | Title | Load When |
|---|-------|-----------|
| [ch01](chapters/ch01-architecture.md) | Project Architecture | finding consent runtime files and understanding boundaries |
| [ch02](chapters/ch02-google-consent.md) | Categories & Google Consent Mode | mapping local consent to Google signals |
| [ch03](chapters/ch03-tracker-loading.md) | Tracker Loading | adding GTM, GA, pixels, heatmaps, embeds, or vendor scripts |
| [ch04](chapters/ch04-ux-revocation.md) | UX & Revocation | changing banner, settings, storage, or consent updates |
| [ch05](chapters/ch05-qa-policy.md) | QA & Policy | final checks and legal caveats |

## Topic Index

- **banner** → ch04
- **consent mode** → ch02
- **GTM** → ch03
- **GA4** → ch03
- **pixels** → ch03
- **revocation** → ch04
- **privacy policy** → ch05
- **QA** → ch05

## Supporting Files

- [cheatsheet.md](cheatsheet.md) — fast rules and validation.
- [patterns.md](patterns.md) — reusable implementation patterns.
- [glossary.md](glossary.md) — terms only when needed.

## Scope Limits

Stay within the skill trigger. Verify current code before relying on paths, commands, package APIs, or generated files. Stop on conflicting user instructions, missing contracts, or unrelated working-tree changes.
