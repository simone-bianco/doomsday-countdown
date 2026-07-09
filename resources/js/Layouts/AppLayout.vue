<script setup lang="ts">
import { Link, router, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import { Button, Card, ToastNotification } from '@simone-bianco/vue-ui-components';
import { DebugOverlay } from '@simone-bianco/vue-form-core';
import LanguageSwitcher from '@/Components/LanguageSwitcher.vue';

const props = withDefaults(defineProps<{
    readonly title: string;
    readonly showTitleCard?: boolean;
    readonly showLanguageSwitcher?: boolean;
    readonly contentClass?: string;
    readonly headerInnerClass?: string;
    readonly appNameOverride?: string;
    readonly logoHref?: string;
}>(), {
    showTitleCard: true,
    showLanguageSwitcher: true,
    contentClass: 'mx-auto max-w-6xl px-4 py-8',
    headerInnerClass: 'mx-auto flex max-w-6xl items-center justify-between px-4 py-4',
    appNameOverride: undefined,
    logoHref: '/',
});

type SharedProps = {
    readonly auth?: { readonly user?: { readonly name?: string; readonly email?: string } | null };
    readonly app?: { readonly name?: string; readonly backoffice_path?: string };
};

const page = usePage<SharedProps>();
const user = computed(() => page.props.auth?.user ?? null);
const appName = computed(() => props.appNameOverride ?? page.props.app?.name ?? 'Doomsday Countdown');
const backofficePath = computed(() => page.props.app?.backoffice_path ?? '');

function logout(): void {
    router.post('/logout');
}
</script>

<template>
    <div class="min-h-screen bg-ui-background text-ui-foreground">
        <ToastNotification />
        <DebugOverlay />
        <header class="border-b border-ui-border bg-ui-card/80 backdrop-blur">
            <div :class="headerInnerClass">
                <Link :href="logoHref" class="inline-flex items-center">
                    <img src="/images/doomsday/doomsday_logo_transparent.png" :alt="appName" class="h-8 w-auto object-contain" />
                </Link>
                <div class="flex items-center gap-3">
                    <LanguageSwitcher v-if="showLanguageSwitcher" />
                    <Link v-if="user" :href="backofficePath" class="text-sm text-ui-muted-foreground hover:text-ui-foreground">
                        {{ user.name }}
                    </Link>
                    <Link v-else href="/login" class="text-sm text-ui-muted-foreground hover:text-ui-foreground">Login</Link>
                    <Button v-if="user" variant="ghost" size="sm" @click="logout">Logout</Button>
                </div>
            </div>
        </header>

        <main :class="contentClass">
            <Card v-if="showTitleCard" :ui="{ root: 'mb-6 bg-ui-card/60', body: 'p-6' }">
                <h1 class="text-2xl font-bold">{{ title }}</h1>
            </Card>
            <slot />
        </main>
    </div>
</template>
