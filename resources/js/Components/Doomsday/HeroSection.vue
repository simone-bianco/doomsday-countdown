<script setup lang="ts">
import { computed } from 'vue';
import { motion } from 'motion-v';
import { fadeIn, fadeUp, resolveMotionPreset, useDoomsdayReducedMotion, withMotionDelay } from '@/animations/doomsdayMotion';

defineProps<{
    readonly hero: Record<string, string>;
}>();

const reducedMotion = useDoomsdayReducedMotion();
const lineMotion = computed(() => resolveMotionPreset(fadeIn, reducedMotion.value));
const headlineMotion = computed(() => resolveMotionPreset(fadeUp, reducedMotion.value));
const subtitleMotion = computed(() => resolveMotionPreset(withMotionDelay(fadeUp, 0.06), reducedMotion.value));
</script>

<template>
    <section class="relative min-h-[430px] overflow-hidden bg-transparent lg:min-h-[460px]">
        <div class="absolute inset-0 bg-gradient-to-r to-transparent" />
        <div class="absolute inset-0 bg-gradient-to-b from-transparent via-transparent" />
        <div class="relative mx-auto flex min-h-[430px] max-w-[1760px] items-center px-4 py-12 sm:px-7 lg:min-h-[460px] lg:py-16">
            <div class="max-w-3xl">
                <motion.div class="mb-7 h-px w-24 bg-ui-primary/80" :initial="lineMotion.initial" :animate="lineMotion.animate" :transition="lineMotion.transition" />
                <motion.h1 class="doomsday-display text-3xl leading-tight text-white sm:text-5xl lg:text-6xl" :initial="headlineMotion.initial" :animate="headlineMotion.animate" :transition="headlineMotion.transition">
                    {{ hero.headline_prefix }}<br />
                    <span class="text-white/80">{{ hero.headline_middle }}</span>
                    <span class="doomsday-red-text"> {{ hero.headline_accent }}</span>
                </motion.h1>
                <motion.p class="mt-6 max-w-2xl text-base text-ui-muted-foreground sm:text-xl" :initial="subtitleMotion.initial" :animate="subtitleMotion.animate" :transition="subtitleMotion.transition">{{ hero.subtitle }}</motion.p>
            </div>
        </div>
    </section>
</template>
