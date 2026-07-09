<script setup lang="ts">
import { computed, ref, watch } from 'vue';
import { Button, Card, NumberInput, TabPanel, Tabs, Textarea, TextInput, Toggle } from '@simone-bianco/vue-ui-components';
import type { ErrorInput } from '@simone-bianco/vue-ui-components';
import { useSmartForm } from '@simone-bianco/vue-form-core';
import BackofficeSelectField from '@/Components/Backoffice/Shared/BackofficeSelectField.vue';
import { SaveCountdownDataRules } from '@/generated/form-rules';
import type { BackofficeOptions, CountdownDetail } from '@/Components/Backoffice/Doomsday/types';
import type { SaveCountdownData } from '@/types/generated';

type LocalizedTextForm = Record<string, string>;
type LocalizedListForm = Record<string, string[]>;
type LocalizedListField = 'causes' | 'consequences' | 'recommended_actions';
type SaveCountdownForm = Omit<SaveCountdownData, 'title' | 'summary' | 'description' | 'causes' | 'consequences' | 'recommended_actions'> & {
    title: LocalizedTextForm;
    summary: LocalizedTextForm;
    description: LocalizedTextForm;
    causes: LocalizedListForm;
    consequences: LocalizedListForm;
    recommended_actions: LocalizedListForm;
} & Record<string, unknown>;

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
const activeLocale = ref('en');
const listTexts = ref<Record<LocalizedListField, Record<string, string>>>({
    causes: {},
    consequences: {},
    recommended_actions: {},
});

const localizedFields = computed(() => {
    const configured = props.options.localized_fields.filter((locale) => locale.trim() !== '');
    const unique = Array.from(new Set(['en', ...configured]));

    return unique.length > 0 ? unique : ['en'];
});

const localeTabs = computed(() => localizedFields.value.map((locale) => ({
    value: locale,
    label: locale.toUpperCase(),
})));

function isRecord(value: unknown): value is Record<string, unknown> {
    return typeof value === 'object' && value !== null && !Array.isArray(value);
}

function localizedText(source?: Record<string, string> | null): LocalizedTextForm {
    return localizedFields.value.reduce<LocalizedTextForm>((values, locale) => {
        values[locale] = typeof source?.[locale] === 'string' ? source[locale] : '';
        return values;
    }, {});
}

function localizedList(source?: Record<string, readonly string[]> | null): LocalizedListForm {
    return localizedFields.value.reduce<LocalizedListForm>((values, locale) => {
        const localizedValue = source?.[locale];
        values[locale] = Array.isArray(localizedValue) ? localizedValue.map(String) : [];
        return values;
    }, {});
}

function completeLocalizedText(value: unknown): LocalizedTextForm {
    const source = isRecord(value) ? value : {};

    return localizedFields.value.reduce<LocalizedTextForm>((values, locale) => {
        const localizedValue = source[locale];
        values[locale] = typeof localizedValue === 'string' ? localizedValue : '';
        return values;
    }, {});
}

function completeLocalizedList(value: unknown): LocalizedListForm {
    const source = isRecord(value) ? value : {};

    return localizedFields.value.reduce<LocalizedListForm>((values, locale) => {
        const localizedValue = source[locale];
        values[locale] = Array.isArray(localizedValue) ? localizedValue.map(String) : [];
        return values;
    }, {});
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

function syncListTextsFromForm(): void {
    for (const field of Object.keys(listTexts.value) as LocalizedListField[]) {
        const localizedLists = form[field] as LocalizedListForm;

        listTexts.value[field] = localizedFields.value.reduce<Record<string, string>>((values, locale) => {
            values[locale] = (localizedLists[locale] ?? []).join('\n');
            return values;
        }, {});
    }
}

function ensureLocalizedRecords(): void {
    form.title = completeLocalizedText(form.title);
    form.summary = completeLocalizedText(form.summary);
    form.description = completeLocalizedText(form.description);
    form.causes = completeLocalizedList(form.causes);
    form.consequences = completeLocalizedList(form.consequences);
    form.recommended_actions = completeLocalizedList(form.recommended_actions);
    syncListTextsFromForm();
}

function fill(countdown?: CountdownDetail): void {
    form.fill({
        slug: countdown?.slug ?? '',
        title: localizedText(countdown?.title),
        summary: localizedText(countdown?.summary),
        description: localizedText(countdown?.description),
        causes: localizedList(countdown?.causes),
        consequences: localizedList(countdown?.consequences),
        recommended_actions: localizedList(countdown?.recommended_actions),
        severity: countdown?.severity ?? first(props.options.countdown_severities, 'moderate'),
        status: countdown?.status ?? first(props.options.countdown_statuses, 'draft'),
        target_date: isoDate(countdown?.target_date),
        image_path: countdown?.image_path ?? '/images/doomsday_hero_background_desktop.png',
        accent_color: countdown?.accent_color ?? '#ff2a23',
        sort_order: countdown?.sort_order ?? 0,
        is_published: countdown?.is_published ?? false,
    });

    syncListTextsFromForm();
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

function fieldPath(field: string, locale: string): string {
    return `${field}.${locale}`;
}

function fieldError(field: string): ErrorInput | undefined {
    const errors = form.errors as Record<string, ErrorInput | undefined>;

    return errors[fieldPath(field, activeLocale.value)] ?? errors[field];
}

function syncLists(): void {
    form.causes = localizedFields.value.reduce<LocalizedListForm>((values, locale) => {
        values[locale] = lines(listTexts.value.causes[locale] ?? '');
        return values;
    }, {});
    form.consequences = localizedFields.value.reduce<LocalizedListForm>((values, locale) => {
        values[locale] = lines(listTexts.value.consequences[locale] ?? '');
        return values;
    }, {});
    form.recommended_actions = localizedFields.value.reduce<LocalizedListForm>((values, locale) => {
        values[locale] = lines(listTexts.value.recommended_actions[locale] ?? '');
        return values;
    }, {});
}

function submit(): void {
    syncLists();
    const method = props.method === 'post' ? form.post : form.put;
    method(props.submitUrl, { preserveScroll: true, onSuccess: () => emit('saved') });
}

watch(localizedFields, (fields) => {
    if (!fields.includes(activeLocale.value)) {
        activeLocale.value = fields[0] ?? 'en';
    }

    ensureLocalizedRecords();
});

fill(props.countdown);
</script>

<template>
    <component :is="embedded ? 'div' : Card" :ui="embedded ? undefined : { body: 'space-y-6 p-6' }">
        <form class="space-y-6" @submit.prevent="submit">
            <div class="flex flex-col gap-4 border-b border-ui-border/60 pb-4 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-wide text-ui-muted-foreground">Main countdown</p>
                    <p class="mt-1 text-sm text-ui-muted-foreground">Edit global settings and localized public copy.</p>
                </div>
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-end">
                    <Toggle v-model="form.is_published" label="Published" on-label="Visible publicly" off-label="Draft" />
                    <Button type="submit" :loading="form.processing">{{ submitLabel }}</Button>
                </div>
            </div>

            <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                <TextInput v-model="form.slug" label="Slug" :error="form.errors.slug" @blur="form.validateField('slug')" />
                <BackofficeSelectField label="Severity" :model-value="form.severity" :options="options.countdown_severities.map((value) => ({ value, label: value }))" :clearable="false" @update:model-value="selectSeverity" />
                <BackofficeSelectField label="Status" :model-value="form.status" :options="options.countdown_statuses.map((value) => ({ value, label: value }))" :clearable="false" @update:model-value="selectStatus" />
                <TextInput v-model="form.target_date" label="Target date" type="date" :error="form.errors.target_date" />
            </div>

            <div class="grid gap-4 md:grid-cols-3">
                <TextInput v-model="form.image_path" label="Image path" :error="form.errors.image_path" />
                <TextInput v-model="form.accent_color" label="Accent color" :error="form.errors.accent_color" />
                <NumberInput v-model="form.sort_order" label="Sort order" :min="0" :error="form.errors.sort_order" />
            </div>

            <Tabs v-model="activeLocale" :items="localeTabs" variant="bordered" :ui="{ panels: 'pt-5' }">
                <TabPanel v-for="locale in localizedFields" :key="locale" :value="locale">
                    <div class="space-y-5">
                        <div class="grid gap-4 md:grid-cols-2">
                            <TextInput v-model="form.title[locale]" :label="`Title (${locale.toUpperCase()})`" :error="fieldError('title')" @blur="form.validateField(fieldPath('title', locale))" />
                            <Textarea v-model="form.summary[locale]" :label="`Summary (${locale.toUpperCase()})`" :error="fieldError('summary')" :rows="3" @blur="form.validateField(fieldPath('summary', locale))" />
                        </div>

                        <Textarea v-model="form.description[locale]" :label="`Description (${locale.toUpperCase()})`" :error="fieldError('description')" :rows="4" />

                        <div class="grid gap-4 md:grid-cols-3">
                            <Textarea v-model="listTexts.causes[locale]" :label="`Causes (${locale.toUpperCase()}, one per line)`" :error="fieldError('causes')" :rows="5" />
                            <Textarea v-model="listTexts.consequences[locale]" :label="`Consequences (${locale.toUpperCase()}, one per line)`" :error="fieldError('consequences')" :rows="5" />
                            <Textarea v-model="listTexts.recommended_actions[locale]" :label="`Recommended actions (${locale.toUpperCase()}, one per line)`" :error="fieldError('recommended_actions')" :rows="5" />
                        </div>
                    </div>
                </TabPanel>
            </Tabs>
        </form>
    </component>
</template>
