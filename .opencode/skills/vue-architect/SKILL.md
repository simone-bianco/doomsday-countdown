---
name: vue-architect
description: Use for scalable Inertia pages, layouts, and domain Vue architecture.
mcp:
  vue_mcp:
    type: remote
    url: http://127.0.0.1:5173/__mcp/sse
---

# Vue UI Architect

Use this skill for scalable Inertia/Vue page architecture, component placement, store lifecycle, form boundaries, and styling contracts.

## Core rules

- Pages orchestrate; components implement UI.
- Component category determines location: package base, package advanced, or app domain.
- Use SmartForm and generated rules for mutations.
- Use typed `ui` prop pattern and `cn()` for stylability.
- Store lifecycle must handle prop updates and unmount cleanup.

## Component placement

1. Base atoms/molecules belong in `vue-ui-components`.
2. Advanced reusable organisms belong in the advanced package.
3. App-specific logic belongs in `resources/js/Components/{Domain}`.

Do not duplicate package-worthy components in app code. Do not put domain-only UI into the package.

## Page and store architecture

Pages should import layout/head/store/domain components and stay as orchestrators. Move tables, forms, panels, and heavy sections into components.

Store lifecycle:

- initialize from Inertia props;
- watch prop changes;
- reset on unmount;
- guard stale async/realtime payloads when active entity changes.

## Forms and mutations

Use SmartForm/StoreForm with generated rules. No raw `axios/fetch` for backend mutations. Keep DTO, generated TypeScript, generated form rules, and frontend form shape aligned.

## Styling contracts

- use `<script setup lang="ts">`;
- type props and emits;
- use `defineModel` for v-model;
- use `cn()` for class merging;
- use Tailwind variables;
- use lucide component icons, not lowercase string names;
- expose typed `ui?: Partial<ComponentUI>` for deep class injection when useful.
