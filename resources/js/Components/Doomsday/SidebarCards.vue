<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { Card, Image, Button } from '@simone-bianco/vue-ui-components';
import { ChevronRight } from 'lucide-vue-next';
import { t } from '@/i18n';
import type { CountdownIndexData } from '@/types/generated';

defineProps<{
    readonly featured: CountdownIndexData | null;
}>();
</script>

<template>
    <aside class="grid gap-5">
        <Card :ui="{ root: 'doomsday-card rounded-xl', header: 'doomsday-display text-ui-primary', body: 'space-y-4 p-5' }">
            <template #header>{{ t('latestUpdate') }}</template>
            <div v-if="featured" class="space-y-4">
                <Image :src="featured.image_url" :alt="featured.title" aspect-ratio="56.25%" rounded="lg" />
                <h3 class="doomsday-display text-xl text-white">{{ t('dailyUpdate') }}</h3>
                <p class="text-sm leading-relaxed text-ui-muted-foreground">{{ featured.summary }}</p>
                <Link :href="featured.url" class="block w-full sm:w-fit">
                    <Button
                        variant="secondary"
                        size="md"
                        icon-position="right"
                        :icon="ChevronRight"
                        :ui="{ root: 'group doomsday-display w-full border-ui-primary/50 bg-ui-primary/10 px-4 py-2.5 text-[0.7rem] font-bold uppercase tracking-[0.12em] text-ui-primary shadow-[0_0_18px_rgba(255,42,35,0.16)] hover:border-ui-primary hover:bg-ui-primary/20 hover:text-white hover:shadow-[0_0_26px_rgba(255,42,35,0.28)] sm:w-auto', icon: 'transition-transform group-hover:translate-x-0.5' }"
                    >
                        {{ t('viewDetails') }}
                    </Button>
                </Link>
            </div>
        </Card>
        <Card :ui="{ root: 'doomsday-card rounded-xl', body: 'p-6' }">
            <h3 class="doomsday-display text-ui-primary">{{ t('riskIndex') }}</h3>
            <p class="mt-2 text-sm text-ui-muted-foreground">Weighted risk across active public-source scenarios</p>
            <div class="mt-5 flex items-end gap-2 text-ui-primary">
                <span class="text-6xl leading-none">18.0</span><span class="pb-2 text-white/60">/100</span>
            </div>
            <div class="mt-4 h-12 rounded bg-gradient-to-r from-ui-primary/5 via-ui-primary/20 to-ui-primary/40" />
        </Card>
        <p class="px-5 text-sm leading-relaxed text-ui-muted-foreground"><span class="text-3xl text-ui-primary">“</span> The end is not sudden. It is a series of events no one wants to believe in—until it is too late.</p>
    </aside>
</template>
