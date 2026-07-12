<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { Button, Card, Checkbox, TextInput } from '@simone-bianco/vue-ui-components';
import { useSmartForm } from '@simone-bianco/vue-form-core';
import AppLayout from '@/Layouts/AppLayout.vue';
import { LoginDataRules } from '@/generated/form-rules';
import type { LoginData } from '@/types/generated';

type LoginForm = LoginData & { remember: boolean } & Record<string, unknown>;

defineProps<{
    readonly backofficePath: string;
}>();

const form = useSmartForm<LoginForm>({ ...LoginDataRules });
form.fill({ email: '', password: '', remember: false });

function submit(): void {
    form.post('/login');
}
</script>

<template>
    <Head title="Login" />
    <AppLayout title="Backoffice login">
        <Card max-width="32rem" :ui="{ root: 'mx-auto', body: 'p-6' }">
            <form class="space-y-4" @submit.prevent="submit">
                <TextInput v-model="form.email" label="Email" type="email" :error="form.errors.email" @blur="form.validateField('email')" />
                <TextInput v-model="form.password" label="Password" type="password" :error="form.errors.password" @blur="form.validateField('password')" />
                <Checkbox id="remember" v-model:checked="form.remember" label="Remember me" />
                <Button type="submit" :loading="form.processing">Login</Button>
            </form>
        </Card>
    </AppLayout>
</template>
