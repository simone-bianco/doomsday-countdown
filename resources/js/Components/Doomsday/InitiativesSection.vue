<script setup lang="ts">
import { Card } from '@simone-bianco/vue-ui-components';
import ContentPreviewCard from '@/Components/Doomsday/ContentPreviewCard.vue';
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
            <ContentPreviewCard
                v-for="item in section.initiatives"
                :key="`${item.title}-${item.url}`"
                :title="item.title"
                :href="item.url"
                :excerpt="item.excerpt"
                :image-url="item.image_url"
                :content-type="item.content_type"
                :external-provider="item.external_provider"
                :eyebrow="item.type"
                :metadata="item.organization"
                :cta-label="item.cta_label || t('viewDetails')"
                variant="initiative"
            />
        </div>
    </Card>
</template>
