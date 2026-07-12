<?php

declare(strict_types=1);

use App\Enums\NewsLocale;
use Carbon\CarbonImmutable;

$shared = require __DIR__.'/../_shared.php';

return new class($shared)
{
    public function __construct(private object $shared) {}

    /** @return array<int, string> */
    public function legacySourceUrls(): array
    {
        return [
            $this->shared->sources()['oecd_ai_jobs'],
            $this->shared->sources()['oecd_conference'],
        ];
    }

    /** @return array<int, array<string, mixed>> */
    public function news(): array
    {
        return [
            [
                'locale' => NewsLocale::All,
                'title' => 'One in four jobs may be transformed by generative AI',
                'excerpt' => 'The ILO–NASK index finds broad task exposure but stresses that job transformation is more likely than wholesale replacement.',
                'source_name' => 'International Labour Organization',
                'source_url' => $this->shared->sources()['ilo_index_news'],
                'preview_image_url' => 'https://www.ilo.org/sites/default/files/2025-05/Blur%20crowd%202000X840.jpg',
                'published_at' => CarbonImmutable::parse('2025-05-20 12:00:00', 'UTC'),
            ],
            [
                'locale' => NewsLocale::All,
                'title' => 'ILO publishes a refined global index of occupational exposure',
                'excerpt' => 'The working paper updates task-level exposure estimates and documents differences by occupation, income group and gender.',
                'source_name' => 'International Labour Organization',
                'source_url' => $this->shared->sources()['ilo_index'],
                'preview_image_url' => 'https://www.ilo.org/sites/default/files/2025-05/WP140_cover.png',
                'published_at' => CarbonImmutable::parse('2025-05-20 12:00:00', 'UTC'),
            ],
            [
                'locale' => NewsLocale::All,
                'title' => 'ILO data show women face higher GenAI exposure in many labour markets',
                'excerpt' => 'New ILO analysis highlights how occupational segregation places women more often in clerical and highly exposed roles.',
                'source_name' => 'International Labour Organization',
                'source_url' => $this->shared->sources()['ilo_gender'],
                'preview_image_url' => 'https://www.ilo.org/sites/default/files/2026-03/54684325516_2ebd3609db_o.jpg',
                'published_at' => CarbonImmutable::parse('2026-03-05 12:00:00', 'UTC'),
            ],
            [
                'locale' => NewsLocale::All,
                'title' => 'One in five EU enterprises used AI technologies in 2025',
                'excerpt' => 'Eurostat reports 20.0% adoption among enterprises with at least 10 employees, up sharply from the previous year.',
                'source_name' => 'Eurostat',
                'source_url' => $this->shared->sources()['eurostat_2025'],
                'preview_image_url' => 'https://ec.europa.eu/eurostat/documents/4187653/15566025/ultramansk_AdobeStock_1672779636_RV.jpg',
                'published_at' => CarbonImmutable::parse('2025-12-11 12:00:00', 'UTC'),
            ],
            [
                'locale' => NewsLocale::All,
                'title' => 'Enterprise AI adoption rose to 13.5% in the EU in 2024',
                'excerpt' => 'Eurostat’s earlier release documents the acceleration of enterprise use before the further increase recorded in 2025.',
                'source_name' => 'Eurostat',
                'source_url' => $this->shared->sources()['eurostat_2024'],
                'preview_image_url' => 'https://ec.europa.eu/eurostat/documents/4187653/15566044/Gorodenkoff_Shutterstock_2081373391_RV.jpg',
                'published_at' => CarbonImmutable::parse('2025-01-23 12:00:00', 'UTC'),
            ],
            [
                'locale' => NewsLocale::All,
                'title' => 'Generative AI and jobs: potential effects on job quantity and quality',
                'excerpt' => 'This UN Innovation Network webinar, run with the ILO, examines task exposure, augmentation and possible automation without treating exposure as inevitable job loss.',
                'content_type' => 'youtube_video',
                'source_name' => 'UN Innovation Network',
                'source_url' => 'https://www.youtube.com/watch?v=575PL6yMFCc',
                'external_provider' => 'youtube',
                'external_id' => '575PL6yMFCc',
                'embed_url' => 'https://www.youtube.com/embed/575PL6yMFCc',
                'preview_image_url' => 'https://i.ytimg.com/vi/575PL6yMFCc/hqdefault.jpg',
            ],
            [
                'locale' => NewsLocale::All,
                'title' => 'What can governments do to be ready for the future of work?',
                'excerpt' => 'OECD employment director Stefano Scarpetta explains why automation need not produce mass unemployment if policy, skills and transition support keep pace.',
                'content_type' => 'youtube_video',
                'source_name' => 'OECD',
                'source_url' => 'https://www.youtube.com/watch?v=_eVUrR1GsKA',
                'external_provider' => 'youtube',
                'external_id' => '_eVUrR1GsKA',
                'embed_url' => 'https://www.youtube.com/embed/_eVUrR1GsKA',
                'preview_image_url' => 'https://i.ytimg.com/vi/_eVUrR1GsKA/hqdefault.jpg',
            ],
            [
                'locale' => NewsLocale::All,
                'title' => 'ILO reviews global ethics guidance for governing AI at work',
                'excerpt' => 'The review centres worker rights, transparency, participation and accountability in workplace AI governance.',
                'source_name' => 'International Labour Organization',
                'source_url' => $this->shared->sources()['ilo_governance'],
                'preview_image_url' => 'https://www.ilo.org/sites/default/files/2025-11/pexels-googledeepmind-17484975.jpg',
                'published_at' => CarbonImmutable::parse('2025-11-14 12:00:00', 'UTC'),
            ],
            [
                'locale' => NewsLocale::All,
                'title' => 'Rethinking AI’s impact on the future of work',
                'excerpt' => 'ILO analysis argues that outcomes depend on institutions and job redesign, not technological capability alone.',
                'source_name' => 'International Labour Organization',
                'source_url' => $this->shared->sources()['ilo_future_work'],
                'preview_image_url' => 'https://www.ilo.org/sites/default/files/2025-11/pexels-olia-danilevich-4974922.jpg',
                'published_at' => CarbonImmutable::parse('2025-11-26 12:00:00', 'UTC'),
            ],
            [
                'locale' => NewsLocale::All,
                'title' => 'European analysis expects uneven employment effects from emerging digital technologies',
                'excerpt' => 'The institutional note describes possible overall employment gains while warning that benefits and transition costs can be unevenly distributed.',
                'source_name' => 'European Commission',
                'source_url' => $this->shared->sources()['eu_employment'],
                'preview_image_url' => 'https://employment-social-affairs.ec.europa.eu/sites/default/files/styles/oe_theme_publication_thumbnail/public/2026-01/Immagine%202026-01-22%20112903.png?itok=Sn3K_dEL',
                'published_at' => CarbonImmutable::parse('2026-01-22 12:00:00', 'UTC'),
            ],
            [
                'locale' => NewsLocale::All,
                'title' => 'Slovak workplace survey exposes a gap between AI-skill need and training',
                'excerpt' => 'The reported survey finds 39% of employees see a need to develop AI skills while 12% trained in the previous year.',
                'source_name' => 'Digital Skills and Jobs Platform',
                'source_url' => $this->shared->sources()['slovakia_skills'],
                'preview_image_url' => 'https://digital-skills-jobs.europa.eu/sites/default/files/dsj_article/2025-04/strach-zo-straty-prace-kvoli-ai.jpg',
                'published_at' => CarbonImmutable::parse('2026-02-05 12:00:00', 'UTC'),
            ],
        ];
    }
};
