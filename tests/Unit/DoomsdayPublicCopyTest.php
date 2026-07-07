<?php

declare(strict_types=1);

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

final class DoomsdayPublicCopyTest extends TestCase
{
    public function test_public_doomsday_sources_do_not_contain_prohibited_visible_copy(): void
    {
        $paths = [
            __DIR__ . '/../../resources/js/Pages/Doomsday/Home.vue',
            __DIR__ . '/../../resources/js/Pages/Doomsday/About.vue',
            __DIR__ . '/../../resources/js/Layouts/PublicLayout.vue',
            __DIR__ . '/../../resources/js/Components/Doomsday',
            __DIR__ . '/../../resources/js/i18n/index.ts',
            __DIR__ . '/../../database/seeders/DoomsdaySeeder.php',
        ];

        $content = '';
        foreach ($paths as $path) {
            if (is_dir($path)) {
                foreach (glob($path . '/*.vue') ?: [] as $file) {
                    $content .= (string) file_get_contents($file);
                }
                continue;
            }

            $content .= (string) file_get_contents($path);
        }

        $this->assertStringNotContainsString('Artificial Intelligence', $content);
        $this->assertStringNotContainsString('OpenAI', $content);
        $this->assertStringNotContainsString('AI ', $content);
        $this->assertStringNotContainsString('AI<', $content);
        $this->assertStringNotContainsString('Agent Debug', $content);
        $this->assertStringNotContainsString('Backoffice', $content);
        $this->assertStringNotContainsString('Login', $content);
    }

    public function test_public_layout_menu_contains_only_home_and_about_links(): void
    {
        $layout = (string) file_get_contents(__DIR__ . '/../../resources/js/Components/Doomsday/SiteHeader.vue');

        $this->assertStringContainsString('homeUrl', $layout);
        $this->assertStringContainsString('aboutUrl', $layout);
        $this->assertStringNotContainsString('href="/login"', $layout);
        $this->assertStringNotContainsString('backoffice', strtolower($layout));
    }

    public function test_selected_route_uses_desktop_master_detail_and_mobile_detail_first_layouts(): void
    {
        $home = (string) file_get_contents(__DIR__ . '/../../resources/js/Pages/Doomsday/Home.vue');
        $masterDetail = (string) file_get_contents(__DIR__ . '/../../resources/js/Components/Doomsday/SelectedMasterDetail.vue');
        $mobileDetail = (string) file_get_contents(__DIR__ . '/../../resources/js/Components/Doomsday/MobileDetailView.vue');
        $countdownList = (string) file_get_contents(__DIR__ . '/../../resources/js/Components/Doomsday/CountdownList.vue');
        $countdownCard = (string) file_get_contents(__DIR__ . '/../../resources/js/Components/Doomsday/CountdownCard.vue');

        $this->assertStringContainsString('selectedCountdown', $home);
        $this->assertStringContainsString('MobileDetailView', $home);
        $this->assertStringContainsString('SelectedMasterDetail', $home);
        $this->assertStringNotContainsString('<DetailPanel', $home);

        $this->assertStringContainsString('grid-cols-[minmax(500px,0.95fr)_minmax(720px,1.25fr)]', $masterDetail);
        $this->assertStringContainsString('sticky top-28', $masterDetail);
        $this->assertStringContainsString(':compact="true"', $masterDetail);

        $this->assertStringContainsString('ChevronLeft', $mobileDetail);
        $this->assertStringContainsString('Share2', $mobileDetail);
        $this->assertStringContainsString('CountdownTimer', $mobileDetail);
        $this->assertStringContainsString('KeyIndicatorsCard', $mobileDetail);
        $this->assertStringContainsString('VisualizationChart', $mobileDetail);
        $this->assertStringContainsString('fixed inset-x-0 bottom-0', $mobileDetail);

        $this->assertStringContainsString('readonly compact?: boolean', $countdownList);
        $this->assertStringContainsString('readonly compact?: boolean', $countdownCard);
        $this->assertStringContainsString(':dense="compact"', $countdownCard);
    }
}
