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
        <div class="grid gap-4 sm:grid-cols-2">
            <article v-for="item in section.news" :key="item.title" class="grid gap-3 rounded-lg border border-white/10 bg-white/[0.03] p-3 sm:grid-cols-[96px_1fr]">
                <Image v-if="item.image_url" :src="item.image_url" :alt="item.title" aspect-ratio="75%" rounded="md" />
                <div>
                    <h4 class="text-sm text-white">{{ item.title }}</h4>
                    <p class="mt-2 text-xs leading-relaxed text-ui-muted-foreground">{{ item.excerpt }}</p>
                    <p class="mt-3 text-xs text-ui-primary">{{ item.source_name }}</p>
                </div>
            </article>
        </div>
    </Card>
</template>
