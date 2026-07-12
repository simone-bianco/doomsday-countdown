<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;

class DoomsdayResponsiveAssetsContractTest extends TestCase
{
    /** @var array<string, array{size: array{int, int}, widths: list<int>, fallback: string}> */
    private const RESPONSIVE_ASSETS = [
        'doomsday_hero_background_desktop' => ['size' => [1536, 1024], 'widths' => [640, 960, 1280, 1536], 'fallback' => 'images/doomsday/doomsday_hero_background_desktop.png'],
        'doomsday_hero_background_mobile' => ['size' => [1440, 2560], 'widths' => [480, 720, 1080, 1440], 'fallback' => 'images/doomsday/doomsday_hero_background_mobile.png'],
        'ai_job_apocalypse' => ['size' => [1672, 941], 'widths' => [480, 768, 1200, 1672], 'fallback' => 'images/doomsday/ai_job_apocalypse.png'],
        'antibiotic_apocalypse' => ['size' => [1535, 1024], 'widths' => [480, 768, 1200, 1535], 'fallback' => 'images/doomsday/antibiotic_apocalypse.png'],
        'europe_war_countdown' => ['size' => [1672, 941], 'widths' => [480, 768, 1200, 1672], 'fallback' => 'images/doomsday/europe_war_countdown.png'],
        'extreme_heat_breakpoint_separate' => ['size' => [1671, 941], 'widths' => [480, 768, 1200, 1671], 'fallback' => 'images/doomsday/extreme_heat_breakpoint_separate.png'],
        'taiwan_invasion' => ['size' => [1672, 941], 'widths' => [480, 768, 1200, 1672], 'fallback' => 'images/doomsday/taiwan_invasion.png'],
        'uninhabitable_earth_separate' => ['size' => [1536, 1024], 'widths' => [480, 768, 1200, 1536], 'fallback' => 'images/doomsday/uninhabitable_earth_separate.png'],
        'society_collapse_separate' => ['size' => [536, 473], 'widths' => [320, 536], 'fallback' => 'images/doomsday/society_collapse_separate.png'],
    ];

    public function test_favicon_family_has_verified_dimensions_mime_and_manifest_contract(): void
    {
        foreach ([
            'favicon-16x16.png' => [16, 16],
            'favicon-32x32.png' => [32, 32],
            'apple-touch-icon.png' => [180, 180],
            'icons/icon-192.png' => [192, 192],
            'icons/icon-512.png' => [512, 512],
        ] as $relativePath => [$width, $height]) {
            $this->assertImage(public_path($relativePath), $width, $height, 'image/png');
        }

        $ico = public_path('favicon.ico');
        $this->assertFileExists($ico);
        $this->assertGreaterThan(0, filesize($ico));
        $this->assertContains(mime_content_type($ico), ['image/vnd.microsoft.icon', 'image/x-icon']);
        $this->assertSame([[16, 16], [32, 32], [48, 48]], $this->icoDimensions($ico));

        $manifest = json_decode((string) file_get_contents(public_path('site.webmanifest')), true, 512, JSON_THROW_ON_ERROR);
        $this->assertSame('Doomsday Clock', $manifest['name']);
        $this->assertSame('#050505', $manifest['theme_color']);
        $this->assertSame('#050505', $manifest['background_color']);
        $this->assertSame([
            ['src' => '/icons/icon-192.png', 'sizes' => '192x192', 'type' => 'image/png', 'purpose' => 'any'],
            ['src' => '/icons/icon-512.png', 'sizes' => '512x512', 'type' => 'image/png', 'purpose' => 'any'],
        ], $manifest['icons']);
    }

    public function test_responsive_variants_have_expected_mime_dimensions_and_png_fallbacks(): void
    {
        foreach (self::RESPONSIVE_ASSETS as $name => $asset) {
            [$sourceWidth, $sourceHeight] = $asset['size'];
            $fallback = public_path($asset['fallback']);
            $this->assertImage($fallback, $sourceWidth, $sourceHeight, 'image/png');

            foreach ($asset['widths'] as $width) {
                $height = (int) round($sourceHeight * $width / $sourceWidth);
                $this->assertImage(public_path("images/doomsday/responsive/{$name}-{$width}.webp"), $width, $height, 'image/webp');
                $this->assertImage(public_path("images/doomsday/responsive/{$name}-{$width}.avif"), $width, $height, 'image/avif');
            }

            $largestWidth = max($asset['widths']);
            $this->assertLessThan(filesize($fallback), filesize(public_path("images/doomsday/responsive/{$name}-{$largestWidth}.webp")));
            $this->assertLessThan(filesize($fallback), filesize(public_path("images/doomsday/responsive/{$name}-{$largestWidth}.avif")));
        }

        $this->assertSame(
            hash_file('sha256', resource_path('js/assets/doomsday/doomsday_hero_background_desktop.png')),
            hash_file('sha256', public_path('images/doomsday/doomsday_hero_background_desktop.png')),
        );
        $this->assertSame(
            hash_file('sha256', resource_path('js/assets/doomsday/doomsday_hero_background_mobile.png')),
            hash_file('sha256', public_path('images/doomsday/doomsday_hero_background_mobile.png')),
        );
    }

    public function test_blade_layout_and_first_party_consumers_use_the_public_responsive_contract(): void
    {
        $blade = (string) file_get_contents(resource_path('views/app.blade.php'));
        foreach ([
            '<link rel="icon" href="/favicon.ico" sizes="any">',
            '<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">',
            '<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">',
            '<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">',
            '<link rel="manifest" href="/site.webmanifest">',
            '<meta name="theme-color" content="#050505">',
            '@routes',
            '@vite([\'resources/js/app.js\'])',
            '@inertiaHead',
        ] as $contract) {
            $this->assertStringContainsString($contract, $blade);
        }
        $this->assertLessThan(strpos($blade, '@vite'), strpos($blade, '@routes'));
        $this->assertLessThan(strpos($blade, '@inertiaHead'), strpos($blade, '@vite'));
        $this->assertStringNotContainsString('z-docs', $blade);
        $this->assertStringNotContainsString('/favicon.svg', $blade);
        $this->assertStringNotContainsString('googletagmanager.com', $blade);
        $this->assertStringNotContainsString('G-2L9QPGWKVL', $blade);

        $layout = (string) file_get_contents(resource_path('js/Layouts/PublicLayout.vue'));
        $responsiveImage = (string) file_get_contents(resource_path('js/Components/Doomsday/ResponsiveImage.vue'));
        $helper = (string) file_get_contents(resource_path('js/Support/doomsdayResponsiveImages.ts'));
        foreach ([
            "import SeoHead from '@/Components/Doomsday/SeoHead.vue';",
            "import ResponsiveImage from '@/Components/Doomsday/ResponsiveImage.vue';",
            '<SeoHead />',
            'mobile-src="/images/doomsday/doomsday_hero_background_mobile.png"',
            ":fetch-priority=\"activePage === 'home' ? 'high' : 'auto'\"",
        ] as $contract) {
            $this->assertStringContainsString($contract, $layout);
        }
        foreach (['<picture', 'type="image/avif"', 'type="image/webp"', ':width="resolvedWidth"', ':height="resolvedHeight"', ':loading="loading"', ':fetchpriority="fetchPriority"', 'v-if="inactiveMedia"', 'transparentPixel'] as $contract) {
            $this->assertStringContainsString($contract, $responsiveImage);
        }
        foreach (array_keys(self::RESPONSIVE_ASSETS) as $name) {
            $this->assertStringContainsString($name, $helper);
        }

        $mobileDetail = (string) file_get_contents(resource_path('js/Components/Doomsday/MobileDetailView.vue'));
        $desktopDetail = (string) file_get_contents(resource_path('js/Components/Doomsday/SelectedMasterDetail.vue'));
        $this->assertStringContainsString('media="(max-width: 1023px)"', $mobileDetail);
        $this->assertStringContainsString('inactive-media="(min-width: 1024px)"', $mobileDetail);
        $this->assertStringContainsString('media="(min-width: 1024px)"', $desktopDetail);
        $this->assertStringContainsString('inactive-media="(max-width: 1023px)"', $desktopDetail);

        foreach ([
            'Components/Doomsday/CountdownCardImage.vue',
            'Components/Doomsday/DetailPanel.vue',
            'Components/Doomsday/MobileDetailView.vue',
            'Components/Doomsday/SelectedMasterDetail.vue',
            'Components/Doomsday/AboutHero.vue',
            'Components/Doomsday/AboutGreatFilterSection.vue',
        ] as $relativePath) {
            $source = (string) file_get_contents(resource_path('js/'.$relativePath));
            $this->assertStringContainsString('ResponsiveImage', $source, $relativePath);
            $this->assertStringContainsString('sizes=', $source, $relativePath);
        }

        $latestNews = (string) file_get_contents(resource_path('js/Components/Doomsday/LatestNewsCarousel.vue'));
        $this->assertStringNotContainsString('ResponsiveImage', $latestNews);
        $this->assertStringContainsString(':src="activeItem.image_url"', $latestNews);
    }

    public function test_runtime_sources_do_not_reference_z_docs(): void
    {
        $runtime = (string) file_get_contents(resource_path('views/app.blade.php'));
        foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator(resource_path('js'))) as $file) {
            if (! $file->isFile() || ! in_array($file->getExtension(), ['js', 'ts', 'vue'], true)) {
                continue;
            }
            $runtime .= "\n".(string) file_get_contents($file->getPathname());
        }
        $this->assertStringNotContainsString('z-docs/', $runtime);
    }

    private function assertImage(string $path, int $width, int $height, string $mime): void
    {
        $this->assertFileExists($path);
        $this->assertGreaterThan(0, filesize($path));
        $info = getimagesize($path);
        $this->assertIsArray($info, $path);
        $this->assertSame($width, $info[0], $path);
        $this->assertSame($height, $info[1], $path);
        $this->assertSame($mime, $info['mime'], $path);
        $this->assertSame($mime, mime_content_type($path), $path);
    }

    /** @return list<array{int, int}> */
    private function icoDimensions(string $path): array
    {
        $bytes = (string) file_get_contents($path);
        $header = unpack('vreserved/vtype/vcount', substr($bytes, 0, 6));
        $this->assertSame(0, $header['reserved']);
        $this->assertSame(1, $header['type']);

        $dimensions = [];
        for ($index = 0; $index < $header['count']; $index++) {
            $entry = unpack('Cwidth/Cheight', substr($bytes, 6 + ($index * 16), 2));
            $dimensions[] = [$entry['width'] ?: 256, $entry['height'] ?: 256];
        }
        sort($dimensions);

        return $dimensions;
    }
}
