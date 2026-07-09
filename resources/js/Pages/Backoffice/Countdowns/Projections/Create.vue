<script setup lang="ts">
import { computed, ref } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import { ArrowLeft } from 'lucide-vue-next';
import { Button, Card } from '@simone-bianco/vue-ui-components';
import BackofficeShell from '@/Components/Backoffice/Layout/BackofficeShell.vue';
import ProjectionForm from '@/Components/Backoffice/Doomsday/ProjectionForm.vue';
import { useBackofficePath } from '@/Components/Backoffice/Doomsday/useBackofficePath';
import type { BackofficeOptions, CountdownSummary, ProjectionRecord } from '@/Components/Backoffice/Doomsday/types';
import type { BackofficeSection } from '@/Components/Backoffice/Layout/types';

const props = defineProps<{
    readonly countdown: CountdownSummary;
    readonly projection: ProjectionRecord | null;
    readonly options: BackofficeOptions;
}>();

const { backofficePath, normalizedBackofficePath, counts } = useBackofficePath();
const activeSection = ref<BackofficeSection>('countdowns');
const returnUrl = computed(() => countdownEditUrl('projections'));
const submitUrl = computed(() => `${normalizedBackofficePath.value}/countdowns/${props.countdown.id}/projections`);

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
    <Head :title="`Create projection · ${countdown.slug}`" />
    <BackofficeShell v-model:active-section="activeSection" title="Create projection" :subtitle="`Add a scenario projection for ${countdown.title.en ?? countdown.slug}.`" :backoffice-path="backofficePath" :counts="counts">
        <Card :ui="{ body: 'space-y-5 p-6' }">
            <div class="flex flex-wrap items-center justify-between gap-3 border-b border-ui-border/60 pb-4">
                <div>
                    <p class="font-semibold">Projection details</p>
                    <p class="text-sm text-ui-muted-foreground">Use a dedicated edit surface for forecast copy, scores and methodology.</p>
                </div>
                <Button variant="secondary" size="sm" :icon="ArrowLeft" @click="backToCountdown">Back to countdown</Button>
            </div>

            <ProjectionForm :options="options" :projection="projection ?? undefined" :submit-url="submitUrl" method="post" submit-label="Create projection" show-top-actions @saved="backToCountdown" @cancel="backToCountdown" />
        </Card>
    </BackofficeShell>
</template>
