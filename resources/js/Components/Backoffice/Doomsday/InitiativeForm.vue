<script setup lang="ts">
import { Button, NumberInput, Textarea, TextInput, Toggle } from '@simone-bianco/vue-ui-components';
import { useSmartForm } from '@simone-bianco/vue-form-core';
import BackofficeSelectField from '@/Components/Backoffice/Shared/BackofficeSelectField.vue';
import FormActions from '@/Components/Backoffice/Shared/FormActions.vue';
import { SaveInitiativeDataRules } from '@/generated/form-rules';
import { first, isoDate, optionItems } from '@/Components/Backoffice/Doomsday/formHelpers';
import type { BackofficeOptions, InitiativeRecord } from '@/Components/Backoffice/Doomsday/types';
import type { SaveInitiativeData } from '@/types/generated';

type SaveInitiativeForm = SaveInitiativeData & Record<string, unknown>;

const props = defineProps<{
    readonly options: BackofficeOptions;
    readonly submitUrl: string;
    readonly method: 'post' | 'put';
    readonly submitLabel: string;
    readonly initiative?: InitiativeRecord;
}>();

const emit = defineEmits<{ (event: 'saved'): void; (event: 'cancel'): void }>();
const form = useSmartForm<SaveInitiativeForm>({ ...SaveInitiativeDataRules });
form.fill({
    locale: props.initiative?.locale ?? first(props.options.initiative_locales, 'en'),
    type: props.initiative?.type ?? first(props.options.initiative_types, 'resource'),
    title: props.initiative?.title ?? '',
    excerpt: props.initiative?.excerpt ?? '',
    body: props.initiative?.body ?? null,
    organization: props.initiative?.organization ?? null,
    url: props.initiative?.url ?? '',
    image_path: props.initiative?.image_path ?? null,
    cta_label: props.initiative?.cta_label ?? null,
    starts_at: isoDate(props.initiative?.starts_at),
    ends_at: isoDate(props.initiative?.ends_at),
    sort_order: props.initiative?.sort_order ?? 0,
    is_featured: props.initiative?.is_featured ?? false,
});

function chooseLocale(value: string | number | null): void {
    if (typeof value === 'string') form.locale = value;
}

function chooseType(value: string | number | null): void {
    if (typeof value === 'string') form.type = value;
}

function submit(): void {
    const method = props.method === 'post' ? form.post : form.put;
    method(props.submitUrl, { preserveScroll: true, onSuccess: () => emit('saved') });
}
</script>

<template>
    <form class="space-y-4" @submit.prevent="submit">
        <div class="grid gap-4 md:grid-cols-3">
            <BackofficeSelectField label="Locale" :model-value="form.locale" :options="optionItems(options.initiative_locales)" :clearable="false" @update:model-value="chooseLocale" />
            <BackofficeSelectField label="Type" :model-value="form.type" :options="optionItems(options.initiative_types)" :clearable="false" @update:model-value="chooseType" />
            <NumberInput v-model="form.sort_order" label="Sort order" :min="0" :error="form.errors.sort_order" />
        </div>

        <TextInput v-model="form.title" label="Title" :error="form.errors.title" />
        <Textarea v-model="form.excerpt" label="Excerpt" :rows="3" :error="form.errors.excerpt" />
        <Textarea v-model="form.body" label="Body" :rows="4" :error="form.errors.body" />

        <div class="grid gap-4 md:grid-cols-3">
            <TextInput v-model="form.organization" label="Organization" :error="form.errors.organization" />
            <TextInput v-model="form.url" label="URL" :error="form.errors.url" />
            <TextInput v-model="form.cta_label" label="CTA label" :error="form.errors.cta_label" />
        </div>

        <div class="grid gap-4 md:grid-cols-3">
            <TextInput v-model="form.image_path" label="Image path" :error="form.errors.image_path" />
            <TextInput v-model="form.starts_at" label="Starts at" type="date" :error="form.errors.starts_at" />
            <TextInput v-model="form.ends_at" label="Ends at" type="date" :error="form.errors.ends_at" />
        </div>

        <FormActions compact>
            <Button type="submit" :loading="form.processing">{{ submitLabel }}</Button>
            <Button variant="secondary" @click="emit('cancel')">Cancel</Button>
            <template #aside>
                <Toggle v-model="form.is_featured" label="Featured" />
            </template>
        </FormActions>
    </form>
</template>
