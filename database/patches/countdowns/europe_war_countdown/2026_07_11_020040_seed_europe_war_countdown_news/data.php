<?php

declare(strict_types=1);

use App\Enums\NewsLocale;
use Carbon\CarbonImmutable;

$shared = require __DIR__.'/../_shared.php';

return new class($shared)
{
    public function __construct(private object $shared) {}

    /** @return list<array<string, mixed>> */
    public function news(): array
    {
        return [
            [
                'locale' => NewsLocale::All,
                'title' => 'NATO annual report records higher European defence investment',
                'excerpt' => 'NATO’s 2025 annual report documents continued increases in defence investment by European allies and Canada while separating expenditure data from readiness outcomes.',
                'source_name' => 'NATO',
                'source_url' => $this->shared->sources()['nato_annual_report_2025'],
                'preview_image_url' => 'https://s7g10.scene7.com/is/image/ncia/20260326_pc-sg-annualreport2025_0003',
                'published_at' => CarbonImmutable::parse('2026-03-26 12:00:00', 'UTC'),
            ],
            [
                'locale' => NewsLocale::All,
                'title' => 'NATO publishes defence expenditure estimates through 2025',
                'excerpt' => 'The official tables provide the observed and estimated spending series used to track Europe and Canada across real expenditure and GDP-share measures.',
                'source_name' => 'NATO',
                'source_url' => $this->shared->sources()['nato_expenditure_2025'],
                'preview_image_url' => 'https://s7g10.scene7.com/is/image/ncia/240617-def-exp',
                'published_at' => CarbonImmutable::parse('2025-08-28 12:00:00', 'UTC'),
            ],
            [
                'locale' => NewsLocale::All,
                'title' => 'The Hague summit raises NATO’s long-term investment benchmark',
                'excerpt' => 'Allies agreed a 2035 investment commitment combining core defence spending with broader security and resilience expenditure, reinforcing the need for measurable delivery paths.',
                'source_name' => 'NATO',
                'source_url' => $this->shared->sources()['nato_hague_news'],
                'preview_image_url' => 'https://s7g10.scene7.com/is/image/ncia/20250625_summit-welcome-photo_0001',
                'published_at' => CarbonImmutable::parse('2025-06-27 12:00:00', 'UTC'),
            ],
            [
                'locale' => NewsLocale::All,
                'title' => 'Investing more than ever: EU defence spending and EDA data 2024-2025',
                'excerpt' => 'The European Defence Agency explains the record €343 billion reported for 2024 and how investment data supports a measurable European readiness assessment.',
                'source_name' => 'European Defence Agency',
                'source_url' => 'https://www.youtube.com/watch?v=EsIntcwZc3o',
                'content_type' => 'youtube_video',
                'preview_image_url' => 'https://i.ytimg.com/vi/EsIntcwZc3o/hqdefault.jpg',
                'embed_url' => 'https://www.youtube.com/embed/EsIntcwZc3o',
                'external_provider' => 'youtube',
                'external_id' => 'EsIntcwZc3o',
            ],
            [
                'locale' => NewsLocale::All,
                'title' => 'Strengthen peace in the European Union: Readiness 2030',
                'excerpt' => 'The European Commission outlines the financing, industrial and cooperation measures behind its Readiness 2030 plan for a stronger European defence posture.',
                'source_name' => 'European Commission',
                'source_url' => 'https://www.youtube.com/watch?v=c3kONILHpGY',
                'content_type' => 'youtube_video',
                'preview_image_url' => 'https://i.ytimg.com/vi/c3kONILHpGY/hqdefault.jpg',
                'embed_url' => 'https://www.youtube.com/embed/c3kONILHpGY',
                'external_provider' => 'youtube',
                'external_id' => 'c3kONILHpGY',
            ],
            [
                'locale' => NewsLocale::All,
                'title' => 'Council adopts €150 billion SAFE instrument for joint procurement',
                'excerpt' => 'SAFE creates an EU loan instrument for member states seeking rapid defence investment through common procurement in priority capability areas.',
                'source_name' => 'Council of the European Union',
                'source_url' => $this->shared->sources()['safe_council'],
                'preview_image_url' => 'https://defence-industry-space.ec.europa.eu/sites/default/files/2025-07/SAFE%20web%20banner%20v1.png',
                'published_at' => CarbonImmutable::parse('2025-05-27 12:00:00', 'UTC'),
            ],
            [
                'locale' => NewsLocale::All,
                'title' => 'Commission proposes faster procedures for defence investment',
                'excerpt' => 'The simplification proposal targets permitting, procurement and programme administration bottlenecks that can delay conversion of budgets into deployable capability.',
                'source_name' => 'European Commission',
                'source_url' => $this->shared->sources()['defence_simplification'],
                'preview_image_url' => 'https://commission.europa.eu/sites/default/files/styles/ewcms_metatag_image/public/2025-06/Defence%20omnibus%20page_567678725_CR.png?h=34e43602&itok=bj9h0q3J',
                'published_at' => CarbonImmutable::parse('2025-06-17 12:00:00', 'UTC'),
            ],
            [
                'locale' => NewsLocale::All,
                'title' => 'EDA defence review identifies opportunities for joint military projects',
                'excerpt' => 'The Coordinated Annual Review on Defence maps shared capability priorities and provides a practical route from national plans to collaborative European projects.',
                'source_name' => 'European Defence Agency',
                'source_url' => $this->shared->sources()['eda_card'],
                'preview_image_url' => 'https://eda.europa.eu/images/default-source/default-lib/card-2024.jpg',
                'published_at' => CarbonImmutable::parse('2024-11-19 12:00:00', 'UTC'),
            ],
            [
                'locale' => NewsLocale::All,
                'title' => 'Commission advances a European military mobility area',
                'excerpt' => 'The initiative addresses regulatory, infrastructure and transport barriers that affect the speed and reliability of moving personnel and equipment across Europe.',
                'source_name' => 'European Commission',
                'source_url' => $this->shared->sources()['military_mobility'],
                'preview_image_url' => 'https://commission.europa.eu/sites/default/files/styles/ewcms_metatag_image/public/media_avportal_thumbnails/GqvG2GyQfvYQ6enHfEEsDuhXbZPCSwvzVYe5ijxjje8.jpg?h=c9f93661&itok=ZbZUkeCq',
                'published_at' => CarbonImmutable::parse('2025-11-19 12:00:00', 'UTC'),
            ],
            [
                'locale' => NewsLocale::All,
                'title' => 'Commission proposes five joint projects for European defence industry',
                'excerpt' => 'The proposed collaborative projects connect industrial capacity with priority capability areas and provide a concrete delivery signal for the Readiness 2030 agenda.',
                'source_name' => 'European Commission',
                'source_url' => $this->shared->sources()['edip_projects_2026'],
                'preview_image_url' => 'https://defence-industry-space.ec.europa.eu/sites/default/files/styles/ewcms_metatag_image/public/2026-07/European%20Defence%20Projects%20of%20Common%20Interest%20%28EDPCI%29%20-%20%28169%29%20%281%29.png?h=d1cb525d&itok=BFYh0PFB',
                'published_at' => CarbonImmutable::parse('2026-07-03 12:00:00', 'UTC'),
            ],
        ];
    }
};
