<script setup lang="ts">
import { computed, ref } from 'vue';
import { Button, Card, TextInput } from '@simone-bianco/vue-ui-components';
import { useSmartForm } from '@simone-bianco/vue-form-core';
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

const editingId = ref<number | null>(null);
const deletingId = ref<number | null>(null);
const backofficeBasePath = computed(() => `/${props.backofficePath.replace(/^\/+|\/+$/g, '')}`);
const form = useSmartForm<SaveUserForm>({ ...SaveUserDataRules });
const deleteForm = useSmartForm<EmptyForm>({});
form.fill({ name: '', email: '', password: null });

function userUrl(userId?: number): string {
    return userId === undefined
        ? `${backofficeBasePath.value}/users`
        : `${backofficeBasePath.value}/users/${userId}`;
}

function edit(user: User): void {
    editingId.value = user.id;
    form.fill({ name: user.name, email: user.email, password: null });
}

function reset(): void {
    editingId.value = null;
    form.fill({ name: '', email: '', password: null });
    form.clearErrors();
}

function save(): void {
    const url = editingId.value === null ? userUrl() : userUrl(editingId.value);
    const method = editingId.value === null ? form.post : form.put;
    method(url, { onSuccess: reset });
}

function destroy(user: User): void {
    if (!window.confirm(`Delete user ${user.email}?`)) {
        return;
    }

    deletingId.value = user.id;
    deleteForm.delete(userUrl(user.id), {
        onSuccess: () => {
            if (editingId.value === user.id) {
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
        <template #header>Users</template>

        <form class="grid gap-4 md:grid-cols-4" @submit.prevent="save">
            <TextInput v-model="form.name" label="Name" :error="form.errors.name" @blur="form.validateField('name')" />
            <TextInput v-model="form.email" label="Email" type="email" :error="form.errors.email" @blur="form.validateField('email')" />
            <TextInput v-model="form.password" label="Password" type="password" :error="form.errors.password" helper-text="Required only for new users or password changes." />
            <div class="flex items-end gap-2">
                <Button type="submit" :loading="form.processing">{{ editingId === null ? 'Create' : 'Save' }}</Button>
                <Button v-if="editingId !== null" variant="secondary" @click="reset">Cancel</Button>
            </div>
        </form>

        <div class="divide-y divide-ui-border rounded-lg border border-ui-border">
            <div v-for="user in users" :key="user.id" class="flex items-center justify-between gap-4 p-4">
                <div>
                    <div class="font-medium">{{ user.name }}</div>
                    <div class="text-sm text-ui-muted-foreground">{{ user.email }}</div>
                </div>
                <div class="flex items-center gap-2">
                    <Button variant="secondary" size="sm" icon="edit" @click="edit(user)">Edit</Button>
                    <Button variant="danger" size="sm" icon="trash" :loading="deleteForm.processing && deletingId === user.id" :disabled="deleteForm.processing" @click="destroy(user)">Delete</Button>
                </div>
            </div>
        </div>
    </Card>
</template>
