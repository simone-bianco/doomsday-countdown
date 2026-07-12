<?php

declare(strict_types=1);

use App\Enums\InitiativeLocale;
use App\Enums\InitiativeType;

$shared = require __DIR__.'/../_shared.php';

return new class($shared)
{
    public function __construct(private object $shared) {}

    /** @return list<array<string, mixed>> */
    public function initiatives(): array
    {
        return [
            $this->resource(
                title: 'White Paper for European Defence – Readiness 2030',
                excerpt: 'The central EU policy framework for capability priorities, industrial readiness, military mobility and joint action through 2030.',
                organization: 'European Commission',
                url: $this->shared->sources()['white_paper'],
                previewImageUrl: 'https://defence-industry-space.ec.europa.eu/sites/default/files/2025-12/capabilityu.png',
                cta: 'Open White Paper',
            ),
            $this->resource(
                title: 'Security Action for Europe (SAFE)',
                excerpt: 'Official information on up to €150 billion in EU-backed loans supporting rapid defence investment through common procurement.',
                organization: 'Council of the European Union',
                url: $this->shared->sources()['safe_policy'],
                previewImageUrl: 'https://defence-industry-space.ec.europa.eu/sites/default/files/2025-07/safe-banner.jpeg',
                cta: 'Explore SAFE',
            ),
            $this->resource(
                title: 'Act in Support of Ammunition Production (ASAP)',
                excerpt: 'Programme information on European ammunition-production bottlenecks, funded capacity expansion and security of supply.',
                organization: 'European Commission',
                url: $this->shared->sources()['asap'],
                previewImageUrl: 'https://defence-industry-space.ec.europa.eu/sites/default/files/2024-03/ASAP_1.png',
                cta: 'Explore ASAP',
            ),
            $this->resource(
                title: 'European Defence Fund',
                excerpt: 'Funding framework for collaborative defence research and capability-development projects across participating countries.',
                organization: 'European Commission',
                url: $this->shared->sources()['edf'],
                previewImageUrl: 'https://defence-industry-space.ec.europa.eu/sites/default/files/2024-03/EDF_2.png',
                cta: 'Open fund portal',
            ),
            $this->resource(
                title: 'Permanent Structured Cooperation (PESCO)',
                excerpt: 'Official catalogue of multinational capability, training, cyber, maritime, land, air and strategic-enabler projects.',
                organization: 'Council of the European Union',
                url: $this->shared->sources()['pesco'],
                previewImageUrl: 'https://www.pesco.europa.eu/wp-content/uploads/2026/05/Pesco-cover.png',
                cta: 'Open PESCO',
            ),
            $this->resource(
                title: 'Future of European defence',
                excerpt: 'Commission overview connecting Readiness 2030, financing, joint spending and delivery milestones.',
                organization: 'European Commission',
                url: $this->shared->sources()['future_defence'],
                previewImageUrl: 'https://commission.europa.eu/sites/default/files/styles/ewcms_metatag_image/public/2025-03/radar-johnny.png?h=34e43602&itok=buafb0VE',
                cta: 'View roadmap',
            ),
            $this->resource(
                title: 'European Defence Industry Programme (EDIP)',
                excerpt: 'EU-wide industrial programme supporting production capacity, resilience and reliable supply for member-state armed forces.',
                organization: 'European Commission',
                url: $this->shared->sources()['edip'],
                previewImageUrl: 'https://defence-industry-space.ec.europa.eu/sites/default/files/2024-03/EDIP_0.png',
                cta: 'Explore EDIP',
            ),
            [
                'locale' => InitiativeLocale::All,
                'type' => InitiativeType::Resource,
                'title' => 'EU Defence Declassified: Defence Data',
                'excerpt' => 'The European Defence Agency explains how comparable defence-spending data supports decisions on European capability and readiness.',
                'body' => 'This official European Defence Agency video introduces the defence-data evidence used to track expenditure, investment and strategic autonomy.',
                'organization' => 'European Defence Agency',
                'url' => 'https://www.youtube.com/watch?v=sHfGbOGxXYg',
                'content_type' => 'youtube_video',
                'preview_image_url' => 'https://i.ytimg.com/vi/sHfGbOGxXYg/hqdefault.jpg',
                'embed_url' => 'https://www.youtube.com/embed/sHfGbOGxXYg',
                'external_provider' => 'youtube',
                'external_id' => 'sHfGbOGxXYg',
                'cta_label' => 'Watch briefing',
            ],
        ];
    }

    /** @return array<string, mixed> */
    private function resource(string $title, string $excerpt, string $organization, string $url, string $previewImageUrl, string $cta): array
    {
        return [
            'locale' => InitiativeLocale::All,
            'type' => InitiativeType::Resource,
            'title' => $title,
            'excerpt' => $excerpt,
            'body' => $excerpt.' This is an official primary resource used to verify delivery milestones and distinguish observed policy from editorial scenarios.',
            'organization' => $organization,
            'url' => $url,
            'content_type' => 'article',
            'preview_image_url' => $previewImageUrl,
            'cta_label' => $cta,
        ];
    }
};
