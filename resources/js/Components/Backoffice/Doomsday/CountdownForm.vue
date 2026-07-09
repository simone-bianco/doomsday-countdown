<script setup lang="ts">
import { ref } from 'vue';
import { Button, Card, NumberInput, Textarea, TextInput, Toggle } from '@simone-bianco/vue-ui-components';
import { useSmartForm } from '@simone-bianco/vue-form-core';
import BackofficeSelectField from '@/Components/Backoffice/Shared/BackofficeSelectField.vue';
import FormActions from '@/Components/Backoffice/Shared/FormActions.vue';
import { SaveCountdownDataRules } from '@/generated/form-rules';
import type { CountdownDetail, BackofficeOptions } from '@/Components/Backoffice/Doomsday/types';
import type { SaveCountdownData } from '@/types/generated';

type SaveCountdownForm = SaveCountdownData & Record<string, unknown>;

const props = withDefaults(defineProps<{
    readonly options: BackofficeOptions;
    readonly submitUrl: string;
    readonly method: 'post' | 'put';
    readonly submitLabel: string;
    readonly countdown?: CountdownDetail;
    readonly embedded?: boolean;
}>(), {
    countdown: undefined,
    embedded: false,
});

const emit = defineEmits<{
    (event: 'saved'): void;
}>();

const form = useSmartForm<SaveCountdownForm>({ ...SaveCountdownDataRules });
const causesText = ref('');
const consequencesText = ref('');
const recommendedActionsText = ref('');

function localizedText(value = ''): Record<string, string> {
    return { en: value };
}

function localizedList(value: string[] = []): Record<string, string[]> {
    return { en: value };
}

function lines(value: string): string[] {
    return value.split('\n').map((line) => line.trim()).filter((line) => line !== '');
}

function first(values: readonly string[], fallback: string): string {
    return values[0] ?? fallback;
}

function isoDate(value: string | null | undefined): string | null {
    return value ? value.slice(0, 10) : null;
}

function fill(countdown?: CountdownDetail): void {
    form.fill({
        slug: countdown?.slug ?? '',
        title: localizedText(countdown?.title?.en ?? ''),
        summary: localizedText(countdown?.summary?.en ?? ''),
        description: localizedText(countdown?.description?.en ?? ''),
        causes: localizedList(countdown?.causes?.en ?? []),
        consequences: localizedList(countdown?.consequences?.en ?? []),
        recommended_actions: localizedList(countdown?.recommended_actions?.en ?? []),
        icon: countdown?.icon ?? 'alert-triangle',
        severity: countdown?.severity ?? first(props.options.countdown_severities, 'moderate'),
        status: countdown?.status ?? first(props.options.countdown_statuses, 'draft'),
        target_date: isoDate(countdown?.target_date),
        image_path: countdown?.image_path ?? '/images/doomsday_hero_background_desktop.png',
        accent_color: countdown?.accent_color ?? '#ff2a23',
        sort_order: countdown?.sort_order ?? 0,
        is_published: countdown?.is_published ?? false,
    });

    causesText.value = (countdown?.causes?.en ?? []).join('\n');
    consequencesText.value = (countdown?.consequences?.en ?? []).join('\n');
    recommendedActionsText.value = (countdown?.recommended_actions?.en ?? []).join('\n');
}

function selectSeverity(value: string | number | null): void {
    if (typeof value === 'string') {
        form.severity = value;
    }
}

function selectStatus(value: string | number | null): void {
    if (typeof value === 'string') {
        form.status = value;
    }
}

function syncLists(): void {
    form.causes = localizedList(lines(causesText.value));
    form.consequences = localizedList(lines(consequencesText.value));
    form.recommended_actions = localizedList(lines(recommendedActionsText.value));
}

function submit(): void {
    syncLists();
    const method = props.method === 'post' ? form.post : form.put;
    method(props.submitUrl, { onSuccess: () => emit('saved') });
}

fill(props.countdown);
</script>

<template>
    <form v-if="embedded" class="space-y-6" @submit.prevent="submit">
        <div class="grid gap-4 md:grid-cols-2">
            <TextInput v-model="form.slug" label="Slug" :error="form.errors.slug" @blur="form.validateField('slug')" />
            <TextInput v-model="form.title.en" label="Title (EN)" :error="form.errors.title" />
        </div>

        <Textarea v-model="form.summary.en" label="Summary (EN)" :error="form.errors.summary" :rows="3" />
        <Textarea v-model="form.description.en" label="Description (EN)" :error="form.errors.description" :rows="4" />

        <div class="grid gap-4 md:grid-cols-3">
            <Textarea v-model="causesText" label="Causes (EN, one per line)" :rows="5" />
            <Textarea v-model="consequencesText" label="Consequences (EN, one per line)" :rows="5" />
            <Textarea v-model="recommendedActionsText" label="Recommended actions (EN, one per line)" :rows="5" />
        </div>

        <div class="grid gap-4 md:grid-cols-4">
            <TextInput v-model="form.icon" label="Icon key" :error="form.errors.icon" />
            <BackofficeSelectField label="Severity" :model-value="form.severity" :options="options.countdown_severities.map((value) => ({ value, label: value }))" :clearable="false" @update:model-value="selectSeverity" />
            <BackofficeSelectField label="Status" :model-value="form.status" :options="options.countdown_statuses.map((value) => ({ value, label: value }))" :clearable="false" @update:model-value="selectStatus" />
            <TextInput v-model="form.target_date" label="Target date" type="date" :error="form.errors.target_date" />
        </div>

        <div class="grid gap-4 md:grid-cols-3">
            <TextInput v-model="form.image_path" label="Image path" :error="form.errors.image_path" />
            <TextInput v-model="form.accent_color" label="Accent color" :error="form.errors.accent_color" />
            <NumberInput v-model="form.sort_order" label="Sort order" :min="0" :error="form.errors.sort_order" />
        </div>

        <FormActions>
            <Button type="submit" :loading="form.processing">{{ submitLabel }}</Button>
            <template #aside>
                <Toggle v-model="form.is_published" label="Published" on-label="Visible publicly" off-label="Draft" />
            </template>
        </FormActions>
    </form>

    <Card v-else :ui="{ body: 'space-y-6 p-6' }">
        <form class="space-y-6" @submit.prevent="submit">
            <div class="grid gap-4 md:grid-cols-2">
                <TextInput v-model="form.slug" label="Slug" :error="form.errors.slug" @blur="form.validateField('slug')" />
                <TextInput v-model="form.title.en" label="Title (EN)" :error="form.errors.title" />
            </div>

            <Textarea v-model="form.summary.en" label="Summary (EN)" :error="form.errors.summary" :rows="3" />
            <Textarea v-model="form.description.en" label="Description (EN)" :error="form.errors.description" :rows="4" />

            <div class="grid gap-4 md:grid-cols-3">
                <Textarea v-model="causesText" label="Causes (EN, one per line)" :rows="5" />
                <Textarea v-model="consequencesText" label="Consequences (EN, one per line)" :rows="5" />
                <Textarea v-model="recommendedActionsText" label="Recommended actions (EN, one per line)" :rows="5" />
            </div>

            <div class="grid gap-4 md:grid-cols-4">
                <TextInput v-model="form.icon" label="Icon key" :error="form.errors.icon" />
                <BackofficeSelectField label="Severity" :model-value="form.severity" :options="options.countdown_severities.map((value) => ({ value, label: value }))" :clearable="false" @update:model-value="selectSeverity" />
                <BackofficeSelectField label="Status" :model-value="form.status" :options="options.countdown_statuses.map((value) => ({ value, label: value }))" :clearable="false" @update:model-value="selectStatus" />
                <TextInput v-model="form.target_date" label="Target date" type="date" :error="form.errors.target_date" />
            </div>

            <div class="grid gap-4 md:grid-cols-3">
                <TextInput v-model="form.image_path" label="Image path" :error="form.errors.image_path" />
                <TextInput v-model="form.accent_color" label="Accent color" :error="form.errors.accent_color" />
                <NumberInput v-model="form.sort_order" label="Sort order" :min="0" :error="form.errors.sort_order" />
            </div>

            <FormActions>
                <Button type="submit" :loading="form.processing">{{ submitLabel }}</Button>
                <template #aside>
                    <Toggle v-model="form.is_published" label="Published" on-label="Visible publicly" off-label="Draft" />
                </template>
            </FormActions>
        </form>
    </Card>
</template>
