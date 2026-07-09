<script setup lang="ts">
import { Button, NumberInput, Textarea, TextInput, Toggle } from '@simone-bianco/vue-ui-components';
import { useSmartForm } from '@simone-bianco/vue-form-core';
import BackofficeSelectField from '@/Components/Backoffice/Shared/BackofficeSelectField.vue';
import FormActions from '@/Components/Backoffice/Shared/FormActions.vue';
import { SaveNewsDataRules } from '@/generated/form-rules';
import { first, isoDate, optionItems } from '@/Components/Backoffice/Doomsday/formHelpers';
import type { BackofficeOptions, NewsRecord } from '@/Components/Backoffice/Doomsday/types';
import type { SaveNewsData } from '@/types/generated';

type SaveNewsForm = SaveNewsData & Record<string, unknown>;

const props = defineProps<{
    readonly options: BackofficeOptions;
    readonly submitUrl: string;
    readonly method: 'post' | 'put';
    readonly submitLabel: string;
    readonly news?: NewsRecord;
}>();

const emit = defineEmits<{ (event: 'saved'): void; (event: 'cancel'): void }>();
const form = useSmartForm<SaveNewsForm>({ ...SaveNewsDataRules });
form.fill({
    locale: props.news?.locale ?? first(props.options.news_locales, 'en'),
    title: props.news?.title ?? '',
    excerpt: props.news?.excerpt ?? '',
    source_name: props.news?.source_name ?? null,
    source_url: props.news?.source_url ?? null,
    image_path: props.news?.image_path ?? null,
    published_at: isoDate(props.news?.published_at),
    sort_order: props.news?.sort_order ?? 0,
    is_featured: props.news?.is_featured ?? false,
});

function chooseLocale(value: string | number | null): void {
    if (typeof value === 'string') {
        form.locale = value;
    }
}

function submit(): void {
    const method = props.method === 'post' ? form.post : form.put;
    method(props.submitUrl, { preserveScroll: true, onSuccess: () => emit('saved') });
}
</script>

<template>
    <form class="space-y-4" @submit.prevent="submit">
        <div class="grid gap-4 md:grid-cols-3">
            <BackofficeSelectField label="Locale" :model-value="form.locale" :options="optionItems(options.news_locales)" :clearable="false" @update:model-value="chooseLocale" />
            <TextInput v-model="form.published_at" label="Published at" type="date" :error="form.errors.published_at" />
            <NumberInput v-model="form.sort_order" label="Sort order" :min="0" :error="form.errors.sort_order" />
        </div>

        <TextInput v-model="form.title" label="Title" :error="form.errors.title" />
        <Textarea v-model="form.excerpt" label="Excerpt" :rows="3" :error="form.errors.excerpt" />

        <div class="grid gap-4 md:grid-cols-3">
            <TextInput v-model="form.source_name" label="Source name" :error="form.errors.source_name" />
            <TextInput v-model="form.source_url" label="Source URL" :error="form.errors.source_url" />
            <TextInput v-model="form.image_path" label="Image path" :error="form.errors.image_path" />
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
