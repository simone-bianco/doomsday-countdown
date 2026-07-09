<script setup lang="ts">
import { ref, watch } from 'vue';
import { Activity, BarChart3, Megaphone, Newspaper, Settings2 } from 'lucide-vue-next';
import { Card, TabPanel, Tabs } from '@simone-bianco/vue-ui-components';
import CountdownForm from '@/Components/Backoffice/Doomsday/CountdownForm.vue';
import InitiativeManager from '@/Components/Backoffice/Doomsday/InitiativeManager.vue';
import NewsManager from '@/Components/Backoffice/Doomsday/NewsManager.vue';
import ProjectionManager from '@/Components/Backoffice/Doomsday/ProjectionManager.vue';
import VisualizationManager from '@/Components/Backoffice/Doomsday/VisualizationManager.vue';
import type { BackofficeOptions, CountdownDetail } from '@/Components/Backoffice/Doomsday/types';

const props = defineProps<{
    readonly basePath: string;
    readonly countdown: CountdownDetail;
    readonly options: BackofficeOptions;
    readonly submitUrl: string;
    readonly method: 'post' | 'put';
    readonly submitLabel: string;
}>();

const tabs = [
    { value: 'main', label: 'Main', icon: Settings2 },
    { value: 'projections', label: 'Projections', icon: Activity },
    { value: 'visualizations', label: 'Visualizations', icon: BarChart3 },
    { value: 'news', label: 'News', icon: Newspaper },
    { value: 'initiatives', label: 'Initiatives', icon: Megaphone },
];

function initialTab(): string {
    if (typeof window === 'undefined') {
        return 'main';
    }

    const queryTab = new URLSearchParams(window.location.search).get('tab');

    return queryTab !== null && tabs.some((tab) => tab.value === queryTab) ? queryTab : 'main';
}

function replaceTabQuery(tab: string): void {
    if (typeof window === 'undefined') {
        return;
    }

    const url = new URL(window.location.href);
    url.searchParams.set('tab', tab);
    window.history.replaceState(window.history.state, '', `${url.pathname}${url.search}${url.hash}`);
}

const activeTab = ref(initialTab());

watch(activeTab, (tab) => replaceTabQuery(tab));
</script>

<template>
    <Tabs v-model="activeTab" :items="tabs" variant="bordered" :ui="{ panels: 'pt-5' }">
        <Card :ui="{ body: 'space-y-5 p-6' }">
            <TabPanel value="main">
                <CountdownForm
                    :options="options"
                    :countdown="countdown"
                    :submit-url="submitUrl"
                    :method="method"
                    :submit-label="submitLabel"
                    embedded
                />
            </TabPanel>
            <TabPanel value="projections">
                <ProjectionManager :base-path="basePath" :countdown-id="countdown.id" :projections="countdown.projections" :options="options" />
            </TabPanel>
            <TabPanel value="visualizations">
                <VisualizationManager :base-path="basePath" :countdown-id="countdown.id" :visualizations="countdown.visualizations" :options="options" title="Countdown visualizations" />
            </TabPanel>
            <TabPanel value="news">
                <NewsManager :base-path="basePath" :countdown-id="countdown.id" :news="countdown.news" :options="options" />
            </TabPanel>
            <TabPanel value="initiatives">
                <InitiativeManager :base-path="basePath" :countdown-id="countdown.id" :initiatives="countdown.initiatives" :options="options" />
            </TabPanel>
        </Card>
    </Tabs>
</template>
