<script setup lang="ts">
import { computed, ref, watch } from 'vue';
import { Button, NumberInput, TextInput, Textarea } from '@simone-bianco/vue-ui-components';
import { useSmartForm } from '@simone-bianco/vue-form-core';
import BackofficeSelectField from '@/Components/Backoffice/Shared/BackofficeSelectField.vue';
import FormActions from '@/Components/Backoffice/Shared/FormActions.vue';
import { SaveVisualizationDataRules } from '@/generated/form-rules';
import VisualizationPayloadEditor from '@/Components/Backoffice/Doomsday/VisualizationPayloadEditor.vue';
import { defaultPayload, first, localizedText, optionItems } from '@/Components/Backoffice/Doomsday/formHelpers';
import type { BackofficeOptions, VisualizationPayload, VisualizationRecord } from '@/Components/Backoffice/Doomsday/types';
import type { SaveVisualizationData } from '@/types/generated';

type SaveVisualizationForm = SaveVisualizationData & Record<string, unknown>;

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

const emit = defineEmits<{ (event: 'saved'): void; (event: 'cancel'): void }>();
const form = useSmartForm<SaveVisualizationForm>({ ...SaveVisualizationDataRules });
const payloadDraft = ref<VisualizationPayload>((props.visualization?.payload as VisualizationPayload | undefined) ?? defaultPayload(props.visualization?.type ?? 'line'));
const payloadIsValid = ref(true);
const supportedOptions = computed(() => optionItems(props.options.visualization_types.filter((type) => ['line', 'area', 'kpi'].includes(type))));
const submitDisabled = computed(() => form.processing || !payloadIsValid.value);

form.fill({
    key: props.visualization?.key ?? '',
    type: props.visualization?.type ?? first(props.options.visualization_types.filter((type) => ['line', 'area', 'kpi'].includes(type)), 'line'),
    title: localizedText(props.visualization?.title.en ?? ''),
    description: localizedText(props.visualization?.description?.en ?? ''),
    payload: ((props.visualization?.payload as Array<unknown> | undefined) ?? defaultPayload('line') as Array<unknown>),
    schema_version: props.visualization?.schema_version ?? 1,
    sort_order: props.visualization?.sort_order ?? 0,
});

function chooseType(value: string | number | null): void {
    if (typeof value === 'string') {
        form.type = value;
    }
}

watch(() => form.type, (type) => {
    if (!['line', 'area', 'kpi'].includes(type)) {
        form.type = 'line';
    }
});

function submit(): void {
    if (!payloadIsValid.value) {
        return;
    }

    form.payload = payloadDraft.value as unknown as Array<unknown>;
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
                <p class="mt-1 text-xs text-ui-muted-foreground">Controls which payload editor and public visualization are used.</p>
            </div>
            <div>
                <NumberInput v-model="form.schema_version" label="Schema version" :min="1" :error="form.errors.schema_version" />
                <p class="mt-1 text-xs text-ui-muted-foreground">Increment only when the payload shape intentionally changes.</p>
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
