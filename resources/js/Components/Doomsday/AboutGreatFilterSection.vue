<script setup lang="ts">
import { computed } from 'vue';
import { Badge, Card, StatusDot } from '@simone-bianco/vue-ui-components';
import { motion } from 'motion-v';
import ResponsiveImage from './ResponsiveImage.vue';
import { Orbit, RadioTower } from 'lucide-vue-next';
import { fadeUp, resolveMotionPreset, useDoomsdayReducedMotion, withMotionDelay } from '@/animations/doomsdayMotion';

type TimelineItem = { readonly label: string; readonly title: string; readonly body: string };

const props = defineProps<{
    readonly eyebrow: string;
    readonly pipelineLabel: string;
    readonly timeline: readonly TimelineItem[];
}>();

const reducedMotion = useDoomsdayReducedMotion();
const panelMotion = computed(() => resolveMotionPreset(fadeUp, reducedMotion.value));
const timelineMotion = computed(() => resolveMotionPreset(withMotionDelay(fadeUp, 0.08), reducedMotion.value));
</script>

<template>
    <motion.section class="grid gap-6 lg:grid-cols-[0.95fr_1.05fr]" :initial="panelMotion.initial" :animate="panelMotion.animate" :transition="panelMotion.transition">
        <Card :ui="{ root: 'doomsday-card rounded-[2rem]', body: 'relative min-h-[430px] overflow-hidden p-0' }">
            <ResponsiveImage
                src="/images/doomsday/society_collapse_separate.png"
                alt="Civilization risk visual"
                sizes="(min-width: 1024px) 46vw, 100vw"
                loading="lazy"
                fetch-priority="low"
                picture-class="absolute inset-0 block h-full w-full"
                img-class="h-full w-full object-cover object-center opacity-70"
            />
            <div class="absolute inset-0 bg-gradient-to-br from-black/20 via-black/72 to-black" />
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_22%_22%,rgba(255,42,35,0.25),transparent_32%)]" />
            <div class="absolute bottom-0 left-0 right-0 p-6 sm:p-8">
                <Badge label="Great Filter" :icon="Orbit" variant="gradient" size="md" />
                <h2 class="doomsday-display mt-6 max-w-2xl text-3xl leading-tight text-white sm:text-5xl">{{ eyebrow }}</h2>
                <p class="mt-5 max-w-xl text-sm leading-relaxed text-white/62 sm:text-base">{{ timeline[0]?.body }}</p>
            </div>
        </Card>

        <Card :ui="{ root: 'doomsday-card rounded-[2rem]', body: 'relative overflow-hidden p-6 sm:p-8' }">
            <div class="absolute right-8 top-8 hidden h-32 w-32 rounded-full border border-ui-primary/20 lg:block doomsday-about-orbit-slow" />
            <div class="relative flex items-center gap-3 text-ui-primary">
                <RadioTower class="h-6 w-6" aria-hidden="true" />
                <span class="doomsday-display text-xs">{{ pipelineLabel }}</span>
            </div>

            <div class="relative mt-8 grid gap-5">
                <div class="absolute bottom-8 left-[1.18rem] top-8 w-px bg-gradient-to-b from-ui-primary via-ui-primary/40 to-transparent" />
                <motion.div v-for="item in timeline" :key="item.label" class="relative grid grid-cols-[2.4rem_minmax(0,1fr)] gap-4" :initial="timelineMotion.initial" :animate="timelineMotion.animate" :transition="timelineMotion.transition">
                    <div class="relative z-10 flex h-10 w-10 items-center justify-center rounded-full border border-ui-primary/60 bg-black text-ui-primary shadow-[0_0_24px_rgba(255,42,35,0.18)]">
                        <span class="doomsday-display text-xs">{{ item.label }}</span>
                    </div>
                    <div class="rounded-2xl border border-white/10 bg-black/42 p-5">
                        <div class="flex items-start justify-between gap-4">
                            <h3 class="doomsday-display text-lg leading-tight text-white">{{ item.title }}</h3>
                            <StatusDot color="error" pulse size="sm" />
                        </div>
                        <p class="mt-3 text-sm leading-relaxed text-white/60">{{ item.body }}</p>
                    </div>
                </motion.div>
            </div>
        </Card>
    </motion.section>
</template>
