---
name: vue-ui-components
description: Use for building, refactoring, selecting, or reviewing reusable components in packages/simone-bianco/vue-ui-components and its testing package.
---

<!-- argument-hint: [component name, catalog, ui prop, export, accessibility, testing] -->

# Vue UI Components
**Purpose**: select, create, export, and review reusable package components | **Chapters**: 5 | **Optimized**: 2026-07-09

## Core Mental Models

- Check the catalog before creating a component; reuse package components before app-local primitives.
- Package components must be typed, accessible, theme-aware, and stylable with `ui` props where relevant.
- Component exports live in `packages/simone-bianco/vue-ui-components/src/index.ts`.
- Demo/test pages live in `packages/simone-bianco/vue-ui-components-testing`.
- App-specific domain components stay in `resources/js/Components`, not the package.

## Chapter Index

| # | Title | Load When |
|---|-------|-----------|
| [ch01](chapters/ch01-catalog-core-forms.md) | Catalog: Core & Form Controls | choosing buttons, inputs, labels, toggles, chips, badges |
| [ch02](chapters/ch02-catalog-data-select-upload.md) | Catalog: Data, Select & Upload | choosing tables, pagination, select, file/image upload |
| [ch03](chapters/ch03-catalog-layout-feedback-media.md) | Catalog: Layout, Feedback, Media & Navigation | choosing panels, modals, status, nav, media, organisms |
| [ch04](chapters/ch04-component-creation-contract.md) | Component Creation Contract | creating/refactoring a package component |
| [ch05](chapters/ch05-testing-package.md) | Testing Package & Demo Pages | validating package components visually/functionally |

## Topic Index

- **Button/TextInput/Textarea/Checkbox/Toggle/RadioGroup** → ch01
- **Select/SelectMultiple/DataTable/ServerDataTable/Pagination** → ch02
- **FileUploader/ImageUploader/AvatarUploader/ImageGallery** → ch02
- **Modal/Tabs/Card/StatusBadge/ToastNotification/Loader** → ch03
- **SidebarNavigation/Topbar/NavLink/PageHeader** → ch03
- **new component/ui prop/export/index.ts** → ch04
- **demo/test page/vue-ui-components-testing** → ch05

## Supporting Files

- [cheatsheet.md](cheatsheet.md) — fast rules and catalog pointers.
- [patterns.md](patterns.md) — reusable package component patterns.
- [glossary.md](glossary.md) — package terms.

## Scope Limits

Do not create package components for one-off domain UI. Do not use native primitives in app UI when an existing package component covers the need. Verify current exports in `src/index.ts` before claiming a component is available.
