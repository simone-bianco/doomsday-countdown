<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;

final class DoomsdayAnalyticsConsentContractTest extends TestCase
{
    public function test_ga4_is_loaded_through_the_consent_runtime_without_an_unconditional_blade_snippet(): void
    {
        $tracking = file_get_contents(resource_path('js/Consent/tracking.ts'));
        $googleConsent = file_get_contents(resource_path('js/Consent/googleConsent.ts'));
        $banner = file_get_contents(resource_path('js/Components/Consent/CookieConsentBanner.vue'));
        $blade = file_get_contents(resource_path('views/app.blade.php'));

        self::assertIsString($tracking);
        self::assertIsString($googleConsent);
        self::assertIsString($banner);
        self::assertIsString($blade);

        self::assertStringContainsString('VITE_GOOGLE_ANALYTICS_ID', $tracking);
        self::assertStringContainsString('VITE_GOOGLE_TAG_MANAGER_PRELOAD', $tracking);
        self::assertStringContainsString('config.loadGoogleTagManagerBeforeConsent || consent.analytics || consent.marketing', $tracking);
        self::assertStringContainsString('initializeGoogleConsentDefaults();', $tracking);
        self::assertMatchesRegularExpression(
            "/initializeGoogleConsentDefaults\(\);\s+loadAllowedGoogleTags\(createConsentPreferences\(EMPTY_CONSENT_DRAFT, 'rejected_all', null\)\);/",
            $tracking,
        );
        self::assertMatchesRegularExpression(
            '/loadAllowedGoogleTags\(consent\);\s+if \(consent\.analytics\) \{\s+trackVirtualPageView\(\);\s+\}/',
            $tracking,
        );
        self::assertStringNotContainsString('shouldLoadTracking', $tracking);
        self::assertStringContainsString('send_page_view: false', $googleConsent);
        self::assertStringContainsString("router.on('finish'", $banner);
        self::assertStringNotContainsString('googletagmanager.com/gtag/js', $blade);
        self::assertStringNotContainsString('G-2L9QPGWKVL', $blade);
    }

    public function test_page_views_are_consent_gated_and_deduplicated_by_location(): void
    {
        $tracking = file_get_contents(resource_path('js/Consent/tracking.ts'));

        self::assertIsString($tracking);
        self::assertStringContainsString('let lastTrackedPageLocation: string | null = null;', $tracking);
        self::assertStringContainsString("if (typeof window === 'undefined' || !canTrackAnalytics())", $tracking);
        self::assertStringContainsString('if (lastTrackedPageLocation === pageLocation)', $tracking);
        self::assertStringContainsString('lastTrackedPageLocation = pageLocation;', $tracking);
        self::assertMatchesRegularExpression(
            '/if \(!consent\.analytics\) \{\s+lastTrackedPageLocation = null;\s+\}/',
            $tracking,
        );
    }

    public function test_production_measurement_id_is_documented_without_enabling_local_tracking(): void
    {
        $environment = file_get_contents(base_path('.env.example'));

        self::assertIsString($environment);
        self::assertStringContainsString('# Production GA4: VITE_GOOGLE_ANALYTICS_ID=G-2L9QPGWKVL', $environment);
        self::assertStringContainsString("VITE_GOOGLE_TAG_MANAGER_ID=\n", $environment);
        self::assertStringContainsString("VITE_GOOGLE_ANALYTICS_ID=\n", $environment);
    }
}
