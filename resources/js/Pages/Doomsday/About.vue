<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { Card } from '@simone-bianco/vue-ui-components';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import type { AboutPageData } from '@/types/generated';

defineProps<{
    readonly page: AboutPageData;
}>();
</script>

<template>
    <Head :title="page.title" />
    <PublicLayout :languages="page.languages" :current-locale="page.current_locale" active-page="about">
        <section class="mx-auto max-w-[1760px] px-4 py-10 sm:px-7 lg:py-16">
            <div class="grid gap-6 lg:grid-cols-[1.05fr_0.95fr]">
                <Card :ui="{ root: 'doomsday-card doomsday-glow rounded-2xl overflow-hidden', body: 'p-7 sm:p-10' }">
                    <div class="mb-8 h-px w-28 bg-ui-primary" />
                    <p class="doomsday-display text-xs text-ui-primary">Operational methodology</p>
                    <h1 class="doomsday-display mt-5 max-w-4xl text-4xl leading-tight text-white sm:text-6xl lg:text-7xl">{{ page.title }}</h1>
                    <p class="mt-7 max-w-3xl text-lg leading-relaxed text-ui-muted-foreground sm:text-xl">{{ page.subtitle }}</p>
                    <div class="mt-10 grid gap-3 text-sm sm:grid-cols-3">
                        <div class="rounded-xl border border-white/10 bg-black/45 p-4">
                            <span class="doomsday-display text-ui-primary">01</span>
                            <p class="mt-2 text-white">Scenario-based monitoring</p>
                        </div>
                        <div class="rounded-xl border border-white/10 bg-black/45 p-4">
                            <span class="doomsday-display text-ui-primary">02</span>
                            <p class="mt-2 text-white">Transparent assumptions</p>
                        </div>
                        <div class="rounded-xl border border-white/10 bg-black/45 p-4">
                            <span class="doomsday-display text-ui-primary">03</span>
                            <p class="mt-2 text-white">Non-deterministic dates</p>
                        </div>
                    </div>
                </Card>

                <div class="grid gap-5">
                    <Card :ui="{ root: 'doomsday-card rounded-2xl', body: 'p-7' }">
                        <p class="doomsday-display text-xs text-ui-primary">Phase 1 status</p>
                        <div class="mt-5 flex items-end gap-3 text-ui-primary">
                            <span class="doomsday-display text-7xl leading-none">04</span>
                            <span class="pb-3 text-white/65">sample scenarios online</span>
                        </div>
                        <p class="mt-5 leading-relaxed text-ui-muted-foreground">The public interface currently uses local seeded scenario data and curated visual assets. No live feeds are ingested in this phase.</p>
                    </Card>
                    <Card :ui="{ root: 'doomsday-card rounded-2xl', body: 'p-7' }">
                        <p class="doomsday-display text-xs text-ui-primary">How to read the clock</p>
                        <p class="mt-5 leading-relaxed text-ui-muted-foreground">Countdowns are pressure indicators. They express an estimated target window for a scenario, not a guaranteed event date.</p>
                    </Card>
                </div>
            </div>

            <div class="mt-6 grid gap-5 lg:grid-cols-3">
                <Card v-for="section in page.sections" :key="section.title" :ui="{ root: 'doomsday-card rounded-2xl', body: 'p-7' }">
                    <h2 class="doomsday-display text-xl text-white">{{ section.title }}</h2>
                    <p class="mt-5 leading-relaxed text-ui-muted-foreground">{{ section.body }}</p>
                </Card>
            </div>
        </section>
    </PublicLayout>
</template>
