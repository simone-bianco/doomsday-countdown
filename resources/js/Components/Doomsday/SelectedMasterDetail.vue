<script setup lang="ts">
import CountdownList from './CountdownList.vue';
import DetailPanel from './DetailPanel.vue';
import type { CountdownDetailData, CountdownIndexData } from '@/types/generated';

defineProps<{
    readonly countdowns: readonly CountdownIndexData[];
    readonly selectedCountdown: CountdownDetailData;
    readonly hero: Record<string, string>;
    readonly currentLocale: string;
}>();
</script>

<template>
    <section class="mx-auto hidden max-w-[1760px] grid-cols-[minmax(500px,0.95fr)_minmax(720px,1.25fr)] gap-5 px-7 py-7 lg:grid">
        <div class="grid content-start gap-5">
            <div class="relative overflow-hidden rounded-2xl border border-white/10 bg-black p-8">
                <img :src="hero.desktop_image" alt="Earth horizon with red monitoring arcs" class="absolute inset-0 h-full w-full object-cover object-center opacity-45" />
                <div class="absolute inset-0 bg-gradient-to-r from-black via-black/85 to-black/20" />
                <div class="relative max-w-2xl">
                    <div class="mb-6 h-px w-24 bg-ui-primary/70" />
                    <h1 class="doomsday-display text-3xl leading-tight text-white 2xl:text-5xl">
                        {{ hero.headline_prefix }}<br />
                        <span class="text-white/70">{{ hero.headline_middle }}</span>
                        <span class="doomsday-red-text"> {{ hero.headline_accent }}</span>
                    </h1>
                    <p class="mt-5 text-base text-ui-muted-foreground">{{ hero.subtitle }}</p>
                </div>
            </div>

            <CountdownList :countdowns="countdowns" :compact="true" />
        </div>

        <div class="sticky top-28 max-h-[calc(100vh-8rem)] self-start overflow-y-auto pr-1">
            <DetailPanel :countdown="selectedCountdown" :current-locale="currentLocale" />
        </div>
    </section>
</template>
