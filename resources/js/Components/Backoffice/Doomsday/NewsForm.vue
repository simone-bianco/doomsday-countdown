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
const mediaTypeOptions = optionItems(['article', 'youtube_video']);
const form = useSmartForm<SaveNewsForm>({ ...SaveNewsDataRules });
form.fill({
    locale: props.news?.locale ?? first(props.options.news_locales, 'en'),
    title: props.news?.title ?? '',
    excerpt: props.news?.excerpt ?? '',
    content_type: props.news?.content_type ?? 'article',
    source_name: props.news?.source_name ?? null,
    source_url: props.news?.source_url ?? null,
    preview_image_url: props.news?.preview_image_url ?? null,
    embed_url: props.news?.embed_url ?? null,
    external_provider: props.news?.external_provider ?? null,
    external_id: props.news?.external_id ?? null,
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

function chooseContentType(value: string | number | null): void {
    if (typeof value === 'string') {
        form.content_type = value;
    }
}

function submit(): void {
    const method = props.method === 'post' ? form.post : form.put;
    method(props.submitUrl, { preserveScroll: true, onSuccess: () => emit('saved') });
}
</script>

<template>
    <form class="space-y-4" @submit.prevent="submit">
        <div class="grid gap-4 md:grid-cols-5">
            <BackofficeSelectField label="Locale" :model-value="form.locale" :options="optionItems(options.news_locales)" :clearable="false" @update:model-value="chooseLocale" />
            <BackofficeSelectField label="Content type" :model-value="form.content_type" :options="mediaTypeOptions" :clearable="false" :error="form.errors.content_type" @update:model-value="chooseContentType" />
            <TextInput v-model="form.published_at" label="Published at" type="date" helper-text="Optional publication date shown in backoffice without microseconds." :error="form.errors.published_at" />
            <div>
                <NumberInput v-model="form.sort_order" label="Sort order" :min="0" :error="form.errors.sort_order" />
                <p class="mt-1 text-xs text-ui-muted-foreground">Lower numbers appear first after publication date ordering.</p>
            </div>
            <div class="pt-6">
                <Toggle v-model="form.is_featured" label="Featured" description="Prioritizes and highlights this news item in public surfaces." />
            </div>
        </div>

        <TextInput v-model="form.title" label="Title" :error="form.errors.title" />
        <Textarea v-model="form.excerpt" label="Excerpt" :rows="3" helper-text="Public previews are truncated by the configured content limit; the stored text remains complete." :error="form.errors.excerpt" />

        <div class="grid gap-4 md:grid-cols-3">
            <TextInput v-model="form.source_name" label="Source name" helper-text="Publisher or outlet shown with the article." :error="form.errors.source_name" />
            <TextInput v-model="form.source_url" label="Source URL" helper-text="Use an HTTPS article URL or YouTube video URL." :error="form.errors.source_url" />
            <TextInput v-model="form.preview_image_url" label="Preview image URL" helper-text="Optional HTTPS image. YouTube thumbnails are derived when omitted." :error="form.errors.preview_image_url" />
        </div>

        <div class="grid gap-4 md:grid-cols-3">
            <TextInput v-model="form.image_path" label="Local image path" helper-text="Local fallback path used when the remote preview is missing or invalid." :error="form.errors.image_path" />
            <TextInput v-model="form.external_provider" label="External provider" :disabled="form.content_type === 'youtube_video'" helper-text="Derived as youtube for video content." :error="form.errors.external_provider" />
            <TextInput v-model="form.external_id" label="External ID" :disabled="form.content_type === 'youtube_video'" helper-text="Derived from the YouTube URL for video content." :error="form.errors.external_id" />
        </div>

        <TextInput v-model="form.embed_url" label="Embed URL" :disabled="form.content_type === 'youtube_video'" helper-text="Persisted for future use, but never rendered inline on the public page. Derived for YouTube videos." :error="form.errors.embed_url" />

        <FormActions compact>
            <Button type="submit" :loading="form.processing">{{ submitLabel }}</Button>
            <Button type="button" variant="secondary" @click="emit('cancel')">Cancel</Button>
        </FormActions>
    </form>
</template>
