<script setup lang="ts">
import { computed, ref } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import { ArrowLeft } from 'lucide-vue-next';
import { Button, Card } from '@simone-bianco/vue-ui-components';
import BackofficeShell from '@/Components/Backoffice/Layout/BackofficeShell.vue';
import VisualizationForm from '@/Components/Backoffice/Doomsday/VisualizationForm.vue';
import { useBackofficePath } from '@/Components/Backoffice/Doomsday/useBackofficePath';
import type { BackofficeOptions, CountdownSummary, ProjectionRecord, VisualizationRecord } from '@/Components/Backoffice/Doomsday/types';
import type { BackofficeSection } from '@/Components/Backoffice/Layout/types';

const props = defineProps<{
    readonly countdown: CountdownSummary;
    readonly projection: ProjectionRecord | null;
    readonly visualization: VisualizationRecord;
    readonly options: BackofficeOptions;
}>();

const { backofficePath, normalizedBackofficePath, counts } = useBackofficePath();
const activeSection = ref<BackofficeSection>('countdowns');
const returnTab = computed(() => props.projection === null ? 'visualizations' : 'projections');
const returnUrl = computed(() => countdownEditUrl(returnTab.value));
const submitUrl = computed(() => props.projection === null
    ? `${normalizedBackofficePath.value}/countdowns/${props.countdown.id}/visualizations/${props.visualization.id}`
    : `${normalizedBackofficePath.value}/countdowns/${props.countdown.id}/projections/${props.projection.id}/visualizations/${props.visualization.id}`);

function countdownEditUrl(tab: string): string {
    const params = new URLSearchParams(typeof window === 'undefined' ? '' : window.location.search);
    params.set('tab', tab);

    return `${normalizedBackofficePath.value}/countdowns/${props.countdown.id}/edit?${params.toString()}`;
}

function backToCountdown(): void {
    router.visit(returnUrl.value, { preserveScroll: true, preserveState: true });
}
</script>

<template>
    <Head :title="`Edit visualization · ${visualization.title.en ?? visualization.key}`" />
    <BackofficeShell v-model:active-section="activeSection" title="Edit visualization" :subtitle="`Update ${visualization.title.en ?? visualization.key} for ${projection?.title.en ?? countdown.title.en ?? countdown.slug}.`" :backoffice-path="backofficePath" :counts="counts">
        <Card :ui="{ body: 'space-y-5 p-6' }">
            <div class="flex flex-wrap items-center justify-between gap-3 border-b border-ui-border/60 pb-4">
                <div>
                    <p class="font-semibold">Visualization payload</p>
                    <p class="text-sm text-ui-muted-foreground">Use a dedicated edit surface for chart metadata, payload and preview setup.</p>
                </div>
                <Button variant="secondary" size="sm" :icon="ArrowLeft" @click="backToCountdown">Back to countdown</Button>
            </div>

            <VisualizationForm :options="options" :visualization="visualization" :submit-url="submitUrl" method="put" submit-label="Save visualization" show-top-actions @saved="backToCountdown" @cancel="backToCountdown" />
        </Card>
    </BackofficeShell>
</template>
