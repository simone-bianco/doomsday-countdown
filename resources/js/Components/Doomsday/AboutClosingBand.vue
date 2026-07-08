<script setup lang="ts">
import { Card, StatusDot } from '@simone-bianco/vue-ui-components';
import { motion } from 'motion-v';
import { computed } from 'vue';
import { fadeUp, resolveMotionPreset, useDoomsdayReducedMotion } from '@/animations/doomsdayMotion';

defineProps<{
    readonly label: string;
    readonly title: string;
    readonly body: string;
}>();

const reducedMotion = useDoomsdayReducedMotion();
const motionPreset = computed(() => resolveMotionPreset(fadeUp, reducedMotion.value));
</script>

<template>
    <motion.section :initial="motionPreset.initial" :animate="motionPreset.animate" :transition="motionPreset.transition">
        <Card :ui="{ root: 'doomsday-card doomsday-glow rounded-[2rem]', body: 'relative overflow-hidden p-7 sm:p-10 lg:p-12' }">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_85%_40%,rgba(255,42,35,0.20),transparent_34%)]" />
            <div class="relative grid gap-6 lg:grid-cols-[0.8fr_1.2fr] lg:items-end">
                <div>
                    <div class="flex items-center gap-3 text-ui-primary">
                        <StatusDot color="error" pulse size="lg" />
                        <span class="doomsday-display text-xs">{{ label }}</span>
                    </div>
                    <h2 class="doomsday-display mt-5 text-3xl leading-tight text-white sm:text-5xl">{{ title }}</h2>
                </div>
                <p class="max-w-4xl text-base leading-relaxed text-white/70 sm:text-xl">{{ body }}</p>
            </div>
        </Card>
    </motion.section>
</template>
