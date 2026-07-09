---
name: frontend-design
description: Use for Laravel frontend design
mcp:
  laravel_boost:
    command: php
    args: ["artisan", "boost:mcp"]
---

# Frontend Design

Use this skill before creating or redesigning public/backoffice UI. It is about visual direction and avoiding generic AI defaults; use implementation skills for code mechanics.

## Core mental models

- Start with an aesthetic direction, not components.
- Reject statistical-center defaults unless requested.
- Typography, color, layout, and motion must have intent.
- Project implementation still uses Tailwind variables, `cn()`, lucide icons, and component hierarchy.
- Accessibility and contrast beat visual novelty.

## Direction and anti-defaults

Define three words before code, for example:

```text
editorial brutalist technical
playful organic dense
premium dark severe
```

Avoid by default:

- Inter as automatic primary font;
- purple/blue gradients on white;
- centered hero + three cards;
- purposeless glassmorphism;
- decorative parallax/floating/bouncing;
- vague “modern clean”.

## Typography and color

- Max two font families: display + body.
- Body readable at 14–16px.
- Headline line-height 1.1–1.2; body 1.5–1.6.
- Max three primary colors plus one accent.
- One color dominates 70%+ of UI.
- Text contrast must pass WCAG AA.

## Layout and motion

- Prefer intentional asymmetry over centered everything.
- Use whitespace and full-bleed rhythm.
- Design mobile-first, then expand.
- Every animation must communicate hierarchy, state, affordance, loading, or transition.
- Avoid infinite animation loops without user trigger.

## Project integration

Use Tailwind theme variables and component hierarchy. Redefine actual values through theme/CSS variables rather than scattering arbitrary colors. Custom fonts require approval and should go through the project font/theme pipeline.
