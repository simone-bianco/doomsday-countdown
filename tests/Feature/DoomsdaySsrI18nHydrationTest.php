<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

final class DoomsdaySsrI18nHydrationTest extends TestCase
{
    #[Test]
    public function client_and_ssr_entries_keep_required_plugin_parity_without_browser_only_ssr_imports(): void
    {
        $client = $this->source('resources/js/app.js');
        $ssr = $this->source('resources/js/ssr.js');

        foreach (['createInertiaApp', 'createSSRApp', 'resolvePageComponent', 'ThemeProvider', 'ZiggyVue'] as $contract) {
            $this->assertStringContainsString($contract, $client);
            $this->assertStringContainsString($contract, $ssr);
        }

        $this->assertStringContainsString('initializeClientI18n', $client);
        $this->assertStringNotContainsString('createApp(', $client);
        $this->assertStringContainsString("import('./types/page-props').DoomsdayPageProps", $client);
        $this->assertStringContainsString('/** @type {DoomsdayPageProps} */ (props.initialPage.props).locale', $client);
        $this->assertStringContainsString("import('./types/page-props').DoomsdayPageProps", $ssr);
        $this->assertStringContainsString('/** @type {DoomsdayPageProps} */ (page.props).locale', $ssr);
        $this->assertStringContainsString('ziggyConfigForPage(page)', $ssr);
        $this->assertStringContainsString("import { Ziggy } from './generated/ziggy';", $ssr);
        $this->assertStringNotContainsString('AppNavigationLoader', $ssr);
        $this->assertStringNotContainsString("import './bootstrap'", $ssr);
        $this->assertStringNotContainsString('document.', $ssr);
        $this->assertStringNotContainsString('window.', $ssr);
    }

    #[Test]
    public function i18n_is_request_safe_and_preserves_patreon_and_community_copy_in_all_locales(): void
    {
        $i18n = $this->source('resources/js/i18n/index.ts');
        $server = $this->source('resources/js/i18n/server.js');

        $this->assertStringNotContainsString('i18next-browser-languagedetector', $i18n);
        $this->assertStringNotContainsString('LanguageDetector', $i18n);
        $this->assertStringContainsString("['en', 'it', 'fr', 'de', 'es', 'nl', 'sv', 'pl']", $i18n);
        $this->assertStringContainsString("from 'node:async_hooks'", $server);
        $this->assertStringContainsString('new AsyncLocalStorage()', $server);
        $this->assertStringContainsString('requestI18n.run(instance, callback)', $server);
        $this->assertStringNotContainsString('document', $server);
        $this->assertStringNotContainsString('window', $server);

        foreach (['supportUs:', 'supportOnPatreon:', 'supportProjectDescription:', 'opensInNewTab:'] as $key) {
            $this->assertSame(8, substr_count($i18n, $key), $key);
        }
    }

    #[Test]
    public function shared_page_props_type_is_consumed_by_all_four_frontend_boundaries(): void
    {
        $contract = $this->source('resources/js/types/page-props.ts');
        $client = $this->source('resources/js/app.js');
        $ssr = $this->source('resources/js/ssr.js');
        $timer = $this->source('resources/js/Components/Doomsday/CountdownTimer.vue');
        $seoHead = $this->source('resources/js/Components/Doomsday/SeoHead.vue');

        $this->assertStringContainsString("import type { SeoPageData } from './generated';", $contract);
        foreach (['readonly locale: string;', 'readonly rendered_at: string;', 'readonly seo: SeoPageData;'] as $field) {
            $this->assertStringContainsString($field, $contract);
        }
        foreach ([$client, $ssr, $timer, $seoHead] as $consumer) {
            $this->assertStringContainsString('DoomsdayPageProps', $consumer);
        }
        $this->assertStringNotContainsString('usePage<{ rendered_at: string }>()', $timer);
        $this->assertStringNotContainsString('usePage<{ seo: SeoPageData }>()', $seoHead);
    }

    #[Test]
    public function countdown_uses_request_render_time_for_server_and_first_client_render(): void
    {
        $timer = $this->source('resources/js/Components/Doomsday/CountdownTimer.vue');
        $clock = $this->source('resources/js/Components/Doomsday/countdownClock.js');

        $this->assertStringContainsString('usePage<DoomsdayPageProps>()', $timer);
        $this->assertStringContainsString('parseRenderedAt(page.props.rendered_at)', $timer);
        $this->assertStringContainsString('onMounted(() => {', $timer);
        $this->assertStringContainsString('now.value = Date.now();', $timer);
        $this->assertStringContainsString('window.setInterval', $timer);
        $this->assertStringNotContainsString('ref(Date.now())', $timer);
        $this->assertStringContainsString('Math.floor((target - now) / 1000)', $clock);
        $this->assertStringContainsString('targetDate === null ? now', $clock);
    }

    #[Test]
    public function ssr_scripts_and_node_hydration_regression_are_explicit(): void
    {
        $package = json_decode($this->source('package.json'), true, flags: JSON_THROW_ON_ERROR);
        $scripts = $package['scripts'];

        $this->assertSame('npm run ziggy:generate && vite build --ssr', $scripts['build:ssr']);
        $this->assertSame('npm run build && npm run build:ssr', $scripts['build:production']);
        $this->assertSame('node tests/Node/ssr-hydration.mjs', $scripts['test:ssr']);
        $this->assertStringContainsString('ziggy:generate', $scripts['build:ssr']);
        $this->assertFileExists(base_path('resources/js/generated/ziggy.js'));

        exec('node '.escapeshellarg(base_path('tests/Node/ssr-hydration.mjs')).' 2>&1', $output, $exitCode);
        $this->assertSame(0, $exitCode, implode(PHP_EOL, $output));
        $this->assertStringContainsString('SSR hydration clock contract passed', implode(PHP_EOL, $output));
    }

    private function source(string $path): string
    {
        $source = file_get_contents(base_path($path));
        $this->assertNotFalse($source, $path);

        return $source;
    }
}
