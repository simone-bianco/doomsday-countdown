---
name: theme-system
description: Use for theme config, CSS variables, and new theme creation.
---

# Theme System

Use this small skill when changing theme config, CSS variables, theme registration, or Tailwind token behavior.

## Core rules

- Themes apply through `[data-theme="name"]` on `<html>`.
- Color variables are raw RGB triplets: `15 23 42`, not `rgb(...)`.
- Raw RGB is required for Tailwind opacity modifiers such as `bg-ui-primary/50`.
- Every theme must define the full semantic token set.
- New semantic tokens require CSS variables and Tailwind config alignment.

## Key files

- `packages/simone-bianco/vue-ui-components/src/style.css`
- `packages/simone-bianco/vue-ui-components/src/components/ThemeProvider.vue`
- `packages/simone-bianco/vue-ui-components/src/composables/useTheme.ts`
- `packages/simone-bianco/vue-ui-components/tailwind.config.js`

## Required token families

`background/foreground`, `card/card-foreground`, `popover/popover-foreground`, `primary/primary-foreground`, `secondary/secondary-foreground`, `muted/muted-foreground`, `accent/accent-foreground`, `destructive/destructive-foreground`, `border`, `input`, `ring`.

## Fast check

Do not write:

```css
--ui-primary: rgb(15 23 42);
```

Write:

```css
--ui-primary: 15 23 42;
```
