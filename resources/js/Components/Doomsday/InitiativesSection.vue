<script setup lang="ts">
import { ExternalLink } from 'lucide-vue-next';
import { Card, Image } from '@simone-bianco/vue-ui-components';
import { t } from '@/i18n';
import type { CountdownInitiativesSectionData } from '@/types/generated';

defineProps<{
    readonly section: CountdownInitiativesSectionData;
}>();
</script>

<template>
    <Card :ui="{ root: 'doomsday-card rounded-xl sm:col-span-2', body: 'p-5 sm:p-6' }">
        <h3 class="doomsday-display mb-5 text-white">{{ t('initiatives') }}</h3>
        <div class="grid grid-cols-1 gap-4">
            <a
                v-for="item in section.initiatives"
                :key="item.title"
                :href="item.url"
                target="_blank"
                rel="noopener noreferrer"
                class="grid min-w-0 gap-4 rounded-lg border border-ui-primary/20 bg-ui-primary/5 p-3 transition hover:border-ui-primary/60 hover:bg-ui-primary/[0.09] sm:grid-cols-[132px_1fr_auto] sm:items-center"
            >
                <Image v-if="item.image_url" :src="item.image_url" :alt="item.title" aspect-ratio="72%" rounded="md" :ui="{ root: 'min-w-0', image: 'h-full w-full object-cover' }" />
                <div class="min-w-0">
                    <p class="doomsday-display text-xs text-ui-primary">{{ item.type }}</p>
                    <h4 class="mt-2 text-base font-semibold leading-snug text-white">{{ item.title }}</h4>
                    <p class="mt-2 text-sm leading-relaxed text-ui-muted-foreground">{{ item.excerpt }}</p>
                    <p v-if="item.organization" class="mt-3 text-xs text-white/60">{{ item.organization }}</p>
                </div>
                <span class="inline-flex items-center gap-2 text-xs font-semibold text-ui-primary sm:justify-self-end">
                    {{ item.cta_label || t('viewDetails') }}
                    <ExternalLink class="h-4 w-4" />
                </span>
            </a>
        </div>
    </Card>
</template>
