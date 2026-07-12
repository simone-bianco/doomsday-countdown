<?php

declare(strict_types=1);

use App\Enums\NewsLocale;
use Carbon\CarbonImmutable;

$shared = require __DIR__.'/../_shared.php';

return new class($shared)
{
    public function __construct(private object $shared) {}

    /** @return array<int, string> */
    public function obsoleteSourceUrls(): array
    {
        return [
            $this->shared->sources()['who_resistance_news_2025'],
            $this->shared->sources()['who_gap_2026'],
            $this->shared->sources()['oecd_one_health'],
        ];
    }

    /** @return array<int, array<string, mixed>> */
    public function news(): array
    {
        return [
            $this->youtubeNews(
                id: '1a4ywWibiq8',
                title: 'Antimicrobial Resistance — Act Now: Protect Our Present, Secure Our Future',
                excerpt: 'WHO explains how antimicrobial resistance makes infections harder to treat and why coordinated action is needed across prevention, access, stewardship and innovation.',
                sourceName: 'World Health Organization',
            ),
            $this->youtubeNews(
                id: 'fsCSNGh5gF0',
                title: 'AMR is invisible, I am not: stories from antimicrobial-resistance survivors',
                excerpt: 'Members of WHO’s Task Force of AMR Survivors share lived experience of resistant infections and call for stronger prevention, diagnosis, treatment access and public action.',
                sourceName: 'World Health Organization',
            ),
            [
                'locale' => NewsLocale::All,
                'title' => 'WHO reports a smaller clinical antibacterial pipeline in 2025',
                'excerpt' => 'WHO counted 90 antibacterial candidates in clinical development, split between 50 traditional and 40 non-traditional approaches, down from 97 candidates in 2023.',
                'source_name' => 'World Health Organization',
                'source_url' => $this->shared->sources()['who_pipeline_2025'],
                'preview_image_url' => 'https://iris.who.int/server/api/core/bitstreams/19d9a55e-a2a2-4837-bedb-9ae321ead50b/content',
                'published_at' => CarbonImmutable::parse('2025-10-02 12:00:00', 'UTC'),
            ],
            [
                'locale' => NewsLocale::All,
                'title' => 'World leaders commit to decisive action on antimicrobial resistance',
                'excerpt' => 'The 2024 UN political declaration set a 2030 goal to reduce estimated deaths associated with bacterial AMR by 10% and strengthen financing and national action.',
                'source_name' => 'World Health Organization',
                'source_url' => $this->shared->sources()['who_un_declaration_2024'],
                'preview_image_url' => 'https://cdn.who.int/media/images/default-source/imported/un-building-new-york.tmb-1200v.jpg?sfvrsn=ae7f0114_15',
                'published_at' => CarbonImmutable::parse('2024-09-26 12:00:00', 'UTC'),
            ],
            [
                'locale' => NewsLocale::All,
                'title' => 'ECDC: Europe must act rather than react on antimicrobial resistance',
                'excerpt' => 'ECDC outlines the need for faster prevention, infection control, prudent use, surveillance and innovation to reverse resistant-infection trends in the European Union.',
                'source_name' => 'European Centre for Disease Prevention and Control',
                'source_url' => $this->shared->sources()['ecdc_action_2025'],
                'preview_image_url' => 'https://www.ecdc.europa.eu/sites/default/files/styles/metatag_image_large/public/images/Healthcare-professionals-5_H.png?itok=8CJFy8ME',
            ],
            [
                'locale' => NewsLocale::All,
                'title' => 'EARS-Net 2024 tracks resistant bloodstream infections across the EU/EEA',
                'excerpt' => 'The annual report provides comparable bloodstream-infection incidence indicators, including third-generation-cephalosporin-resistant E. coli and carbapenem-resistant K. pneumoniae.',
                'source_name' => 'European Centre for Disease Prevention and Control',
                'source_url' => $this->shared->sources()['ecdc_ears_2024'],
                'preview_image_url' => 'https://www.ecdc.europa.eu/sites/default/files/styles/metatag_image_large/public/images/generic-cover-aers.png?itok=yoWFF7_3',
            ],
            [
                'locale' => NewsLocale::All,
                'title' => 'ESAC-Net 2024 reports antibiotic consumption and Access share',
                'excerpt' => 'The EU/EEA report records community systemic antibacterial consumption at 18.8 DDD per 1,000 inhabitants per day and an Access share of 60.3%.',
                'source_name' => 'European Centre for Disease Prevention and Control',
                'source_url' => $this->shared->sources()['ecdc_esac_2024'],
                'preview_image_url' => 'https://www.ecdc.europa.eu/sites/default/files/styles/metatag_image_large/public/images/ESAC-Net-AER-Cover.png?itok=xpewwXjX',
            ],
            [
                'locale' => NewsLocale::All,
                'title' => 'Accelerated efforts are needed to meet EU antimicrobial-resistance targets',
                'excerpt' => 'ECDC’s target review highlights persistent gaps in resistant-infection incidence, total antibiotic consumption and the share of WHO Access antibiotics.',
                'source_name' => 'European Centre for Disease Prevention and Control',
                'source_url' => $this->shared->sources()['ecdc_targets'],
                'preview_image_url' => 'https://www.ecdc.europa.eu/sites/default/files/styles/metatag_image_large/public/images/8.png?itok=iAqvxKGe',
            ],
            [
                'locale' => NewsLocale::All,
                'title' => 'European agencies link lower antibiotic use with lower resistance',
                'excerpt' => 'The JIACRA One Health analysis from EMA, ECDC and EFSA reinforces that reducing unnecessary antimicrobial use in humans and food-producing animals can reduce resistance.',
                'source_name' => 'European Medicines Agency',
                'source_url' => $this->shared->sources()['ema_jiacra'],
                'preview_image_url' => 'https://www.ema.europa.eu/sites/default/files/styles/ema_smp_opengraph/public/2024-02/2024-02_JIACRA%20report-3-_WEB_Square.png.webp?itok=4_gs8TWH',
            ],
            $this->youtubeNews(
                id: 'QWHcRO9Is4A',
                title: 'Antimicrobial resistance targets: how can Europe reach them by 2030?',
                excerpt: 'ECDC’s European Antibiotic Awareness Day event brings experts together to discuss measurable 2030 targets for antibiotic use and resistant infections.',
                sourceName: 'European Centre for Disease Prevention and Control',
            ),
            [
                'locale' => NewsLocale::All,
                'title' => 'WHO reviews the antibacterial development pipeline in 2024',
                'excerpt' => 'WHO’s 2024 pipeline update warns that too few innovative agents address priority pathogens and that access and stewardship must accompany development.',
                'source_name' => 'World Health Organization',
                'source_url' => $this->shared->sources()['who_pipeline_2024'],
                'preview_image_url' => 'https://cdn.who.int/media/images/default-source/antimicrobial-resistance/malaria-medication-pakistan-2023.tmb-1200v.jpg?sfvrsn=37d26d89_6',
                'published_at' => CarbonImmutable::parse('2024-06-14 12:00:00', 'UTC'),
            ],
            [
                'locale' => NewsLocale::All,
                'title' => 'WHO antimicrobial resistance fact sheet explains the global threat',
                'excerpt' => 'The fact sheet distinguishes antimicrobial resistance from treatment failure caused by other factors and summarizes surveillance, access, prevention and innovation priorities.',
                'source_name' => 'World Health Organization',
                'source_url' => $this->shared->sources()['who_amr_fact_sheet'],
                'preview_image_url' => 'https://cdn.who.int/media/images/default-source/amr-lib/blink-shoot-malbran_sarah-pabst_00285.tmb-1200v.jpg?sfvrsn=dd75ea20_4',
            ],
        ];
    }

    /** @return array<string, mixed> */
    private function youtubeNews(string $id, string $title, string $excerpt, string $sourceName): array
    {
        return [
            'locale' => NewsLocale::All,
            'title' => $title,
            'excerpt' => $excerpt,
            'content_type' => 'youtube_video',
            'source_name' => $sourceName,
            'source_url' => 'https://www.youtube.com/watch?v='.$id,
            'external_provider' => 'youtube',
            'external_id' => $id,
            'embed_url' => 'https://www.youtube.com/embed/'.$id,
            'preview_image_url' => 'https://i.ytimg.com/vi/'.$id.'/hqdefault.jpg',
        ];
    }
};
