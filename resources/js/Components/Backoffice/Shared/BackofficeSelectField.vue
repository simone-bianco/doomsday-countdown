<script setup lang="ts">
import { InputErrors, InputLabel, Select } from '@simone-bianco/vue-ui-components';
import type { ErrorInput, SelectOption } from '@simone-bianco/vue-ui-components';

const model = defineModel<string | number | null>({ required: false, default: null });

withDefaults(defineProps<{
    readonly label: string;
    readonly options: SelectOption[];
    readonly clearable?: boolean;
    readonly disabled?: boolean;
    readonly placeholder?: string;
    readonly error?: ErrorInput;
}>(), {
    clearable: false,
    disabled: false,
    placeholder: 'Select an option...',
    error: undefined,
});
</script>

<template>
    <div class="w-full min-h-[76px]">
        <InputLabel :value="label" />
        <Select
            v-model="model"
            :options="options"
            :clearable="clearable"
            :disabled="disabled"
            :placeholder="placeholder"
            :ui="{ trigger: 'min-h-[42px]' }"
        />
        <div class="mt-1 min-h-[20px]">
            <InputErrors :error="error" />
        </div>
    </div>
</template>
