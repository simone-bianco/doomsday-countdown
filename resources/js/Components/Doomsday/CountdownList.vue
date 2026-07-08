<script setup lang="ts">
import { computed } from 'vue';
import { motion } from 'motion-v';
import CountdownCard from './CountdownCard.vue';
import { cardReveal, cardStaggerDelay, resolveMotionPreset, useDoomsdayReducedMotion, withMotionDelay } from '@/animations/doomsdayMotion';
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

const reducedMotion = useDoomsdayReducedMotion();
const cardMotion = computed(() => resolveMotionPreset(cardReveal, reducedMotion.value));

function cardTransition(index: number) {
    return resolveMotionPreset(withMotionDelay(cardReveal, cardStaggerDelay(index)), reducedMotion.value).transition;
}
</script>

<template>
    <section class="grid content-start items-start gap-3 sm:gap-4">
        <motion.div
            v-for="(countdown, index) in countdowns"
            :key="countdown.slug"
            class="min-w-0"
            :initial="cardMotion.initial"
            :animate="cardMotion.animate"
            :exit="cardMotion.exit"
            :transition="cardTransition(index)"
        >
            <CountdownCard
                :countdown="countdown"
                :compact="compact"
                :selected-slug="selectedSlug"
                :pending-slug="pendingSlug"
                @select="emit('select', $event)"
            />
        </motion.div>
    </section>
</template>
