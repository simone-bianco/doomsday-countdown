<script setup lang="ts">
import { Link, router, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import { Button, Card, ToastNotification } from '@simone-bianco/vue-ui-components';
import { DebugOverlay } from '@simone-bianco/vue-form-core';
import LanguageSwitcher from '@/Components/LanguageSwitcher.vue';

defineProps<{
    readonly title: string;
}>();

type SharedProps = {
    readonly auth?: { readonly user?: { readonly name?: string; readonly email?: string } | null };
    readonly app?: { readonly name?: string; readonly backoffice_path?: string };
};

const page = usePage<SharedProps>();
const user = computed(() => page.props.auth?.user ?? null);
const appName = computed(() => page.props.app?.name ?? 'Doomsday Countdown');
const backofficePath = computed(() => page.props.app?.backoffice_path ?? '/backoffice');

function logout(): void {
    router.post('/logout');
}
</script>

<template>
    <div class="min-h-screen bg-ui-background text-ui-foreground">
        <ToastNotification />
        <DebugOverlay />
        <header class="border-b border-ui-border bg-ui-card/80 backdrop-blur">
            <div class="mx-auto flex max-w-6xl items-center justify-between px-4 py-4">
                <Link href="/" class="text-lg font-bold tracking-tight">{{ appName }}</Link>
                <div class="flex items-center gap-3">
                    <LanguageSwitcher />
                    <Link v-if="user" :href="backofficePath" class="text-sm text-ui-muted-foreground hover:text-ui-foreground">
                        {{ user.name }}
                    </Link>
                    <Link v-else href="/login" class="text-sm text-ui-muted-foreground hover:text-ui-foreground">Login</Link>
                    <Button v-if="user" variant="ghost" size="sm" @click="logout">Logout</Button>
                </div>
            </div>
        </header>

        <main class="mx-auto max-w-6xl px-4 py-8">
            <Card :ui="{ root: 'mb-6 bg-ui-card/60', body: 'p-6' }">
                <h1 class="text-2xl font-bold">{{ title }}</h1>
            </Card>
            <slot />
        </main>
    </div>
</template>
