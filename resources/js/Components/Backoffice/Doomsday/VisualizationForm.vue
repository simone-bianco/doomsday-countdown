<script setup lang="ts">
import { computed, ref, watch } from 'vue';
import { Button, NumberInput, TextInput, Textarea } from '@simone-bianco/vue-ui-components';
import { useSmartForm } from '@simone-bianco/vue-form-core';
import BackofficeSelectField from '@/Components/Backoffice/Shared/BackofficeSelectField.vue';
import FormActions from '@/Components/Backoffice/Shared/FormActions.vue';
import { SaveVisualizationDataRules } from '@/generated/form-rules';
import VisualizationPayloadEditor from '@/Components/Backoffice/Doomsday/VisualizationPayloadEditor.vue';
import { defaultPayload, first, localizedText, optionItems } from '@/Components/Backoffice/Doomsday/formHelpers';
import type { BackofficeOptions, LocalizedText, VisualizationPayload, VisualizationRecord } from '@/Components/Backoffice/Doomsday/types';
import type { SaveVisualizationData } from '@/types/generated';

type SaveVisualizationForm = Omit<SaveVisualizationData, 'title' | 'description' | 'payload'> & {
    title: LocalizedText;
    description: LocalizedText;
    payload: VisualizationPayload;
} & Record<string, unknown>;

const editableTypes = ['line', 'area', 'bar', 'kpi'] as const;

const props = withDefaults(defineProps<{
    readonly options: BackofficeOptions;
    readonly submitUrl: string;
    readonly method: 'post' | 'put';
    readonly submitLabel: string;
    readonly visualization?: VisualizationRecord;
    readonly showTopActions?: boolean;
}>(), {
    visualization: undefined,
    showTopActions: false,
});

function isChartType(type: string): boolean {
    return ['line', 'area', 'bar'].includes(type);
}

function schemaVersion(type: string): number {
    return isChartType(type) ? 2 : 1;
}

const initialType = props.visualization?.type ?? first(props.options.visualization_types.filter((type) => editableTypes.includes(type as typeof editableTypes[number])), 'line');
const initialPayload = (props.visualization?.payload as VisualizationPayload | undefined) ?? defaultPayload(initialType);
const emit = defineEmits<{ (event: 'saved'): void; (event: 'cancel'): void }>();
const form = useSmartForm<SaveVisualizationForm>({ ...SaveVisualizationDataRules });
const payloadDraft = ref<VisualizationPayload>(initialPayload);
const payloadIsValid = ref(true);
const supportedOptions = computed(() => optionItems(props.options.visualization_types.filter((type) => editableTypes.includes(type as typeof editableTypes[number]))));
const submitDisabled = computed(() => form.processing || !payloadIsValid.value);

form.fill({
    key: props.visualization?.key ?? '',
    type: initialType,
    title: localizedText(props.visualization?.title.en ?? ''),
    description: localizedText(props.visualization?.description?.en ?? ''),
    payload: initialPayload,
    schema_version: schemaVersion(initialType),
    sort_order: props.visualization?.sort_order ?? 0,
});

function chooseType(value: string | number | null): void {
    if (typeof value === 'string') {
        form.type = value;
    }
}

watch(() => form.type, (type) => {
    if (!editableTypes.includes(type as typeof editableTypes[number])) {
        form.type = 'line';
        return;
    }

    form.schema_version = schemaVersion(type);
});

function submit(): void {
    if (!payloadIsValid.value) {
        return;
    }

    form.payload = payloadDraft.value;
    const method = props.method === 'post' ? form.post : form.put;
    method(props.submitUrl, { preserveScroll: true, onSuccess: () => emit('saved') });
}
</script>

<template>
    <form class="space-y-5" @submit.prevent="submit">
        <div v-if="showTopActions" class="flex flex-wrap items-center justify-end gap-2 border-b border-ui-border/60 pb-4">
            <Button type="submit" :loading="form.processing" :disabled="submitDisabled">{{ submitLabel }}</Button>
            <Button type="button" variant="secondary" @click="emit('cancel')">Cancel</Button>
        </div>

        <div class="grid gap-4 md:grid-cols-4">
            <TextInput v-model="form.key" label="Key" helper-text="Stable identifier used by frontend chart integrations." :error="form.errors.key" />
            <div>
                <BackofficeSelectField label="Type" :model-value="form.type" :options="supportedOptions" :clearable="false" @update:model-value="chooseType" />
                <p class="mt-1 text-xs text-ui-muted-foreground">Line and area are ordered series; bar is for categorical comparisons.</p>
            </div>
            <div>
                <NumberInput v-model="form.schema_version" label="Schema version" :min="isChartType(form.type) ? 2 : 1" :error="form.errors.schema_version" />
                <p class="mt-1 text-xs text-ui-muted-foreground">Line, area and bar use schema v2; KPI keeps its distinct payload contract.</p>
            </div>
            <div>
                <NumberInput v-model="form.sort_order" label="Sort order" :min="0" :error="form.errors.sort_order" />
                <p class="mt-1 text-xs text-ui-muted-foreground">Lower numbers appear first.</p>
            </div>
        </div>

        <div class="grid gap-4 md:grid-cols-2">
            <TextInput v-model="form.title.en" label="Title (EN)" :error="form.errors.title" />
            <Textarea v-model="form.description.en" label="Description (EN)" :rows="2" :error="form.errors.description" />
        </div>

        <VisualizationPayloadEditor v-model:type="form.type" v-model:payload="payloadDraft" v-model:valid="payloadIsValid" />

        <FormActions compact>
            <Button type="submit" :loading="form.processing" :disabled="submitDisabled">{{ submitLabel }}</Button>
            <Button type="button" variant="secondary" @click="emit('cancel')">Cancel</Button>
        </FormActions>
    </form>
</template>
