<script setup lang="ts">
import { computed } from 'vue';
import { ExternalLink } from 'lucide-vue-next';
import { Image } from '@simone-bianco/vue-ui-components';

const props = withDefaults(defineProps<{
    readonly title: string;
    readonly href?: string | null;
    readonly excerpt: string;
    readonly imageUrl: string;
    readonly contentType?: string | null;
    readonly externalProvider?: string | null;
    readonly eyebrow?: string | null;
    readonly metadata?: string | null;
    readonly ctaLabel: string;
    readonly variant?: 'news' | 'initiative';
}>(), {
    href: null,
    contentType: 'article',
    externalProvider: null,
    eyebrow: null,
    metadata: null,
    variant: 'news',
});

const rootClass = computed(() => [
    'grid min-w-0 gap-4 rounded-lg border p-3 transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ui-primary/70 sm:grid-cols-[132px_1fr_auto] sm:items-center',
    props.variant === 'initiative'
        ? 'border-ui-primary/20 bg-ui-primary/5 hover:border-ui-primary/60 hover:bg-ui-primary/[0.09]'
        : 'border-white/10 bg-white/[0.03] hover:border-ui-primary/50 hover:bg-ui-primary/[0.07]',
]);

const providerLabel = computed(() => props.externalProvider?.replaceAll('_', ' ') ?? null);
const isVideo = computed(() => props.contentType === 'youtube_video');
</script>

<template>
    <component
        :is="href ? 'a' : 'article'"
        :href="href || undefined"
        :target="href ? '_blank' : undefined"
        :rel="href ? 'noopener noreferrer' : undefined"
        :class="rootClass"
    >
        <Image :src="imageUrl" :alt="title" aspect-ratio="72%" rounded="md" :ui="{ root: 'min-w-0', image: 'h-full w-full object-cover' }" />

        <div class="min-w-0">
            <div class="flex flex-wrap items-center gap-2">
                <span v-if="eyebrow" class="doomsday-display text-xs text-ui-primary">{{ eyebrow }}</span>
                <span v-if="isVideo" class="rounded-full border border-red-400/40 bg-red-500/10 px-2 py-0.5 text-[10px] font-semibold uppercase tracking-[0.18em] text-red-200">Video</span>
                <span v-if="providerLabel" class="rounded-full border border-white/15 bg-white/5 px-2 py-0.5 text-[10px] font-semibold uppercase tracking-[0.14em] text-white/70">{{ providerLabel }}</span>
            </div>
            <h4 :class="['text-base font-semibold leading-snug text-white', eyebrow || isVideo || providerLabel ? 'mt-2' : '']">{{ title }}</h4>
            <p class="mt-2 text-sm leading-relaxed text-ui-muted-foreground">{{ excerpt }}</p>
            <p v-if="metadata" class="mt-3 text-xs text-white/60">{{ metadata }}</p>
        </div>

        <span v-if="href" class="inline-flex items-center gap-2 text-xs font-semibold text-ui-primary sm:justify-self-end">
            {{ ctaLabel }}
            <ExternalLink class="h-4 w-4" aria-hidden="true" />
        </span>
    </component>
</template>
