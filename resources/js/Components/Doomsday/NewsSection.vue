<script setup lang="ts">
import { Card } from '@simone-bianco/vue-ui-components';
import ContentPreviewCard from '@/Components/Doomsday/ContentPreviewCard.vue';
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
            <ContentPreviewCard
                v-for="item in section.news"
                :key="`${item.title}-${item.source_url ?? 'no-link'}`"
                :title="item.title"
                :href="item.source_url"
                :excerpt="item.excerpt"
                :image-url="item.image_url"
                :content-type="item.content_type"
                :external-provider="item.external_provider"
                :metadata="item.source_name"
                :cta-label="t('viewDetails')"
                variant="news"
            />
        </div>
    </Card>
</template>
