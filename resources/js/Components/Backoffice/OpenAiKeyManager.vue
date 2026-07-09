<script setup lang="ts">
import { computed, ref } from 'vue';
import { Edit3, Plus, Trash2 } from 'lucide-vue-next';
import { Badge, Button, Card, DataTable, Modal, TextInput, Toggle } from '@simone-bianco/vue-ui-components';
import { useSmartForm } from '@simone-bianco/vue-form-core';
import BackofficeSelectField from '@/Components/Backoffice/Shared/BackofficeSelectField.vue';
import FormActions from '@/Components/Backoffice/Shared/FormActions.vue';
import DeleteConfirmationModal from '@/Components/Backoffice/Shared/DeleteConfirmationModal.vue';
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

const isFormOpen = ref(false);
const editingId = ref<number | null>(null);
const deletingId = ref<number | null>(null);
const pendingDelete = ref<ApiKey | null>(null);
const backofficeBasePath = computed(() => `/${props.backofficePath.replace(/^\/+|\/+$/g, '')}`);
const rows = computed<Record<string, unknown>[]>(() => props.apiKeys.map((apiKey) => ({ ...apiKey })));
const columns = [
    { key: 'label', label: 'Key', class: 'flex-[2]', headerClass: 'flex-[2]' },
    { key: 'status', label: 'Status', class: 'flex-1', headerClass: 'flex-1' },
    { key: 'usage', label: 'Usage', class: 'flex-[2]', headerClass: 'flex-[2]' },
    { key: 'actions', label: 'Actions', class: 'w-44 flex-none', headerClass: 'w-44 flex-none text-right' },
];
const baseLimitOptions = [
    { value: 'none', label: 'None' },
    { value: 'fixed', label: 'Fixed' },
    { value: 'unlimited', label: 'Unlimited' },
];
const freeLimitOptions = [
    { value: 'none', label: 'None' },
    { value: 'daily', label: 'Daily' },
    { value: 'monthly', label: 'Monthly' },
];
const form = useSmartForm<SaveOpenAiKeyForm>({ ...SaveOpenAiKeyDataRules });
const deleteForm = useSmartForm<EmptyForm>({});
const formTitle = computed(() => editingId.value === null ? 'Register OpenAI key' : 'Edit OpenAI key');
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

function asApiKey(item: Record<string, unknown>): ApiKey {
    return item as unknown as ApiKey;
}

function chooseBaseLimit(value: string | number | null): void {
    if (typeof value === 'string') {
        form.base_limit_type = value;
    }
}

function chooseFreeLimit(value: string | number | null): void {
    if (typeof value === 'string') {
        form.free_limit_type = value;
    }
}

function openCreate(): void {
    editingId.value = null;
    form.fill(defaults());
    form.clearErrors();
    isFormOpen.value = true;
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
    form.clearErrors();
    isFormOpen.value = true;
}

function closeForm(): void {
    isFormOpen.value = false;
    editingId.value = null;
    form.fill(defaults());
    form.clearErrors();
}

function save(): void {
    const url = editingId.value === null ? openAiKeyUrl() : openAiKeyUrl(editingId.value);
    const method = editingId.value === null ? form.post : form.put;
    method(url, { onSuccess: closeForm });
}

function destroy(apiKey: ApiKey): void {
    pendingDelete.value = apiKey;
}

function confirmDestroy(): void {
    const apiKey = pendingDelete.value;
    if (apiKey === null) {
        return;
    }

    deletingId.value = apiKey.id;
    deleteForm.delete(openAiKeyUrl(apiKey.id), {
        onSuccess: () => {
            pendingDelete.value = null;
            if (editingId.value === apiKey.id) {
                closeForm();
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
        <template #header>
            <div class="flex flex-wrap items-center justify-between gap-3">
                <span>OpenAI keys</span>
                <Button size="sm" :icon="Plus" @click="openCreate">Register key</Button>
            </div>
        </template>

        <DataTable :items="rows" :columns="columns" item-key="id" density="comfortable">
            <template #cell-label="{ item }">
                <div class="space-y-1">
                    <div class="font-medium">{{ item.label }}</div>
                    <div class="font-mono text-sm text-ui-muted-foreground">{{ item.masked_key }}</div>
                </div>
            </template>
            <template #cell-status="{ item }">
                <div class="flex flex-wrap gap-2">
                    <Badge :color="item.is_active ? 'success' : 'secondary'">{{ item.is_active ? 'active' : 'inactive' }}</Badge>
                    <Badge v-if="item.is_depleted" color="danger">depleted</Badge>
                </div>
            </template>
            <template #cell-usage="{ item }">
                <div class="text-sm text-ui-muted-foreground">
                    paid {{ item.current_base_usage }} / {{ item.max_base_usage ?? '∞' }} · free {{ item.current_free_usage }} / {{ item.max_free_usage ?? '∞' }}
                </div>
            </template>
            <template #cell-actions="{ item }">
                <div class="flex items-center justify-end gap-2">
                    <Button variant="secondary" size="sm" :icon="Edit3" @click="edit(asApiKey(item))">Edit</Button>
                    <Button variant="danger" size="sm" :icon="Trash2" :loading="deleteForm.processing && deletingId === item.id" :disabled="deleteForm.processing" @click="destroy(asApiKey(item))">Delete</Button>
                </div>
            </template>
        </DataTable>

        <Modal :show="isFormOpen" :title="formTitle" max-width="2xl" @close="closeForm">
            <form class="space-y-4" @submit.prevent="save">
                <div class="grid gap-4 md:grid-cols-2">
                    <TextInput v-model="form.label" label="Label" :error="form.errors.label" />
                    <TextInput v-model="form.key" label="OpenAI key" type="password" :error="form.errors.key" helper-text="Leave empty while editing to keep the stored key." />
                </div>

                <div class="grid gap-4 md:grid-cols-2">
                    <BackofficeSelectField label="Paid pool" :model-value="form.base_limit_type" :options="baseLimitOptions" :clearable="false" @update:model-value="chooseBaseLimit" />
                    <TextInput v-model="form.max_base_usage" label="Paid token limit" type="number" :error="form.errors.max_base_usage" />
                    <BackofficeSelectField label="Free pool" :model-value="form.free_limit_type" :options="freeLimitOptions" :clearable="false" @update:model-value="chooseFreeLimit" />
                    <TextInput v-model="form.max_free_usage" label="Free token limit" type="number" :error="form.errors.max_free_usage" />
                </div>

                <FormActions compact>
                    <Button type="submit" :loading="form.processing">{{ editingId === null ? 'Register key' : 'Save key' }}</Button>
                    <Button variant="secondary" @click="closeForm">Cancel</Button>
                    <template #aside>
                        <Toggle v-model="form.is_active" label="Active" on-label="Enabled" off-label="Disabled" />
                    </template>
                </FormActions>
            </form>
        </Modal>

        <DeleteConfirmationModal
            :show="pendingDelete !== null"
            title="Delete OpenAI key"
            :description="`Delete OpenAI key ${pendingDelete?.label ?? ''}? This action cannot be undone.`"
            :loading="deleteForm.processing"
            @close="pendingDelete = null"
            @confirm="confirmDestroy"
        />
    </Card>
</template>
