<?php

declare(strict_types=1);

use App\Enums\InitiativeLocale;
use App\Enums\InitiativeType;

$shared = require __DIR__.'/../_shared.php';

return new class($shared)
{
    public function __construct(private object $shared) {}

    /** @return array<int, string> */
    public function legacyUrls(): array
    {
        return [$this->shared->sources()['oecd_ai_wips']];
    }

    /** @return array<int, array<string, mixed>> */
    public function initiatives(): array
    {
        return [
            [
                'locale' => InitiativeLocale::All,
                'type' => InitiativeType::Resource,
                'title' => 'ILO Observatory on AI and Work in the Digital Economy',
                'excerpt' => 'A public evidence hub for tracking how AI changes tasks, employment, institutions and worker outcomes.',
                'body' => 'Use the observatory to separate occupational exposure from actual deployment, job quality and labour-market outcomes.',
                'organization' => 'International Labour Organization',
                'url' => $this->shared->sources()['ilo_observatory'],
                'preview_image_url' => 'https://www.ilo.org/sites/default/files/2024-09/ILO%20Observatory%20portal.png',
                'cta_label' => 'Open observatory',
            ],
            [
                'locale' => InitiativeLocale::All,
                'type' => InitiativeType::Resource,
                'title' => 'OECD Skills Outlook 2025',
                'excerpt' => 'The OECD briefing examines how countries can build adaptive, literacy and problem-solving skills for economies transformed by AI and other technologies.',
                'body' => 'Use the briefing as a policy resource for understanding why access to lifelong skills development shapes who can benefit from technological change.',
                'organization' => 'OECD',
                'url' => 'https://www.youtube.com/watch?v=Kq8YKg2rlTM',
                'content_type' => 'youtube_video',
                'external_provider' => 'youtube',
                'external_id' => 'Kq8YKg2rlTM',
                'embed_url' => 'https://www.youtube.com/embed/Kq8YKg2rlTM',
                'preview_image_url' => 'https://i.ytimg.com/vi/Kq8YKg2rlTM/hqdefault.jpg',
                'cta_label' => 'Watch OECD briefing',
            ],
            [
                'locale' => InitiativeLocale::All,
                'type' => InitiativeType::Resource,
                'title' => 'ILO Skills for AI and Digitalization',
                'excerpt' => 'A policy resource focused on equitable access to skills, lifelong learning and digital labour-market transitions.',
                'body' => 'Use the resource to identify training strategies that support augmentation and mobility rather than reactive displacement support alone.',
                'organization' => 'International Labour Organization',
                'url' => $this->shared->sources()['ilo_skills'],
                'preview_image_url' => 'https://www.ilo.org/sites/default/files/2025-01/andy-kelly-0E_vhMVqL9g-unsplash.jpg',
                'cta_label' => 'View skills resources',
            ],
            [
                'locale' => InitiativeLocale::All,
                'type' => InitiativeType::Campaign,
                'title' => 'Pact for Skills',
                'excerpt' => 'European partnerships coordinate public authorities, employers, educators and workers around upskilling and reskilling commitments.',
                'body' => 'The pact provides a framework for sector and regional partnerships responding to digital and green transitions.',
                'organization' => 'European Commission',
                'url' => $this->shared->sources()['pact_skills'],
                'preview_image_url' => 'https://pact-for-skills.ec.europa.eu/sites/default/files/styles/oe_theme_full_width_banner_4_1/public/2022-09/homepage-hero-x2.jpg.webp?itok=Qa0EpA8m',
                'cta_label' => 'Explore the pact',
            ],
            [
                'locale' => InitiativeLocale::All,
                'type' => InitiativeType::Resource,
                'title' => 'AI talent, skills and literacy policy',
                'excerpt' => 'European policy resources on AI literacy, specialist talent and workforce capabilities for responsible adoption.',
                'body' => 'Use the policy page to follow institutional action on AI literacy obligations and broader skills development.',
                'organization' => 'European Commission',
                'url' => $this->shared->sources()['eu_ai_skills'],
                'preview_image_url' => 'https://digital-strategy.ec.europa.eu/sites/default/files/styles/newsroom_thumbnail/public/2025-11/Repository%20of%20AI%20literacy%20practices_logo.jpg?itok=0B-KK7ue',
                'cta_label' => 'Read policy',
            ],
            [
                'locale' => InitiativeLocale::All,
                'type' => InitiativeType::Resource,
                'title' => 'Digital Skills and Jobs: Artificial Intelligence',
                'excerpt' => 'A European portal collecting learning opportunities, policies, research and practices related to AI skills.',
                'body' => 'The portal can help workers, educators and organizations find current training and literacy resources.',
                'organization' => 'European Commission',
                'url' => $this->shared->sources()['digital_skills_ai'],
                'preview_image_url' => 'https://digital-skills-jobs.europa.eu/sites/default/files/inline-images/Artificial%20Intelligence%402_0.png',
                'cta_label' => 'Browse AI skills',
            ],
            [
                'locale' => InitiativeLocale::All,
                'type' => InitiativeType::Resource,
                'title' => 'ARISA Academy AI skills development',
                'excerpt' => 'An educator-focused resource supporting structured learning pathways for AI skills and responsible practice.',
                'body' => 'The academy initiative is relevant to building training capacity before workplace demand outpaces provision.',
                'organization' => 'ARISA Academy',
                'url' => $this->shared->sources()['arisa_academy'],
                'preview_image_url' => 'https://digital-skills-jobs.europa.eu/sites/default/files/styles/oe_theme_medium_no_crop/public/2025-05/ARISA_Empowering%20educators%20webinar.png?itok=EvRGCveZ',
                'cta_label' => 'Open academy resource',
            ],
            [
                'locale' => InitiativeLocale::All,
                'type' => InitiativeType::Campaign,
                'title' => 'Pioneers for Artificial Intelligence',
                'excerpt' => 'A documented European good practice supporting AI literacy, education and practical capability building.',
                'body' => 'Use the initiative as a practical example of AI-skills development rather than evidence of labour-market outcomes.',
                'organization' => 'Digital Skills and Jobs Platform',
                'url' => $this->shared->sources()['ai_pioneers'],
                'preview_image_url' => 'https://digital-skills-jobs.europa.eu/sites/default/files/2026-01/Greek_hero_card_A.jpg',
                'cta_label' => 'View good practice',
            ],
            [
                'locale' => InitiativeLocale::All,
                'type' => InitiativeType::Resource,
                'title' => 'Regional skills partnership for automotive regions in twin transition',
                'excerpt' => 'A sectoral partnership aligning reskilling with technological and industrial change in regions exposed to rapid transition.',
                'body' => 'The partnership illustrates how place-based labour-market institutions can coordinate employers, training providers and public authorities.',
                'organization' => 'Pact for Skills',
                'url' => $this->shared->sources()['automotive_skills'],
                'preview_image_url' => 'https://pact-for-skills.ec.europa.eu/sites/default/files/styles/oe_theme_full_width_banner_4_1/public/2024-06/Group%2011.png.webp?h=33b05ede&itok=bBmosVAg',
                'cta_label' => 'Open partnership',
            ],
        ];
    }
};
