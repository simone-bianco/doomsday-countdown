import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import { formBridgePlugin } from './packages/simone-bianco/vue-form-core/src/vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: 'resources/js/app.js',
            ssr: 'resources/js/ssr.js',
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
        formBridgePlugin({
            watch: ['app/Data/**/*.php'],
            debounce: 500,
        }),
    ],
    server: {
        host: '0.0.0.0',
        port: 5173,
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
    resolve: {
        alias: {
            '@': '/resources/js',
            '@simone-bianco/vue-ui-components': '/packages/simone-bianco/vue-ui-components/src',
            '@simone-bianco/vue-form-core': '/packages/simone-bianco/vue-form-core/src',
        },
    },
});
