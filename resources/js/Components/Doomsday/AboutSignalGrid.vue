<script setup lang="ts">
import { computed } from 'vue';
import { Card, StatCard } from '@simone-bianco/vue-ui-components';
import { motion } from 'motion-v';
import { Activity, Eye, Radar, ShieldAlert } from 'lucide-vue-next';
import { cardReveal, cardStaggerDelay, resolveMotionPreset, useDoomsdayReducedMotion, withMotionDelay } from '@/animations/doomsdayMotion';

type StatItem = { readonly label: string; readonly value: string; readonly description: string };
type SectionItem = { readonly title: string; readonly body: string };

const props = defineProps<{
    readonly stats: readonly StatItem[];
    readonly sections: readonly SectionItem[];
}>();

const statIcons = [Radar, ShieldAlert, Activity];
const sectionIcons = [Eye, Activity, ShieldAlert];
const reducedMotion = useDoomsdayReducedMotion();
const stats = computed(() => props.stats ?? []);
const sections = computed(() => props.sections ?? []);

function motionFor(index: number) {
    return resolveMotionPreset(withMotionDelay(cardReveal, cardStaggerDelay(index)), reducedMotion.value);
}

</script>

<template>
    <section class="grid gap-5">
        <div class="grid gap-4 md:grid-cols-3">
            <motion.div v-for="(stat, index) in stats" :key="stat.label" :initial="motionFor(index).initial" :animate="motionFor(index).animate" :transition="motionFor(index).transition">
                <StatCard :label="stat.label" :value="stat.value" :description="stat.description" :icon="statIcons[index % statIcons.length]" color="rose" :ui="{ root: 'doomsday-card rounded-2xl border-white/10 bg-black/55 p-5 hover:border-ui-primary/50', label: 'doomsday-display text-ui-primary/80', value: 'doomsday-display text-3xl text-white sm:text-4xl', description: 'text-sm leading-relaxed text-white/58', icon: 'bg-ui-primary/12 text-ui-primary' }" />
            </motion.div>
        </div>

        <div class="grid gap-5 lg:grid-cols-3">
            <motion.div v-for="(section, index) in sections" :key="section.title" :initial="motionFor(index + stats.length).initial" :animate="motionFor(index + stats.length).animate" :transition="motionFor(index + stats.length).transition">
                <Card :ui="{ root: 'doomsday-card group h-full rounded-2xl transition duration-300 hover:-translate-y-1 hover:border-ui-primary/55 hover:shadow-[0_0_34px_rgba(255,42,35,0.12)]', body: 'relative h-full overflow-hidden p-6 sm:p-7' }">
                    <div class="absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-ui-primary/60 to-transparent opacity-0 transition group-hover:opacity-100" />
                    <component :is="sectionIcons[index % sectionIcons.length]" class="h-6 w-6 text-ui-primary" aria-hidden="true" />
                    <h2 class="doomsday-display mt-5 text-xl leading-tight text-white">{{ section.title }}</h2>
                    <p class="mt-5 text-sm leading-relaxed text-white/62 sm:text-base">{{ section.body }}</p>
                </Card>
            </motion.div>
        </div>
    </section>
</template>
