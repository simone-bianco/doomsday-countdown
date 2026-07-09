# Component Hierarchy

        ## Load When
        deciding where a component belongs or whether to reuse a package component.

        1. Base package: `packages/simone-bianco/vue-ui-components`.
2. Advanced package: `packages/simone-bianco/vue-ui-components-advanced`.
3. Domain app components: `resources/js/Components/{Domain}/`.

If a needed package component does not exist, use `vue-ui-components` skill before creating app-local duplicates.
