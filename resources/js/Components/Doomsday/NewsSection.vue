<script setup lang="ts">
import { Card, Image } from '@simone-bianco/vue-ui-components';
import { t } from '@/i18n';
import type { CountdownNewsSectionData } from '@/types/generated';

defineProps<{
    readonly section: CountdownNewsSectionData;
}>();
</script>

<template>
    <Card :ui="{ root: 'doomsday-card rounded-xl sm:col-span-2', body: 'p-5 sm:p-6' }">
        <h3 class="doomsday-display mb-5 text-white">{{ t('news') }}</h3>
        <div class="grid grid-cols-1 gap-4">
            <a
                v-for="item in section.news"
                :key="item.title"
                :href="item.source_url ?? '#'"
                target="_blank"
                rel="noopener noreferrer"
                class="grid min-w-0 gap-4 rounded-lg border border-white/10 bg-white/[0.03] p-3 transition hover:border-ui-primary/50 hover:bg-ui-primary/[0.07] sm:grid-cols-[132px_1fr]"
            >
                <Image v-if="item.image_url" :src="item.image_url" :alt="item.title" aspect-ratio="72%" rounded="md" :ui="{ root: 'min-w-0', image: 'h-full w-full object-cover' }" />
                <div class="min-w-0">
                    <h4 class="text-base font-semibold leading-snug text-white">{{ item.title }}</h4>
                    <p class="mt-2 text-sm leading-relaxed text-ui-muted-foreground">{{ item.excerpt }}</p>
                    <p class="mt-3 text-xs text-ui-primary">{{ item.source_name ?? 'Source' }}</p>
                </div>
            </a>
        </div>
    </Card>
</template>
