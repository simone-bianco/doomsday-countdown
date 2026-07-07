<script setup lang="ts">
import CountdownCard from './CountdownCard.vue';
import type { CountdownIndexData } from '@/types/generated';

withDefaults(defineProps<{
    readonly countdowns: readonly CountdownIndexData[];
    readonly compact?: boolean;
}>(), {
    compact: false,
});
</script>

<template>
    <section class="grid gap-3 sm:gap-4">
        <CountdownCard v-for="countdown in countdowns" :key="countdown.slug" :countdown="countdown" :compact="compact" />
    </section>
</template>
