<?php

declare(strict_types=1);

use App\Enums\NewsLocale;
use Carbon\CarbonImmutable;

$shared = require __DIR__.'/../_shared.php';

return new class($shared)
{
    public function __construct(private object $shared) {}

    /** @return array<int, array<string, mixed>> */
    public function news(): array
    {
        $sources = $this->shared->sources();

        return [
            [
                'locale' => NewsLocale::All,
                'title' => 'WHO warns that more deadly heat weeks may still lie ahead',
                'excerpt' => 'WHO/Europe reports lessons from 41 Member States and stresses that tested heat-health action plans connect warnings, health services and outreach before heat arrives.',
                'source_name' => 'WHO Regional Office for Europe',
                'source_url' => $sources['who_heat_statement_2026'],
                'preview_image_url' => 'https://cdn.who.int/media/images/librariesprovider2/default-album/news-images/extreme-heat-more-deadly-weeks-may-still-lie-ahead-for-the-european-region-web001220.tmb-1200v.jpg?sfvrsn=d9d1e3cf_2',
                'published_at' => CarbonImmutable::parse('2026-07-07 12:00:00', 'UTC'),
            ],
            [
                'locale' => NewsLocale::All,
                'title' => 'The surprising impacts of extreme heat | UNDRR',
                'excerpt' => 'UNDRR shows how extreme heat reshapes health, learning, mobility and daily life, and why risk reduction must extend beyond temperature warnings.',
                'source_name' => 'United Nations Office for Disaster Risk Reduction',
                'source_url' => 'https://www.youtube.com/watch?v=iwoijGguBFM',
                'content_type' => 'youtube_video',
                'external_provider' => 'youtube',
                'external_id' => 'iwoijGguBFM',
                'embed_url' => 'https://www.youtube.com/embed/iwoijGguBFM',
                'preview_image_url' => 'https://i.ytimg.com/vi/iwoijGguBFM/hqdefault.jpg',
            ],
            [
                'locale' => NewsLocale::All,
                'title' => 'WHO calls for stronger heat-health action plans',
                'excerpt' => 'WHO/Europe describes extreme summer heat as a persistent public-health crisis and outlines coordinated planning, warning and response measures.',
                'source_name' => 'WHO Regional Office for Europe',
                'source_url' => $sources['who_plans_news'],
                'preview_image_url' => 'https://cdn.who.int/media/images/librariesprovider2/default-album/news-images/strengthening-heat-health-action-plans-to-protect-public-health--with-who-guidance-u2741l4-web001167.tmb-1200v.jpg?sfvrsn=b9cb2c36_3',
                'published_at' => CarbonImmutable::parse('2026-06-02 12:00:00', 'UTC'),
            ],
            [
                'locale' => NewsLocale::All,
                'title' => 'WMO confirms 2025 among the three warmest years recorded',
                'excerpt' => 'WMO’s consolidated analysis places the 2025 global mean surface-temperature anomaly at 1.44°C above the 1850–1900 average.',
                'source_name' => 'World Meteorological Organization',
                'source_url' => $sources['wmo_2025'],
                'preview_image_url' => 'https://wmo.int/sites/default/files/2026-01/Thumbnails%202026.png',
                'published_at' => CarbonImmutable::parse('2026-01-14 12:00:00', 'UTC'),
            ],
            [
                'locale' => NewsLocale::All,
                'title' => 'Copernicus documents record heat stress in southeastern Europe',
                'excerpt' => 'Summer 2024 brought 66 days with at least strong UTCI heat stress and 23 tropical nights across southeastern Europe.',
                'source_name' => 'Copernicus Climate Change Service',
                'source_url' => $sources['copernicus_southeast'],
                'preview_image_url' => 'https://climate.copernicus.eu/sites/default/files/styles/hero_image_extra_large_2x/public/2025-03/ESOTC-banner_19.jpg?itok=BXpzDlbx',
            ],
            [
                'locale' => NewsLocale::All,
                'title' => 'Copernicus tracks expanding tropical-night exposure across Europe',
                'excerpt' => 'The European State of the Climate thermal-stress analysis shows a larger share of Europe experiencing nights that remain at or above 20°C.',
                'source_name' => 'Copernicus Climate Change Service',
                'source_url' => $sources['copernicus_thermal'],
                'preview_image_url' => 'https://climate.copernicus.eu/sites/default/files/styles/hero_image_extra_large_2x/public/2025-03/ESOTC-banner_18.jpg?itok=28kveJaw',
            ],
            [
                'locale' => NewsLocale::All,
                'title' => 'WMO confirms 2024 as the warmest year on record',
                'excerpt' => 'The WMO consolidated estimate puts the 2024 global mean temperature at about 1.55°C above the 1850–1900 baseline.',
                'source_name' => 'World Meteorological Organization',
                'source_url' => $sources['wmo_2024'],
                'preview_image_url' => 'https://wmo.int/sites/default/files/2025-01/Screenshot%202025-01-10%20170909.jpg',
                'published_at' => CarbonImmutable::parse('2025-01-10 12:00:00', 'UTC'),
            ],
            [
                'locale' => NewsLocale::All,
                'title' => 'Heat claims more than 175,000 lives annually in the WHO European Region',
                'excerpt' => 'WHO estimates the 2000–2019 annual average burden and highlights Europe’s rapid warming and the preventability of many heat-related deaths.',
                'source_name' => 'WHO Regional Office for Europe',
                'source_url' => $sources['who_heat_deaths'],
                'preview_image_url' => 'https://cdn.who.int/media/images/librariesprovider2/default-album/news-images/united-nations-secretary-general-issues-global-call-to-action-on-extreme-heat---20183559.tmb-1200v.jpg?sfvrsn=1dba857_4',
                'published_at' => CarbonImmutable::parse('2024-08-01 12:00:00', 'UTC'),
            ],
            [
                'locale' => NewsLocale::All,
                'title' => "WMO's Heat expert John Nairn explains why it's important to measure heatwave intensity",
                'excerpt' => 'WMO heat expert John Nairn explains why measuring heatwave intensity is essential for comparable warnings, planning and protection.',
                'source_name' => 'World Meteorological Organization - WMO',
                'source_url' => 'https://www.youtube.com/watch?v=jb1OcQogLfY',
                'content_type' => 'youtube_video',
                'external_provider' => 'youtube',
                'external_id' => 'jb1OcQogLfY',
                'embed_url' => 'https://www.youtube.com/embed/jb1OcQogLfY',
                'preview_image_url' => 'https://i.ytimg.com/vi/jb1OcQogLfY/hqdefault.jpg',
            ],
            [
                'locale' => NewsLocale::All,
                'title' => 'Eurostat shows cooling demand concentrated in southern EU countries',
                'excerpt' => 'The 2022 cooling-degree-day comparison places Malta and Cyprus highest, followed by Spain, Italy and Greece.',
                'source_name' => 'Eurostat',
                'source_url' => $sources['eurostat_cdd_news'],
                'preview_image_url' => 'https://ec.europa.eu/eurostat/documents/4187653/15337362/Olivier%20Le%20Moal_shutterstock_519476797_RV.jpg',
                'published_at' => CarbonImmutable::parse('2023-02-27 12:00:00', 'UTC'),
            ],
        ];
    }

    /** @return array<int, string> */
    public function legacySourceUrls(): array
    {
        $sources = $this->shared->sources();

        return [$sources['who_guidance_news'], $sources['wmo_2023']];
    }
};
