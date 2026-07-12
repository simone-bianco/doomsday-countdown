import { createSSRApp, h } from 'vue';
import { renderToString } from '@vue/server-renderer';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ThemeProvider } from '@simone-bianco/vue-ui-components';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import { Ziggy } from './generated/ziggy';
import { withServerI18n } from './i18n/server';

/** @typedef {import('./types/page-props').DoomsdayPageProps} DoomsdayPageProps */

const appName = import.meta.env.VITE_APP_NAME || 'Doomsday Clock';
const resolvePage = (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue'));

function ziggyConfigForPage(page) {
    const location = new URL(page.url, Ziggy.url);

    return {
        ...Ziggy,
        url: location.origin,
        port: location.port === '' ? null : location.port,
        location,
    };
}

export default function render(page) {
    return withServerI18n(/** @type {DoomsdayPageProps} */ (page.props).locale, () => createInertiaApp({
        page,
        render: renderToString,
        title: (title) => `${title} - ${appName}`,
        resolve: resolvePage,
        setup({ App, props, plugin }) {
            const app = createSSRApp({
                render: () => h(ThemeProvider, { defaultTheme: 'doomsday' }, () => h(App, props)),
            });

            return app.use(ZiggyVue, ziggyConfigForPage(page)).use(plugin);
        },
    }));
}
