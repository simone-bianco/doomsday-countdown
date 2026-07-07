<script setup lang="ts">
import { Button } from '@simone-bianco/vue-ui-components';
import { currentLanguage, setLanguage } from '@/i18n';

async function toggleLanguage(): Promise<void> {
    await setLanguage(currentLanguage.value === 'it' ? 'en' : 'it');
}
</script>

<template>
    <Button variant="secondary" size="sm" @click="toggleLanguage">
        {{ currentLanguage.toUpperCase() }}
    </Button>
</template>
