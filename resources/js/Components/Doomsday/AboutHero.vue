<script setup lang="ts">
import { computed } from 'vue';
import { Badge, Card, StatusDot } from '@simone-bianco/vue-ui-components';
import { motion } from 'motion-v';
import ResponsiveImage from './ResponsiveImage.vue';
import { Globe2, Radar } from 'lucide-vue-next';
import { fadeIn, fadeUp, resolveMotionPreset, useDoomsdayReducedMotion, withMotionDelay } from '@/animations/doomsdayMotion';
import type { AboutPageData } from '@/types/generated';

const props = defineProps<{
    readonly page: AboutPageData;
}>();

const reducedMotion = useDoomsdayReducedMotion();
const lineMotion = computed(() => resolveMotionPreset(fadeIn, reducedMotion.value));
const titleMotion = computed(() => resolveMotionPreset(fadeUp, reducedMotion.value));
const bodyMotion = computed(() => resolveMotionPreset(withMotionDelay(fadeUp, 0.07), reducedMotion.value));
const visualMotion = computed(() => resolveMotionPreset(withMotionDelay(fadeUp, 0.12), reducedMotion.value));
const intro = computed(() => props.page.intro ?? []);
</script>

<template>
    <section class="grid items-stretch gap-6 lg:grid-cols-[minmax(0,1.05fr)_minmax(360px,0.95fr)]">
        <motion.div :initial="titleMotion.initial" :animate="titleMotion.animate" :transition="titleMotion.transition">
            <Card :ui="{ root: 'doomsday-card doomsday-glow h-full rounded-[2rem]', body: 'relative overflow-hidden p-6 sm:p-9 lg:p-12' }">
                <div class="absolute inset-0 bg-[radial-gradient(circle_at_18%_8%,rgba(255,42,35,0.22),transparent_34%)]" />
                <div class="absolute -right-24 top-12 h-72 w-72 rounded-full border border-ui-primary/20 blur-[1px]" />
                <div class="relative">
                    <motion.div class="mb-8 h-px w-28 bg-ui-primary/90" :initial="lineMotion.initial" :animate="lineMotion.animate" :transition="lineMotion.transition" />
                    <Badge :label="page.hero_badge" :icon="Radar" dot pulse variant="soft" color="primary" size="md" :ui="{ root: 'bg-black/55 tracking-[0.18em]' }" />
                    <p class="doomsday-display mt-7 max-w-3xl text-xs text-ui-primary/90 sm:text-sm">{{ page.eyebrow }}</p>
                    <h1 class="doomsday-display mt-4 max-w-5xl text-[clamp(2.65rem,7vw,7.25rem)] leading-[0.92] text-white drop-shadow-[0_0_24px_rgba(255,42,35,0.18)]">{{ page.title }}</h1>
                    <p class="mt-7 max-w-4xl text-[clamp(1.05rem,2vw,1.5rem)] leading-relaxed text-white/78">{{ page.subtitle }}</p>
                </div>
            </Card>
        </motion.div>

        <motion.div :initial="visualMotion.initial" :animate="visualMotion.animate" :transition="visualMotion.transition">
            <Card :ui="{ root: 'doomsday-card h-full rounded-[2rem]', body: 'relative h-full min-h-[520px] overflow-hidden p-0' }">
                <ResponsiveImage
                    src="/images/doomsday/uninhabitable_earth_separate.png"
                    alt="Doomsday scenario visual"
                    sizes="(min-width: 1024px) 46vw, 100vw"
                    loading="eager"
                    fetch-priority="high"
                    picture-class="absolute inset-0 block h-full w-full"
                    img-class="h-full w-full object-cover object-center opacity-65"
                />
                <div class="absolute inset-0 bg-gradient-to-b from-black/35 via-black/50 to-black/95" />
                <div class="absolute inset-6 rounded-full border border-ui-primary/20 doomsday-about-orbit" />
                <div class="absolute inset-14 rounded-full border border-white/10 doomsday-about-orbit doomsday-about-orbit-slow" />
                <div class="absolute left-1/2 top-1/2 h-28 w-28 -translate-x-1/2 -translate-y-1/2 rounded-full border border-ui-primary/80 bg-ui-primary/10 shadow-[0_0_80px_rgba(255,42,35,0.28)]" />
                <div class="absolute inset-x-8 top-1/2 h-px bg-ui-primary/50 doomsday-about-scan" />
                <div class="absolute bottom-0 left-0 right-0 p-6 sm:p-8">
                    <div class="flex items-center gap-3 text-xs uppercase tracking-[0.22em] text-white/65">
                        <StatusDot color="error" pulse size="lg" />
                        <span class="doomsday-display">{{ page.filter_watch_label }}</span>
                    </div>
                    <div class="mt-6 grid gap-4">
                        <motion.p v-for="paragraph in intro" :key="paragraph" class="text-base leading-relaxed text-white/78 sm:text-lg" :initial="bodyMotion.initial" :animate="bodyMotion.animate" :transition="bodyMotion.transition">
                            {{ paragraph }}
                        </motion.p>
                    </div>
                    <div class="mt-7 flex items-center gap-3 text-ui-primary">
                        <Globe2 class="h-5 w-5" aria-hidden="true" />
                        <span class="doomsday-display text-xs">{{ page.visual_label }}</span>
                    </div>
                </div>
            </Card>
        </motion.div>
    </section>
</template>
