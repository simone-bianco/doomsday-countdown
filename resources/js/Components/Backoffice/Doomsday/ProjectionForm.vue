<script setup lang="ts">
import { Button, NumberInput, Textarea, TextInput } from '@simone-bianco/vue-ui-components';
import { useSmartForm } from '@simone-bianco/vue-form-core';
import BackofficeSelectField from '@/Components/Backoffice/Shared/BackofficeSelectField.vue';
import FormActions from '@/Components/Backoffice/Shared/FormActions.vue';
import { SaveProjectionDataRules } from '@/generated/form-rules';
import { first, isoDate, localizedText, optionItems } from '@/Components/Backoffice/Doomsday/formHelpers';
import type { BackofficeOptions, ProjectionRecord } from '@/Components/Backoffice/Doomsday/types';
import type { SaveProjectionData } from '@/types/generated';

type SaveProjectionForm = SaveProjectionData & Record<string, unknown>;

const props = withDefaults(defineProps<{
    readonly options: BackofficeOptions;
    readonly submitUrl: string;
    readonly method: 'post' | 'put';
    readonly submitLabel: string;
    readonly projection?: ProjectionRecord;
    readonly showTopActions?: boolean;
}>(), {
    projection: undefined,
    showTopActions: false,
});

const emit = defineEmits<{
    (event: 'saved'): void;
    (event: 'cancel'): void;
}>();

const form = useSmartForm<SaveProjectionForm>({ ...SaveProjectionDataRules });
form.fill({
    type: props.projection?.type ?? first(props.options.projection_types, 'neutral'),
    target_date: isoDate(props.projection?.target_date),
    title: localizedText(props.projection?.title.en ?? ''),
    summary: localizedText(props.projection?.summary?.en ?? ''),
    confidence_score: props.projection?.confidence_score ?? 50,
    probability_score: props.projection?.probability_score ?? 50,
    trend: props.projection?.trend ?? 'stable',
    methodology: localizedText(props.projection?.methodology?.en ?? ''),
    sort_order: props.projection?.sort_order ?? 0,
});

function chooseType(value: string | number | null): void {
    if (typeof value === 'string') {
        form.type = value;
    }
}

function submit(): void {
    const method = props.method === 'post' ? form.post : form.put;
    method(props.submitUrl, { preserveScroll: true, onSuccess: () => emit('saved') });
}
</script>

<template>
    <form class="space-y-4" @submit.prevent="submit">
        <div v-if="showTopActions" class="flex flex-wrap items-center justify-end gap-2 border-b border-ui-border/60 pb-4">
            <Button type="submit" :loading="form.processing">{{ submitLabel }}</Button>
            <Button type="button" variant="secondary" @click="emit('cancel')">Cancel</Button>
        </div>

        <div class="grid gap-4 md:grid-cols-3">
            <div>
                <BackofficeSelectField label="Projection type" :model-value="form.type" :options="optionItems(options.projection_types)" :clearable="false" @update:model-value="chooseType" />
                <p class="mt-1 text-xs text-ui-muted-foreground">Groups the projection by scenario category in the public UI.</p>
            </div>
            <TextInput v-model="form.target_date" label="Target date" type="date" helper-text="Optional forecast date; shown as entered without timezone conversion." :error="form.errors.target_date" />
            <div>
                <NumberInput v-model="form.sort_order" label="Sort order" :min="0" :error="form.errors.sort_order" />
                <p class="mt-1 text-xs text-ui-muted-foreground">Lower numbers appear first.</p>
            </div>
        </div>

        <div class="grid gap-4 md:grid-cols-2">
            <TextInput v-model="form.title.en" label="Title (EN)" :error="form.errors.title" />
            <TextInput v-model="form.trend" label="Trend" helper-text="Short trend token such as stable, rising, falling, or worsening." :error="form.errors.trend" />
        </div>

        <Textarea v-model="form.summary.en" label="Summary (EN)" :rows="3" :error="form.errors.summary" />
        <div>
            <Textarea v-model="form.methodology.en" label="Methodology (EN)" :rows="3" :error="form.errors.methodology" />
            <p class="mt-1 text-xs text-ui-muted-foreground">Briefly explain the data or assumptions behind the projection.</p>
        </div>

        <div class="grid gap-4 md:grid-cols-2">
            <NumberInput v-model="form.confidence_score" label="Confidence score" :min="0" :max="100" :error="form.errors.confidence_score" />
            <NumberInput v-model="form.probability_score" label="Probability score" :min="0" :max="100" :error="form.errors.probability_score" />
        </div>

        <FormActions compact>
            <Button type="submit" :loading="form.processing">{{ submitLabel }}</Button>
            <Button type="button" variant="secondary" @click="emit('cancel')">Cancel</Button>
        </FormActions>
    </form>
</template>
