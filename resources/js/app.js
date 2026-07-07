import '@simone-bianco/vue-ui-components/style.css';
import '../css/app.css';
import './bootstrap';
import './i18n';

import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createApp, h } from 'vue';
import { ThemeProvider } from '@simone-bianco/vue-ui-components';
import { loadValidationMessages, setValidationLocale } from '@simone-bianco/vue-form-core';
import { i18n } from './i18n';

void import('./generated/validation-messages').then(({ validationMessages }) => {
    loadValidationMessages(validationMessages);
    setValidationLocale(i18n.language);
});

const appName = import.meta.env.VITE_APP_NAME || 'Doomsday Countdown';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        const app = createApp({
            render: () => h(ThemeProvider, { defaultTheme: 'doomsday' }, () => [
                h(App, props),
            ]),
        });

        return app.use(plugin).mount(el);
    },
    progress: false,
});
