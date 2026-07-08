<script setup lang="ts">
import { ExternalLink } from 'lucide-vue-next';
import { Button, Card, Image } from '@simone-bianco/vue-ui-components';
import { t } from '@/i18n';
import type { CountdownInitiativesSectionData } from '@/types/generated';

defineProps<{
    readonly section: CountdownInitiativesSectionData;
}>();
</script>

<template>
    <Card :ui="{ root: 'doomsday-card rounded-xl sm:col-span-2', body: 'p-5 sm:p-6' }">
        <h3 class="doomsday-display mb-5 text-white">{{ t('initiatives') }}</h3>
        <div class="grid gap-4 sm:grid-cols-2">
            <article v-for="item in section.initiatives" :key="item.title" class="rounded-lg border border-ui-primary/20 bg-ui-primary/5 p-4">
                <Image v-if="item.image_url" :src="item.image_url" :alt="item.title" aspect-ratio="56.25%" rounded="md" />
                <p class="doomsday-display mt-4 text-xs text-ui-primary">{{ item.type }}</p>
                <h4 class="mt-2 text-lg text-white">{{ item.title }}</h4>
                <p class="mt-2 text-sm leading-relaxed text-ui-muted-foreground">{{ item.excerpt }}</p>
                <p v-if="item.organization" class="mt-3 text-xs text-white/60">{{ item.organization }}</p>
                <a :href="item.url" target="_blank" rel="noopener noreferrer" class="mt-4 inline-block">
                    <Button variant="secondary" size="sm" :icon="ExternalLink">{{ item.cta_label || t('viewDetails') }}</Button>
                </a>
            </article>
        </div>
    </Card>
</template>
