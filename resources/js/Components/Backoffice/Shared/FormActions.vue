<script setup lang="ts">
withDefaults(defineProps<{
    readonly compact?: boolean;
}>(), {
    compact: false,
});
</script>

<template>
    <div :class="['flex flex-col gap-3 border-t border-ui-border/60 sm:flex-row sm:items-center sm:justify-between', compact ? 'pt-3' : 'pt-4']">
        <div class="flex flex-wrap items-center gap-2">
            <slot />
        </div>
        <div v-if="$slots.aside" class="flex min-h-10 items-center">
            <slot name="aside" />
        </div>
    </div>
</template>
