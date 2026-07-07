<script setup lang="ts">
import { computed, ref } from 'vue';
import ky, { HTTPError } from 'ky';
import { Button, Card, Textarea } from '@simone-bianco/vue-ui-components';
import { useSmartForm } from '@simone-bianco/vue-form-core';
import { AgentPromptDataRules } from '@/generated/form-rules';
import { t } from '@/i18n';
import type { AgentPromptData } from '@/types/generated';

type AgentPromptForm = AgentPromptData & Record<string, unknown>;

type AgentResult = {
    readonly ok: boolean;
    readonly status?: number;
    readonly elapsed_ms?: number;
    readonly error?: string;
    readonly payload?: Record<string, unknown>;
    readonly response?: unknown;
};

const props = defineProps<{
    readonly endpoint: string;
}>();

const result = ref<AgentResult | null>(null);
const processing = ref(false);
const form = useSmartForm<AgentPromptForm>({ ...AgentPromptDataRules });
form.fill({ message: 'Say hello from the starter demo agent and include the selected model name.' });

const formattedResult = computed(() => JSON.stringify(result.value, null, 2));

function cookieValue(name: string): string | null {
    const cookie = document.cookie
        .split('; ')
        .find((item: string): boolean => item.startsWith(`${name}=`));

    if (cookie === undefined) {
        return null;
    }

    const value = cookie.substring(name.length + 1);

    try {
        return decodeURIComponent(value);
    } catch (error: unknown) {
        console.warn('Unable to decode CSRF cookie value.', error);
        return value;
    }
}

function metaCsrfToken(): string | null {
    return document.querySelector<HTMLMetaElement>('meta[name="csrf-token"]')?.content ?? null;
}

function csrfHeaders(): Record<string, string> {
    const headers: Record<string, string> = {
        Accept: 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
    };
    const xsrfToken = cookieValue('XSRF-TOKEN');

    if (xsrfToken !== null && xsrfToken !== '') {
        headers['X-XSRF-TOKEN'] = xsrfToken;
        return headers;
    }

    const token = metaCsrfToken();
    if (token !== null && token !== '') {
        headers['X-CSRF-TOKEN'] = token;
    }

    return headers;
}

async function httpErrorResult(error: HTTPError, payload: AgentPromptForm): Promise<AgentResult> {
    const { response } = error;
    const status = response.status;
    const contentType = response.headers.get('content-type') ?? '';

    if (contentType.includes('application/json') || contentType.includes('+json')) {
        try {
            const body = await response.json<Partial<AgentResult>>();

            return {
                ...body,
                ok: false,
                status: body.status ?? status,
                error: body.error ?? `Request failed with HTTP ${status}.`,
                payload: body.payload ?? payload,
            };
        } catch (parseError: unknown) {
            return {
                ok: false,
                status,
                error: `Request failed with HTTP ${status}, but the JSON error response could not be parsed: ${String(parseError)}`,
                payload,
            };
        }
    }

    const text = await response.text();
    const preview = text.trim().replace(/\s+/g, ' ').slice(0, 160);
    const message = status === 419
        ? 'Session expired or CSRF token mismatch. Refresh the page and try again.'
        : `Request failed with HTTP ${status}.`;

    return {
        ok: false,
        status,
        error: preview === '' ? message : `${message} Response was not JSON: ${preview}`,
        payload,
    };
}

async function callAgent(): Promise<void> {
    if (!form.validate()) {
        return;
    }

    processing.value = true;
    result.value = null;

    const payload: AgentPromptForm = { message: form.message };

    try {
        result.value = await ky.post(props.endpoint, {
            json: payload,
            headers: csrfHeaders(),
            timeout: 70000,
        }).json<AgentResult>();
    } catch (error: unknown) {
        if (error instanceof HTTPError) {
            result.value = await httpErrorResult(error, payload);
            return;
        }

        result.value = {
            ok: false,
            error: error instanceof Error ? error.message : String(error),
            payload,
        };
    } finally {
        processing.value = false;
    }
}
</script>

<template>
    <Card :ui="{ body: 'space-y-4 p-6' }">
        <template #header>
            <div class="flex items-center justify-between gap-3">
                <span>Agent debug</span>
                <span class="text-xs text-ui-muted-foreground">POST {{ endpoint }}</span>
            </div>
        </template>

        <Textarea v-model="form.message" :label="t('message')" :error="form.errors.message" @blur="form.validateField('message')" />
        <Button icon="sparkles" :loading="processing" :disabled="form.message === ''" @click="callAgent">
            {{ t('callAgent') }}
        </Button>

        <div v-if="result" class="rounded-lg border border-ui-border bg-ui-background p-4">
            <div class="mb-2 flex items-center justify-between text-sm">
                <span :class="result.ok ? 'text-emerald-400' : 'text-ui-error'">
                    {{ result.ok ? 'OK' : 'ERROR' }}
                </span>
                <span class="text-ui-muted-foreground">{{ result.elapsed_ms ?? 0 }} ms</span>
            </div>
            <pre class="max-h-[32rem] overflow-auto whitespace-pre-wrap text-xs leading-relaxed text-ui-muted-foreground">{{ formattedResult }}</pre>
        </div>
    </Card>
</template>
