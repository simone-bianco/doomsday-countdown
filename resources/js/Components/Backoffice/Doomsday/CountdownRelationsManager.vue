<script setup lang="ts">
import { ref } from 'vue';
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

const activeTab = ref('main');
const tabs = [
    { value: 'main', label: 'Main', icon: Settings2 },
    { value: 'projections', label: 'Projections', icon: Activity },
    { value: 'visualizations', label: 'Visualizations', icon: BarChart3 },
    { value: 'news', label: 'News', icon: Newspaper },
    { value: 'initiatives', label: 'Initiatives', icon: Megaphone },
];
</script>

<template>
    <Card :ui="{ body: 'space-y-5 p-6' }">
        <Tabs v-model="activeTab" :items="tabs" variant="pills" :ui="{ panels: 'pt-5' }">
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
        </Tabs>
    </Card>
</template>
