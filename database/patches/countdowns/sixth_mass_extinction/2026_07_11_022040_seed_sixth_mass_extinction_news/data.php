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
        return [
            [
                'locale' => NewsLocale::All,
                'title' => 'Living Planet Index 2024',
                'excerpt' => 'ZSL explains how the Living Planet Index measures average change across monitored vertebrate populations and why it is not a count of all wildlife.',
                'content_type' => 'youtube_video',
                'source_name' => 'ZSL - Zoological Society of London',
                'source_url' => $this->shared->sources()['living_planet_video'],
                'external_provider' => 'youtube',
                'external_id' => 'jmvgQ5fBBLg',
                'embed_url' => 'https://www.youtube.com/embed/jmvgQ5fBBLg',
                'preview_image_url' => 'https://i.ytimg.com/vi/jmvgQ5fBBLg/hqdefault.jpg',
            ],
            [
                'locale' => NewsLocale::All,
                'title' => 'IUCN Red List update: global impacts, regional statuses and the way forward',
                'excerpt' => 'The 2025 IUCN update reviews new assessments, regional variation and the limits that incomplete taxonomic coverage places on global interpretation.',
                'source_name' => 'IUCN',
                'source_url' => $this->shared->sources()['iucn_2025_update'],
                'preview_image_url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/5/55/Cycas_debaoensis_at_BBG_%2850863%29.jpg/1280px-Cycas_debaoensis_at_BBG_%2850863%29.jpg',
            ],
            [
                'locale' => NewsLocale::All,
                'title' => 'Global deforestation slows, but forests remain under pressure',
                'excerpt' => 'FAO’s Global Forest Resources Assessment 2025 reports slower deforestation while warning that millions of hectares are still lost each year.',
                'source_name' => 'Food and Agriculture Organization of the United Nations',
                'source_url' => $this->shared->sources()['fao_fra_2025'],
                'preview_image_url' => 'https://upload.wikimedia.org/wikipedia/commons/5/5c/8_Aerial_view_of_indigenous_forest_canopy_-_N_Plettenberg_bay.jpg',
                'published_at' => CarbonImmutable::parse('2025-10-21 12:00:00', 'UTC'),
            ],
            [
                'locale' => NewsLocale::All,
                'title' => 'FAO and the United Kingdom launch AIM4NatuRe',
                'excerpt' => 'The programme is designed to improve monitoring and reporting for ecosystem restoration, including progress toward the 2030 restoration target.',
                'source_name' => 'Food and Agriculture Organization of the United Nations',
                'source_url' => $this->shared->sources()['fao_aim4nature'],
                'preview_image_url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/b/bf/Mangrove_Planting_Restoration_Project_in_Changkat_Keruing.jpg/1280px-Mangrove_Planting_Restoration_Project_in_Changkat_Keruing.jpg',
                'published_at' => CarbonImmutable::parse('2025-04-22 12:00:00', 'UTC'),
            ],
            [
                'locale' => NewsLocale::All,
                'title' => 'UN awards new World Restoration Flagships',
                'excerpt' => 'Four restoration flagships spanning 18 countries were recognized for work linking ecosystem recovery, food systems and local livelihoods.',
                'source_name' => 'Food and Agriculture Organization of the United Nations',
                'source_url' => $this->shared->sources()['fao_restoration_flagships'],
                'preview_image_url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/d/d6/Mangrove_Planting_Restoration_Project_in_Changkat_Keruing_3.jpg/1280px-Mangrove_Planting_Restoration_Project_in_Changkat_Keruing_3.jpg',
                'published_at' => CarbonImmutable::parse('2025-10-14 12:00:00', 'UTC'),
            ],
            [
                'locale' => NewsLocale::All,
                'title' => 'Protected Planet Report 2024 measures the gap to 30×30',
                'excerpt' => 'The first full global assessment of Target 3 reports 17.6% terrestrial and inland-water coverage and 8.4% marine and coastal coverage.',
                'source_name' => 'UNEP-WCMC and IUCN',
                'source_url' => $this->shared->sources()['protected_planet_report'],
                'preview_image_url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/4/43/Arpa_Protected_Landscape_03.jpg/1280px-Arpa_Protected_Landscape_03.jpg',
            ],
            [
                'locale' => NewsLocale::All,
                'title' => 'Global Wetland Outlook 2025 documents continuing loss',
                'excerpt' => 'The Ramsar outlook estimates that 22% of global wetland extent has been lost since 1970 and that decline continues at roughly 0.52% per year.',
                'source_name' => 'Convention on Wetlands',
                'source_url' => $this->shared->sources()['ramsar_gwo'],
                'preview_image_url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/e/e6/Louisiana_wetlands_aerial_view.jpg/1280px-Louisiana_wetlands_aerial_view.jpg',
            ],
            [
                'locale' => NewsLocale::All,
                'title' => 'Final text of the Kunming–Montreal Global Biodiversity Framework',
                'excerpt' => 'The adopted framework sets 23 targets for 2030, including restoration of 30% of degraded ecosystems and conservation of 30% of land, waters and seas.',
                'source_name' => 'Convention on Biological Diversity',
                'source_url' => $this->shared->sources()['cbd_final_text'],
                'preview_image_url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/f/fd/Diploria_labyrinthiformis_%E2%80%93_Atlantic_reef_corals%2C_a_handbook_of_the_common_reef_and_shallow-water_corals_of_Bermuda%E2%80%A6_%281948%29_%2820155515530%29.jpg/1280px-Diploria_labyrinthiformis_%E2%80%93_Atlantic_reef_corals%2C_a_handbook_of_the_common_reef_and_shallow-water_corals_of_Bermuda%E2%80%A6_%281948%29_%2820155515530%29.jpg',
                'published_at' => CarbonImmutable::parse('2022-12-27 12:00:00', 'UTC'),
            ],
            [
                'locale' => NewsLocale::All,
                'title' => 'IPBES global assessment summarizes nature’s decline',
                'excerpt' => 'The summary for policymakers separates observed ecosystem change from modelled global estimates, including the widely cited estimate of about one million threatened species.',
                'source_name' => 'IPBES',
                'source_url' => $this->shared->sources()['ipbes_spm'],
                'preview_image_url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/0/0b/Bird_specimens_at_the_Beaty_Biodiversity_Centre.JPG/1280px-Bird_specimens_at_the_Beaty_Biodiversity_Centre.JPG',
            ],
            [
                'locale' => NewsLocale::All,
                'title' => 'Second Global Amphibian Assessment finds 41% threatened',
                'excerpt' => 'The IUCN assessment documents amphibians as the most threatened vertebrate class while making its assessed-species denominator explicit.',
                'source_name' => 'IUCN Red List',
                'source_url' => $this->shared->sources()['iucn_amphibians'],
                'preview_image_url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/3/3c/Sphagnum_Frog_-_Philoria_sphagnicolus.jpg/1280px-Sphagnum_Frog_-_Philoria_sphagnicolus.jpg',
                'published_at' => CarbonImmutable::parse('2023-10-04 12:00:00', 'UTC'),
            ],
            [
                'locale' => NewsLocale::All,
                'title' => 'One year in review of the Kunming–Montreal Global Biodiversity Framework',
                'excerpt' => 'The Convention on Biological Diversity reviews the first year of implementation of the global framework and its 2030 targets for halting biodiversity loss.',
                'content_type' => 'youtube_video',
                'source_name' => 'Convention on Biological Diversity',
                'source_url' => $this->shared->sources()['gbf_review_video'],
                'external_provider' => 'youtube',
                'external_id' => 'U6oP6Q-hVQI',
                'embed_url' => 'https://www.youtube.com/embed/U6oP6Q-hVQI',
                'preview_image_url' => 'https://i.ytimg.com/vi/U6oP6Q-hVQI/hqdefault.jpg',
            ],
        ];
    }
};
