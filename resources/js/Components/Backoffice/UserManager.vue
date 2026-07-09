<script setup lang="ts">
import { computed, ref } from 'vue';
import { Edit3, Plus, Trash2 } from 'lucide-vue-next';
import { Button, DataTable, Modal, SearchBox, TextInput } from '@simone-bianco/vue-ui-components';
import { useSmartForm } from '@simone-bianco/vue-form-core';
import DeleteConfirmationModal from '@/Components/Backoffice/Shared/DeleteConfirmationModal.vue';
import FormActions from '@/Components/Backoffice/Shared/FormActions.vue';
import { backofficeInteractiveTableUi, backofficeTableSearchUi } from '@/Components/Backoffice/Shared/backofficeTableUi';
import { SaveUserDataRules } from '@/generated/form-rules';
import type { SaveUserData } from '@/types/generated';

type User = {
    readonly id: number;
    readonly name: string;
    readonly email: string;
};

type SaveUserForm = SaveUserData & Record<string, unknown>;
type EmptyForm = Record<string, never>;

const props = defineProps<{
    readonly backofficePath: string;
    readonly users: readonly User[];
}>();

const isFormOpen = ref(false);
const editingId = ref<number | null>(null);
const deletingId = ref<number | null>(null);
const pendingDelete = ref<User | null>(null);
const searchQuery = ref('');
const backofficeBasePath = computed(() => `/${props.backofficePath.replace(/^\/+|\/+$/g, '')}`);
const normalizedSearchQuery = computed(() => searchQuery.value.trim().toLowerCase());
const rows = computed<Record<string, unknown>[]>(() => props.users
    .filter((user) => matchesSearch(user, normalizedSearchQuery.value))
    .map((user) => ({ ...user })));
const columns = [
    { key: 'name', label: 'User', class: 'flex-[2]', headerClass: 'flex-[2]' },
    { key: 'email', label: 'Email', class: 'flex-[2]', headerClass: 'flex-[2]' },
    { key: 'actions', label: 'Actions', class: 'w-44 flex-none', headerClass: 'w-44 flex-none text-right' },
];
const form = useSmartForm<SaveUserForm>({ ...SaveUserDataRules });
const deleteForm = useSmartForm<EmptyForm>({});
const formTitle = computed(() => editingId.value === null ? 'Create user' : 'Edit user');
form.fill(defaults());

function defaults(): SaveUserData {
    return { name: '', email: '', password: null };
}

function userUrl(userId?: number): string {
    return userId === undefined
        ? `${backofficeBasePath.value}/users`
        : `${backofficeBasePath.value}/users/${userId}`;
}

function asUser(item: Record<string, unknown>): User {
    return item as unknown as User;
}

function matchesSearch(user: User, query: string): boolean {
    if (query === '') {
        return true;
    }

    return [user.id, user.name, user.email]
        .some((value) => String(value).toLowerCase().includes(query));
}

function openCreate(): void {
    editingId.value = null;
    form.fill(defaults());
    form.clearErrors();
    isFormOpen.value = true;
}

function edit(user: User): void {
    editingId.value = user.id;
    form.fill({ name: user.name, email: user.email, password: null });
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
    const url = editingId.value === null ? userUrl() : userUrl(editingId.value);
    const method = editingId.value === null ? form.post : form.put;
    method(url, { onSuccess: closeForm });
}

function destroy(user: User): void {
    pendingDelete.value = user;
}

function confirmDestroy(): void {
    const user = pendingDelete.value;
    if (user === null) {
        return;
    }

    deletingId.value = user.id;
    deleteForm.delete(userUrl(user.id), {
        onSuccess: () => {
            pendingDelete.value = null;
            if (editingId.value === user.id) {
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
    <div class="space-y-4">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <SearchBox v-model="searchQuery" placeholder="Search users" :ui="backofficeTableSearchUi" />
            <Button :icon="Plus" @click="openCreate">Create user</Button>
        </div>

        <DataTable :items="rows" :columns="columns" item-key="id" density="comfortable" enable-row-click :ui="backofficeInteractiveTableUi" @row-click="(item) => edit(asUser(item))">
            <template #cell-name="{ item }">
                <div class="font-medium">{{ item.name }}</div>
            </template>
            <template #cell-email="{ item }">
                <div class="text-sm text-ui-muted-foreground">{{ item.email }}</div>
            </template>
            <template #cell-actions="{ item }">
                <div class="flex items-center justify-end gap-2" data-no-row-click>
                    <Button variant="secondary" size="sm" :icon="Edit3" @click="edit(asUser(item))">Edit</Button>
                    <Button variant="danger" size="sm" :icon="Trash2" :loading="deleteForm.processing && deletingId === item.id" :disabled="deleteForm.processing" @click="destroy(asUser(item))">Delete</Button>
                </div>
            </template>
            <template #empty>
                <p class="text-sm text-ui-muted-foreground">No users match this search.</p>
            </template>
        </DataTable>
    </div>

    <Modal :show="isFormOpen" :title="formTitle" max-width="xl" @close="closeForm">
        <form class="space-y-4" @submit.prevent="save">
            <div class="grid gap-4 md:grid-cols-2">
                <TextInput v-model="form.name" label="Name" :error="form.errors.name" @blur="form.validateField('name')" />
                <TextInput v-model="form.email" label="Email" type="email" :error="form.errors.email" @blur="form.validateField('email')" />
            </div>
            <TextInput v-model="form.password" label="Password" type="password" :error="form.errors.password" helper-text="Required only for new users or password changes." />

            <FormActions compact>
                <Button type="submit" :loading="form.processing">{{ editingId === null ? 'Create user' : 'Save user' }}</Button>
                <Button variant="secondary" @click="closeForm">Cancel</Button>
            </FormActions>
        </form>
    </Modal>

    <DeleteConfirmationModal
        :show="pendingDelete !== null"
        title="Delete user"
        :description="`Delete user ${pendingDelete?.email ?? ''}? This action cannot be undone.`"
        :loading="deleteForm.processing"
        @close="pendingDelete = null"
        @confirm="confirmDestroy"
    />
</template>
