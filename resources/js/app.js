import '@simone-bianco/vue-ui-components/style.css';
import '../css/app.css';
import './bootstrap';

import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createSSRApp, h } from 'vue';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import { ThemeProvider } from '@simone-bianco/vue-ui-components';
import AppNavigationLoader from './Components/App/AppNavigationLoader.vue';
import { assertSupportedLocale, initializeClientI18n } from './i18n';
import { initializeValidationLocale } from './i18n/validation';

/** @typedef {import('./types/page-props').DoomsdayPageProps} DoomsdayPageProps */

const appName = import.meta.env.VITE_APP_NAME || 'Doomsday Clock';
const resolvePage = (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue'));

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: resolvePage,
    async setup({ el, App, props, plugin }) {
        const locale = assertSupportedLocale(/** @type {DoomsdayPageProps} */ (props.initialPage.props).locale);
        await initializeClientI18n(locale);
        initializeValidationLocale(locale);

        const app = createSSRApp({
            render: () => h(ThemeProvider, { defaultTheme: 'doomsday' }, () => [
                h(App, props),
                h(AppNavigationLoader),
            ]),
        });

        return app.use(ZiggyVue).use(plugin).mount(el);
    },
    progress: false,
});
