<?php

declare(strict_types=1);

namespace Tests\Feature;

use Database\Seeders\DoomsdaySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class DoomsdayLatestUpdateCtaQaTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_consumes_the_generated_sidebar_contract_without_a_featured_countdown(): void
    {
        $generated = (string) file_get_contents(base_path('resources/js/types/generated.d.ts'));
        $home = (string) file_get_contents(base_path('resources/js/Pages/Doomsday/Home.vue'));
        $sidebar = (string) file_get_contents(base_path('resources/js/Components/Doomsday/SidebarCards.vue'));

        foreach (['sidebar: HomeSidebarData;', 'export type HomeSidebarData = {', 'export type LatestNewsItemData = {', 'export type NewsActivityData = {'] as $contract) {
            $this->assertStringContainsString($contract, $generated);
        }

        $this->assertStringContainsString(':sidebar="page.sidebar"', $home);
        $this->assertStringNotContainsString('page.countdowns[0]', $home);
        $this->assertStringNotContainsString('const featured', $home);

        $this->assertStringContainsString('readonly sidebar: HomeSidebarData', $sidebar);
        $this->assertStringContainsString('<LatestNewsCarousel :items="sidebar.latest_news" />', $sidebar);
        $this->assertStringContainsString('<PublicSignalActivityCard :activity="sidebar.signal_activity" />', $sidebar);
        $this->assertStringNotContainsString('readonly featured:', $sidebar);
    }

    public function test_latest_news_carousel_has_stable_geometry_skeleton_primary_link_and_accessible_controls(): void
    {
        $carousel = (string) file_get_contents(base_path('resources/js/Components/Doomsday/LatestNewsCarousel.vue'));

        foreach ([
            'readonly items: readonly LatestNewsItemData[]',
            'autoplayIntervalMs: 7000',
            "import { AnimatePresence, motion } from 'motion-v';",
            'Button, Card, Image, SkeletonLoader',
            "const slideViewportClass = 'relative min-h-[32rem] overflow-hidden sm:min-h-[35.5rem]'",
            "const slideContentClass = 'grid grid-rows-[1.25rem_3.5rem_4.5rem_1.5rem]",
            'v-else-if="hasPendingSlide"',
            ':animated="!reducedMotion"',
            'loading-type="skeleton"',
            'aspect-ratio="56.25%"',
            'line-clamp-2 min-h-[3.5rem]',
            'line-clamp-3 min-h-[4.5rem]',
            '<AnimatePresence mode="wait" :initial="false">',
            '<motion.article',
            ':initial="slideInitial"',
            ':exit="slideExit"',
            ":is=\"activeExternalUrl ? 'a' : Link\"",
            ':href="activeExternalUrl ?? activeItem.countdown_url"',
            ":rel=\"activeExternalUrl ? 'noopener noreferrer' : undefined\"",
            'pointer-events-none absolute inset-x-0 top-0 z-20 aspect-video',
            'h-11 min-h-11 w-11 min-w-11',
            ':aria-current="index === currentIndex ? \'true\' : undefined"',
            '@click="goTo(index)"',
            'v-if="!items.length"',
            'v-if="hasMultipleItems"',
            ':icon="ChevronLeft"',
            ':icon="ChevronRight"',
            'role="region"',
            'aria-roledescription="carousel"',
            'aria-roledescription="slide"',
        ] as $contract) {
            $this->assertStringContainsString($contract, $carousel, 'Missing modern carousel contract: '.$contract);
        }

        $primaryLinkStart = strpos($carousel, '<component');
        $primaryLinkEnd = strpos($carousel, '</component>');
        $arrowOverlayStart = strpos($carousel, '<div v-if="hasMultipleItems" class="pointer-events-none absolute');
        $dotsStart = strpos($carousel, '<div :class="paginationClass" role="group"');
        $this->assertIsInt($primaryLinkStart);
        $this->assertIsInt($primaryLinkEnd);
        $this->assertIsInt($arrowOverlayStart);
        $this->assertIsInt($dotsStart);
        $this->assertGreaterThan($primaryLinkEnd, $arrowOverlayStart, 'Arrow controls must remain outside the primary slide link.');
        $this->assertGreaterThan($primaryLinkEnd, $dotsStart, 'Dot controls must remain outside the primary slide link.');

        foreach (['v-show', '{{ currentIndex + 1 }} / {{ items.length }}', '<ExternalLink', "{{ t('openSource') }}", '<Link v-else', '<button', 'setTimeout(', 'window.fetch', 'axios.'] as $forbidden) {
            $this->assertStringNotContainsString($forbidden, $carousel, 'Obsolete or unsafe carousel marker: '.$forbidden);
        }
    }

    public function test_latest_news_carousel_preserves_autoplay_pause_keyboard_and_cleanup_contracts(): void
    {
        $carousel = (string) file_get_contents(base_path('resources/js/Components/Doomsday/LatestNewsCarousel.vue'));

        foreach ([
            'useDoomsdayReducedMotion',
            '!reducedMotion.value',
            'setInterval(() => advance(1), props.autoplayIntervalMs)',
            "document.addEventListener('visibilitychange', handleVisibilityChange)",
            "document.removeEventListener('visibilitychange', handleVisibilityChange)",
            'onBeforeUnmount',
            'clearAutoplay()',
            '@mouseenter="hoverPaused = true"',
            '@mouseleave="hoverPaused = false"',
            '@focusin="focusPaused = true"',
            '@focusout="handleFocusOut"',
            '@keydown.left.prevent="previous"',
            '@keydown.right.prevent="next"',
            'navigationDirection.value = direction',
            'forwardDistance <= backwardDistance ? 1 : -1',
            'const slideInitial = computed(() => reducedMotion.value',
            '? { opacity: 0 }',
            'const slideTransition = computed(() => reducedMotion.value',
            '? { duration: 0.12 }',
        ] as $contract) {
            $this->assertStringContainsString($contract, $carousel, 'Missing lifecycle or motion contract: '.$contract);
        }
    }

    public function test_public_signal_activity_uses_backend_buckets_without_risk_score_semantics(): void
    {
        $activity = (string) file_get_contents(base_path('resources/js/Components/Doomsday/PublicSignalActivityCard.vue'));
        $translations = (string) file_get_contents(base_path('resources/js/i18n/index.ts'));

        foreach ([
            'readonly activity: NewsActivityData',
            'props.activity.bucket_counts',
            'props.activity.bucket_labels',
            '{{ activity.total_items }}',
            '{{ activity.unique_sources }}',
            'activity.latest_published_at',
            'activity.top_countdown_title',
            'activity.top_countdown_count',
            "t('publicSignalActivityDisclaimer')",
            'v-if="!hasActivity"',
        ] as $contract) {
            $this->assertStringContainsString($contract, $activity, 'Missing activity contract: '.$contract);
        }

        $this->assertStringContainsString('Published items from monitored public sources. Volume measures source activity, not event probability.', $translations);
        $this->assertStringContainsString('Elementi pubblicati da fonti pubbliche monitorate. Il volume misura l’attività delle fonti, non la probabilità di un evento.', $translations);

        foreach (['18.0', '/100', 'Global Risk Index', 'Global risk index', "t('riskIndex')", 'probability_score', 'confidence_score'] as $forbidden) {
            $this->assertStringNotContainsString($forbidden, $activity.$translations, 'Misleading activity marker: '.$forbidden);
        }
    }

    public function test_old_latest_update_and_hardcoded_risk_ui_are_removed(): void
    {
        $home = (string) file_get_contents(base_path('resources/js/Pages/Doomsday/Home.vue'));
        $sidebar = (string) file_get_contents(base_path('resources/js/Components/Doomsday/SidebarCards.vue'));
        $translations = (string) file_get_contents(base_path('resources/js/i18n/index.ts'));
        $source = $home.$sidebar.$translations;

        foreach (['18.0', '/100', 'Global Risk Index', 'Global risk index', 'Daily update', 'Aggiornamento giornaliero', 'dailyUpdate', 'riskIndex', 'latestUpdate', 'page.countdowns[0]'] as $forbidden) {
            $this->assertStringNotContainsString($forbidden, $source, 'Obsolete Home sidebar marker: '.$forbidden);
        }
    }

    public function test_sidebar_redesign_does_not_break_public_taiwan_route_or_selection_runtime(): void
    {
        $this->seed(DoomsdaySeeder::class);

        $this->get('/?lang=en')
            ->assertOk()
            ->assertSee('Doomsday Countdown')
            ->assertSee('Taiwan Invasion');

        $this->get('/countdowns/taiwan-invasion?lang=en')
            ->assertOk()
            ->assertSee('Taiwan Invasion');

        $selection = (string) file_get_contents(base_path('resources/js/Composables/useDoomsdaySelection.ts'));
        $lazy = (string) file_get_contents(base_path('resources/js/Composables/useDoomsdayLazySections.ts'));
        $runtime = $selection.$lazy;

        $this->assertStringContainsString('axios.get<{ data: CountdownOverviewData }>', $selection);
        $this->assertStringContainsString("route('countdowns.data.overview'", $selection);
        $this->assertStringContainsString('pendingSelectedSlug.value === requestedSlug', $selection);
        $this->assertStringContainsString('axios.get<{ data: SectionDataByKey[K] }>', $lazy);
        $this->assertStringContainsString('countdownSlug.value === requestedSlug', $lazy);

        foreach (['router.visit', 'router.reload', 'router.prefetch', 'history.pushState', 'window.location', 'window.fetch'] as $forbidden) {
            $this->assertStringNotContainsString($forbidden, $runtime);
        }
    }
}
