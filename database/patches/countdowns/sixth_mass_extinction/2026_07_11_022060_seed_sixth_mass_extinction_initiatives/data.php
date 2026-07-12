<?php

declare(strict_types=1);

use App\Enums\InitiativeLocale;
use App\Enums\InitiativeType;

$shared = require __DIR__.'/../_shared.php';

return new class($shared)
{
    public function __construct(private object $shared) {}

    /** @return array<int, array<string, mixed>> */
    public function initiatives(): array
    {
        return [
            [
                'locale' => InitiativeLocale::All,
                'type' => InitiativeType::Campaign,
                'title' => 'GBF Target 2: Restore 30% of degraded ecosystems',
                'excerpt' => 'Official 2030 restoration target covering degraded terrestrial, inland-water, marine and coastal ecosystems.',
                'body' => 'Use the CBD guidance to follow the target definition, monitoring indicators and implementation resources without treating the 30% threshold as current observed progress.',
                'organization' => 'Convention on Biological Diversity',
                'url' => $this->shared->sources()['cbd_target_2'],
                'preview_image_url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/b/bf/Mangrove_Planting_Restoration_Project_in_Changkat_Keruing.jpg/1280px-Mangrove_Planting_Restoration_Project_in_Changkat_Keruing.jpg',
                'cta_label' => 'Open Target 2',
            ],
            [
                'locale' => InitiativeLocale::All,
                'type' => InitiativeType::Campaign,
                'title' => 'GBF Target 3: Conserve 30% of land, waters and seas',
                'excerpt' => 'Official 30×30 target emphasizing effective, representative, connected and equitably governed conservation systems.',
                'body' => 'Coverage is only one element: the target also includes biodiversity importance, management effectiveness, connectivity, governance and the rights of Indigenous peoples and local communities.',
                'organization' => 'Convention on Biological Diversity',
                'url' => $this->shared->sources()['cbd_target_3'],
                'preview_image_url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/4/43/Arpa_Protected_Landscape_03.jpg/1280px-Arpa_Protected_Landscape_03.jpg',
                'cta_label' => 'Open Target 3',
            ],
            [
                'locale' => InitiativeLocale::All,
                'type' => InitiativeType::Resource,
                'title' => 'Protected Planet',
                'excerpt' => 'Global platform for official data on protected areas and other effective area-based conservation measures.',
                'body' => 'Use the platform and its report to distinguish coverage from ecological representation, connectivity, management and equitable governance.',
                'organization' => 'UNEP-WCMC and IUCN',
                'url' => $this->shared->sources()['protected_planet'],
                'preview_image_url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/8/8e/S%C3%A4ntisgebiet_protected_area_map.png/1280px-S%C3%A4ntisgebiet_protected_area_map.png',
                'cta_label' => 'Explore protected areas',
            ],
            [
                'locale' => InitiativeLocale::All,
                'type' => InitiativeType::Resource,
                'title' => 'IUCN Red List summary statistics',
                'excerpt' => 'Official statistics for assessed taxonomic groups, including the denominator needed to interpret threatened-species shares.',
                'body' => 'The Red List is extensive but taxonomically incomplete; use assessed-group percentages rather than extrapolating every listed count to all life on Earth.',
                'organization' => 'International Union for Conservation of Nature',
                'url' => $this->shared->sources()['iucn_summary'],
                'preview_image_url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/5/51/Encephalartos_dolomiticus%2C_Manie_van_der_Schijff_BT.jpg/1280px-Encephalartos_dolomiticus%2C_Manie_van_der_Schijff_BT.jpg',
                'cta_label' => 'View statistics',
            ],
            [
                'locale' => InitiativeLocale::All,
                'type' => InitiativeType::Resource,
                'title' => 'Living Planet Index database',
                'excerpt' => 'Open indicator resource tracking average abundance change in monitored vertebrate populations.',
                'body' => 'The database documents population time series and taxonomic and geographic coverage. Its index is not a census of all wildlife or an absolute extinction count.',
                'organization' => 'Zoological Society of London',
                'url' => $this->shared->sources()['living_planet_index'],
                'preview_image_url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/0/0b/Bird_specimens_at_the_Beaty_Biodiversity_Centre.JPG/1280px-Bird_specimens_at_the_Beaty_Biodiversity_Centre.JPG',
                'cta_label' => 'Explore the index',
            ],
            [
                'locale' => InitiativeLocale::All,
                'type' => InitiativeType::Resource,
                'title' => 'Global Wetland Outlook',
                'excerpt' => 'Global evidence platform on wetland extent, condition, loss and the economic and ecological services wetlands provide.',
                'body' => 'Use the outlook to track global trends while retaining regional and ecosystem-type differences that a single aggregate index cannot show.',
                'organization' => 'Convention on Wetlands',
                'url' => $this->shared->sources()['ramsar_gwo'],
                'preview_image_url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/4/49/Uryunuma_Wetland_Aerial_photograph.1977.jpg/1280px-Uryunuma_Wetland_Aerial_photograph.1977.jpg',
                'cta_label' => 'Open the outlook',
            ],
            [
                'locale' => InitiativeLocale::All,
                'type' => InitiativeType::Campaign,
                'title' => 'UN Decade on Ecosystem Restoration',
                'excerpt' => 'Global initiative for preventing, halting and reversing ecosystem degradation during 2021–2030.',
                'body' => 'The initiative provides implementation resources and restoration examples; project recognition should not be confused with a global percentage of degraded area restored.',
                'organization' => 'United Nations Environment Programme and FAO',
                'url' => $this->shared->sources()['restoration_decade'],
                'preview_image_url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/b/b0/Sheridan_ecosystem_restoration_project_-_USACE-p16021coll7-1936.pdf/page1-1280px-Sheridan_ecosystem_restoration_project_-_USACE-p16021coll7-1936.pdf.jpg',
                'cta_label' => 'Explore restoration',
            ],
            [
                'locale' => InitiativeLocale::All,
                'type' => InitiativeType::Resource,
                'title' => 'Global Biodiversity Information Facility',
                'excerpt' => 'Open infrastructure for species-occurrence data used in biodiversity research, monitoring and assessment.',
                'body' => 'Occurrence records improve evidence coverage but retain sampling, geographic and taxonomic biases that must be considered in analysis.',
                'organization' => 'GBIF',
                'url' => $this->shared->sources()['gbif'],
                'preview_image_url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/3/3c/Sphagnum_Frog_-_Philoria_sphagnicolus.jpg/1280px-Sphagnum_Frog_-_Philoria_sphagnicolus.jpg',
                'cta_label' => 'Explore biodiversity data',
            ],
            [
                'locale' => InitiativeLocale::All,
                'type' => InitiativeType::Resource,
                'title' => 'What is ecosystem restoration?',
                'excerpt' => 'UNEP explains how ecosystem restoration supports biodiversity, climate resilience and human well-being across the UN Decade to 2030.',
                'body' => 'This official explainer introduces restoration as a portfolio of actions across terrestrial, freshwater, coastal and marine ecosystems rather than a single global percentage already achieved.',
                'organization' => 'UN Environment Programme',
                'url' => $this->shared->sources()['ecosystem_restoration_video'],
                'content_type' => 'youtube_video',
                'external_provider' => 'youtube',
                'external_id' => 'XhjN8Xux2I4',
                'embed_url' => 'https://www.youtube.com/embed/XhjN8Xux2I4',
                'preview_image_url' => 'https://i.ytimg.com/vi/XhjN8Xux2I4/hqdefault.jpg',
                'cta_label' => 'Watch the explainer',
            ],
        ];
    }
};
