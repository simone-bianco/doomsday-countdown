<script setup lang="ts">
import CountdownCard from './CountdownCard.vue';
import type { CountdownIndexData } from '@/types/generated';

withDefaults(defineProps<{
    readonly countdowns: readonly CountdownIndexData[];
    readonly compact?: boolean;
    readonly selectedSlug?: string | null;
    readonly pendingSlug?: string | null;
}>(), {
    compact: false,
    selectedSlug: null,
    pendingSlug: null,
});

const emit = defineEmits<{
    select: [countdown: CountdownIndexData];
}>();
</script>

<template>
    <section class="grid gap-3 sm:gap-4">
        <CountdownCard
            v-for="countdown in countdowns"
            :key="countdown.slug"
            :countdown="countdown"
            :compact="compact"
            :selected-slug="selectedSlug"
            :pending-slug="pendingSlug"
            @select="emit('select', $event)"
        />
    </section>
</template>
