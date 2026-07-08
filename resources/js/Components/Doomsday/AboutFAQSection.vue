<script setup lang="ts">
import { computed } from 'vue';
import { Badge, Card, CollapsibleSection } from '@simone-bianco/vue-ui-components';
import { motion } from 'motion-v';
import { HelpCircle, MessageCircleWarning } from 'lucide-vue-next';
import { cardReveal, cardStaggerDelay, resolveMotionPreset, useDoomsdayReducedMotion, withMotionDelay } from '@/animations/doomsdayMotion';

type FAQItem = { readonly question: string; readonly answer: string };

const props = defineProps<{
    readonly title: string;
    readonly subtitle: string;
    readonly items: readonly FAQItem[];
}>();

const reducedMotion = useDoomsdayReducedMotion();
const items = computed(() => props.items ?? []);

function motionFor(index: number) {
    return resolveMotionPreset(withMotionDelay(cardReveal, cardStaggerDelay(index)), reducedMotion.value);
}
</script>

<template>
    <section class="grid gap-5 lg:grid-cols-[0.36fr_0.64fr]">
        <Card :ui="{ root: 'doomsday-card rounded-[2rem]', body: 'relative overflow-hidden p-6 sm:p-8' }">
            <div class="absolute -right-16 -top-16 h-44 w-44 rounded-full bg-ui-primary/10 blur-3xl" />
            <Badge label="FAQ" :icon="HelpCircle" variant="soft" color="primary" size="md" />
            <h2 class="doomsday-display mt-7 text-4xl leading-none text-white sm:text-5xl">{{ title }}</h2>
            <p class="mt-5 text-sm leading-relaxed text-white/58 sm:text-base">{{ subtitle }}</p>
        </Card>

        <div class="grid gap-3">
            <motion.div v-for="(item, index) in items" :key="item.question" :initial="motionFor(index).initial" :animate="motionFor(index).animate" :transition="motionFor(index).transition">
                <CollapsibleSection :title="item.question" :default-open="index === 0" :icon="MessageCircleWarning" :ui="{ root: 'doomsday-card rounded-2xl border-white/10 bg-black/60', header: 'p-5 hover:bg-ui-primary/10', headerTitle: 'doomsday-display text-left text-sm leading-relaxed text-white sm:text-base', headerIcon: 'text-ui-primary', chevron: 'text-ui-primary', content: 'px-5 pb-5' }">
                    <p class="border-t border-white/10 pt-4 text-sm leading-relaxed text-white/64 sm:text-base">{{ item.answer }}</p>
                </CollapsibleSection>
            </motion.div>
        </div>
    </section>
</template>
