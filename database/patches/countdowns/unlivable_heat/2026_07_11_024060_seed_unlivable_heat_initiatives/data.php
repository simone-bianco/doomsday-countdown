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
        $sources = $this->shared->sources();

        return [
            [
                'locale' => InitiativeLocale::All,
                'type' => InitiativeType::Resource,
                'title' => 'WHO Heat–Health Action Plans Guidance',
                'excerpt' => 'Evidence-based second-edition guidance for authorities designing, operating and evaluating heat-health action plans.',
                'body' => 'Use the guidance to connect governance, warnings, health-system readiness, communication and protection of vulnerable groups.',
                'organization' => 'World Health Organization Regional Office for Europe',
                'url' => $sources['who_hhap_guidance'],
                'preview_image_url' => 'https://cdn.who.int/media/images/librariesprovider2/default-album/news-images/strengthening-heat-health-action-plans-to-protect-public-health--with-who-guidance-u2741l4-web001167.tmb-1200v.jpg?sfvrsn=b9cb2c36_3',
                'cta_label' => 'Open guidance',
            ],
            [
                'locale' => InitiativeLocale::All,
                'type' => InitiativeType::Resource,
                'title' => 'WHO planning heat-health action',
                'excerpt' => 'WHO’s programme page gathers implementation support for heat preparedness, warning and public-health response.',
                'body' => 'Use this resource to follow WHO heat-health planning work and related policy support.',
                'organization' => 'World Health Organization Regional Office for Europe',
                'url' => $sources['who_hhap_planning'],
                'preview_image_url' => 'https://cdn.who.int/media/images/librariesprovider2/default-album/site-banner-images/activities/planning-heat-health-action.tmb-1200v.jpg?sfvrsn=8ea87bb9_2',
                'cta_label' => 'Explore programme',
            ],
            [
                'locale' => InitiativeLocale::All,
                'type' => InitiativeType::Campaign,
                'title' => 'Early Warnings for All',
                'excerpt' => 'United Nations initiative seeking universal protection through multi-hazard early-warning systems.',
                'body' => 'Heat warnings become actionable when hazard information is connected to public-health and community response.',
                'organization' => 'United Nations',
                'url' => $sources['un_early_warnings'],
                'preview_image_url' => 'https://www.un.org/sites/un2.un.org/files/2021/04/utf-8green_planet.jpg',
                'cta_label' => 'View initiative',
            ],
            [
                'locale' => InitiativeLocale::All,
                'type' => InitiativeType::Campaign,
                'title' => 'United Nations Call to Action on Extreme Heat',
                'excerpt' => 'A global call focused on caring for vulnerable people, protecting workers, strengthening resilience and limiting warming.',
                'body' => 'The call frames extreme heat as a cross-sector risk requiring both mitigation and adaptation.',
                'organization' => 'United Nations',
                'url' => $sources['un_extreme_heat'],
                'preview_image_url' => 'https://www.un.org/sites/un2.un.org/files/2024/07/1350x500_heat_hero_notext.png',
                'cta_label' => 'Read call to action',
            ],
            [
                'locale' => InitiativeLocale::All,
                'type' => InitiativeType::Resource,
                'title' => 'EU Mission on Adaptation to Climate Change',
                'excerpt' => 'European support platform helping regions and local authorities build resilience to climate impacts, including heat.',
                'body' => 'The mission offers knowledge, tools and community support for regional and local adaptation pathways.',
                'organization' => 'European Commission',
                'url' => $sources['eu_adaptation_mission'],
                'preview_image_url' => 'https://mission-adaptation-portal.ec.europa.eu/sites/default/files/styles/oe_theme_ratio_3_2_medium/public/2026-06/heatwave.png?h=201b36ac&itok=JCRNrp-r',
                'cta_label' => 'Open mission portal',
            ],
            [
                'locale' => InitiativeLocale::All,
                'type' => InitiativeType::Resource,
                'title' => 'Climate-ADAPT Heat–Health Action Plans',
                'excerpt' => 'European Climate and Health Observatory resource comparing national heat-health policies and planning approaches.',
                'body' => 'Use the policy overview to examine how European countries organize warnings and health protection.',
                'organization' => 'European Environment Agency and European Commission',
                'url' => $sources['climate_adapt_hhap'],
                'preview_image_url' => 'https://climate-adapt.eea.europa.eu/en/observatory/policy/national-policies/heat-health-action-plans/@@images/image-800-1bdb1ee1fd8b2eb9b6ec3551ceb32a62.png',
                'cta_label' => 'Compare policies',
            ],
            [
                'locale' => InitiativeLocale::All,
                'type' => InitiativeType::Campaign,
                'title' => 'Keeping cool in a warming world | Transforming Cities',
                'excerpt' => 'C40 Cities explains how better cooling systems and urban action can reduce heat exposure while avoiding additional climate pollution.',
                'body' => 'This Transforming Cities video connects efficient cooling, city planning and climate resilience in a warming world.',
                'organization' => 'C40 Cities',
                'url' => 'https://www.youtube.com/watch?v=KdI5c8f8hZI',
                'content_type' => 'youtube_video',
                'external_provider' => 'youtube',
                'external_id' => 'KdI5c8f8hZI',
                'embed_url' => 'https://www.youtube.com/embed/KdI5c8f8hZI',
                'preview_image_url' => 'https://i.ytimg.com/vi/KdI5c8f8hZI/hqdefault.jpg',
                'cta_label' => 'Watch video',
            ],
            [
                'locale' => InitiativeLocale::All,
                'type' => InitiativeType::Resource,
                'title' => 'Global Heat Health Information Network action-plan library',
                'excerpt' => 'A practical collection of heat action plans and case studies from cities and countries around the world.',
                'body' => 'Use the library to compare governance, warning thresholds, communications and interventions across contexts.',
                'organization' => 'Global Heat Health Information Network',
                'url' => $sources['ghhin_action_plans'],
                'preview_image_url' => 'https://heathealth.info/wp-content/uploads/Varanasi-Heat-Action-Plan-MHT-NRDC-IIPHG-scaled.jpg',
                'cta_label' => 'Browse action plans',
            ],
        ];
    }

    /** @return array<int, string> */
    public function legacyUrls(): array
    {
        return [$this->shared->sources()['c40_cool_cities']];
    }
};
