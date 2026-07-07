<script setup lang="ts">
import { computed, ref } from 'vue';
import { Badge, Button, Card, TextInput } from '@simone-bianco/vue-ui-components';
import { useSmartForm } from '@simone-bianco/vue-form-core';
import { SaveOpenAiKeyDataRules } from '@/generated/form-rules';
import type { SaveOpenAiKeyData } from '@/types/generated';

type ApiKey = {
    readonly id: number;
    readonly label: string;
    readonly masked_key: string;
    readonly base_limit_type: string;
    readonly max_base_usage: number | null;
    readonly current_base_usage: number;
    readonly free_limit_type: string;
    readonly max_free_usage: number | null;
    readonly current_free_usage: number;
    readonly is_active: boolean;
    readonly is_depleted: boolean;
    readonly last_used_at: string | null;
};

type SaveOpenAiKeyForm = SaveOpenAiKeyData & Record<string, unknown>;
type EmptyForm = Record<string, never>;

const props = defineProps<{
    readonly backofficePath: string;
    readonly apiKeys: readonly ApiKey[];
}>();

const editingId = ref<number | null>(null);
const deletingId = ref<number | null>(null);
const backofficeBasePath = computed(() => `/${props.backofficePath.replace(/^\/+|\/+$/g, '')}`);
const form = useSmartForm<SaveOpenAiKeyForm>({ ...SaveOpenAiKeyDataRules });
const deleteForm = useSmartForm<EmptyForm>({});
form.fill(defaults());

function defaults(): SaveOpenAiKeyData {
    return {
        label: '',
        key: '',
        base_limit_type: 'unlimited',
        max_base_usage: null,
        free_limit_type: 'none',
        max_free_usage: null,
        is_active: true,
    };
}

function openAiKeyUrl(apiKeyId?: number): string {
    return apiKeyId === undefined
        ? `${backofficeBasePath.value}/openai-keys`
        : `${backofficeBasePath.value}/openai-keys/${apiKeyId}`;
}

function edit(apiKey: ApiKey): void {
    editingId.value = apiKey.id;
    form.fill({
        label: apiKey.label,
        key: '',
        base_limit_type: apiKey.base_limit_type,
        max_base_usage: apiKey.max_base_usage,
        free_limit_type: apiKey.free_limit_type,
        max_free_usage: apiKey.max_free_usage,
        is_active: apiKey.is_active,
    });
}

function reset(): void {
    editingId.value = null;
    form.fill(defaults());
    form.clearErrors();
}

function chooseBaseLimit(value: string): void {
    form.base_limit_type = value;
}

function chooseFreeLimit(value: string): void {
    form.free_limit_type = value;
}

function toggleActive(): void {
    form.is_active = !form.is_active;
}

function save(): void {
    const url = editingId.value === null ? openAiKeyUrl() : openAiKeyUrl(editingId.value);
    const method = editingId.value === null ? form.post : form.put;
    method(url, { onSuccess: reset });
}

function destroy(apiKey: ApiKey): void {
    if (!window.confirm(`Delete OpenAI key ${apiKey.label}?`)) {
        return;
    }

    deletingId.value = apiKey.id;
    deleteForm.delete(openAiKeyUrl(apiKey.id), {
        onSuccess: () => {
            if (editingId.value === apiKey.id) {
                reset();
            }
        },
        onFinish: () => {
            deletingId.value = null;
        },
    });
}
</script>

<template>
    <Card :ui="{ body: 'space-y-5 p-6' }">
        <template #header>OpenAI keys</template>

        <form class="grid gap-4 md:grid-cols-3" @submit.prevent="save">
            <TextInput v-model="form.label" label="Label" :error="form.errors.label" />
            <TextInput v-model="form.key" label="OpenAI key" type="password" :error="form.errors.key" helper-text="Leave empty while editing to keep the stored key." />
            <TextInput v-model="form.max_base_usage" label="Paid token limit" type="number" :error="form.errors.max_base_usage" />

            <div class="space-y-2">
                <div class="text-sm font-medium">Paid pool</div>
                <div class="flex gap-2">
                    <Button v-for="value in ['none', 'fixed', 'unlimited']" :key="value" size="sm" :variant="form.base_limit_type === value ? 'primary' : 'secondary'" @click="chooseBaseLimit(value)">
                        {{ value }}
                    </Button>
                </div>
            </div>

            <div class="space-y-2">
                <div class="text-sm font-medium">Free pool</div>
                <div class="flex gap-2">
                    <Button v-for="value in ['none', 'daily', 'monthly']" :key="value" size="sm" :variant="form.free_limit_type === value ? 'primary' : 'secondary'" @click="chooseFreeLimit(value)">
                        {{ value }}
                    </Button>
                </div>
            </div>

            <TextInput v-model="form.max_free_usage" label="Free token limit" type="number" :error="form.errors.max_free_usage" />

            <div class="flex items-end gap-2 md:col-span-3">
                <Button type="submit" :loading="form.processing">{{ editingId === null ? 'Register key' : 'Save key' }}</Button>
                <Button type="button" :variant="form.is_active ? 'primary' : 'secondary'" @click="toggleActive">
                    {{ form.is_active ? 'Active' : 'Inactive' }}
                </Button>
                <Button v-if="editingId !== null" variant="secondary" @click="reset">Cancel</Button>
            </div>
        </form>

        <div class="grid gap-3">
            <div v-for="apiKey in apiKeys" :key="apiKey.id" class="rounded-lg border border-ui-border p-4">
                <div class="flex items-start justify-between gap-4">
                    <div class="space-y-1">
                        <div class="flex items-center gap-2">
                            <span class="font-medium">{{ apiKey.label }}</span>
                            <Badge :color="apiKey.is_active ? 'success' : 'secondary'">{{ apiKey.is_active ? 'active' : 'inactive' }}</Badge>
                            <Badge v-if="apiKey.is_depleted" color="danger">depleted</Badge>
                        </div>
                        <div class="font-mono text-sm text-ui-muted-foreground">{{ apiKey.masked_key }}</div>
                        <div class="text-xs text-ui-muted-foreground">
                            paid {{ apiKey.current_base_usage }} / {{ apiKey.max_base_usage ?? '∞' }} · free {{ apiKey.current_free_usage }} / {{ apiKey.max_free_usage ?? '∞' }}
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <Button variant="secondary" size="sm" icon="edit" @click="edit(apiKey)">Edit</Button>
                        <Button variant="danger" size="sm" icon="trash" :loading="deleteForm.processing && deletingId === apiKey.id" :disabled="deleteForm.processing" @click="destroy(apiKey)">Delete</Button>
                    </div>
                </div>
            </div>
        </div>
    </Card>
</template>
