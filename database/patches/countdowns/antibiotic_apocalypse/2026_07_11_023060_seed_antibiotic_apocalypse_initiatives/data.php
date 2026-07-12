<?php

declare(strict_types=1);

use App\Enums\InitiativeLocale;
use App\Enums\InitiativeType;

$shared = require __DIR__.'/../_shared.php';

return new class($shared)
{
    public function __construct(private object $shared) {}

    /** @return array<int, string> */
    public function obsoleteUrls(): array
    {
        return [$this->shared->sources()['one_health_plan']];
    }

    /** @return array<int, array<string, mixed>> */
    public function initiatives(): array
    {
        return [
            [
                'locale' => InitiativeLocale::All,
                'type' => InitiativeType::Resource,
                'title' => 'GLASS antimicrobial resistance and use surveillance',
                'excerpt' => 'WHO’s global surveillance system standardizes reporting of antimicrobial resistance and antimicrobial use so countries can identify gaps and compare compatible indicators.',
                'body' => 'Use GLASS to follow surveillance participation, pathogen–drug definitions, specimens, denominators and reporting coverage rather than relying on isolated resistance headlines.',
                'organization' => 'World Health Organization',
                'url' => $this->shared->sources()['who_glass'],
                'preview_image_url' => 'https://cdn.who.int/media/images/default-source/health-topics/antimicrobial-resistance/amr-spc-sel-glass/misc/glassheroimagefinal-centred.tmb-1366v.jpg?sfvrsn=90cea595_2',
                'cta_label' => 'Explore GLASS',
            ],
            [
                'locale' => InitiativeLocale::All,
                'type' => InitiativeType::Campaign,
                'title' => 'WHO AWaRe antibiotic stewardship framework',
                'excerpt' => 'The Access, Watch and Reserve classification supports antibiotic selection, stewardship monitoring and the goal of increasing the share of Access antibiotics.',
                'body' => 'AWaRe provides a common framework for tracking antibiotic use while preserving Reserve agents and improving access to appropriate first- and second-choice treatments.',
                'organization' => 'World Health Organization',
                'url' => $this->shared->sources()['who_aware'],
                'preview_image_url' => 'https://cdn.who.int/media/images/default-source/headquarters/teams/antimicrobial-resistance-division-(amr)/surveillance-prevention-and-control-(spc)/control-and-response-strategies-(csr)/who-aware-classification-of-antibiotics.jpg?sfvrsn=2e9408b7_1',
                'cta_label' => 'Open AWaRe guidance',
            ],
            [
                'locale' => InitiativeLocale::All,
                'type' => InitiativeType::Campaign,
                'title' => 'Global action plan on antimicrobial resistance 2026–2036',
                'excerpt' => 'The updated plan coordinates prevention, equitable access, surveillance, financing, research and accountability across human, animal, plant and environmental health.',
                'body' => 'The plan is the global policy frame behind the countdown’s 2030 horizon and should be read as a programme of action, not a forecast of resistance outcomes.',
                'organization' => 'World Health Organization',
                'url' => $this->shared->sources()['who_gap_2026'],
                'preview_image_url' => 'https://cdn.who.int/media/images/default-source/topics/medicines-medical-devices-and-medical-care/antimicrobial-resistance/wha79-photo.tmb-1200v.jpg?sfvrsn=105370ce_1',
                'cta_label' => 'Read the action-plan update',
            ],
            [
                'locale' => InitiativeLocale::All,
                'type' => InitiativeType::Campaign,
                'title' => 'Global Leaders Group on Antimicrobial Resistance',
                'excerpt' => 'Independent global leaders advocate political action, accountability, financing and a One Health response to antimicrobial resistance.',
                'body' => 'The group follows commitments made by governments and international institutions and promotes sustained leadership beyond single awareness campaigns.',
                'organization' => 'Global Leaders Group on Antimicrobial Resistance',
                'url' => $this->shared->sources()['global_leaders_group'],
                'preview_image_url' => 'https://www.amrleaders.org/images/librariesprovider20/default-album/who-051998.tmb-1200v.jpg?Culture=en&sfvrsn=a5e68d5_2',
                'cta_label' => 'Follow global leadership',
            ],
            $this->youtubeInitiative(
                id: 'jJxqct8kPZw',
                title: 'Stop the spread of antimicrobial resistance',
                excerpt: 'The Quadripartite partners explain why human, animal, food and environmental health must work together to prevent antimicrobial resistance.',
                body: 'This official One Health explainer connects responsible antimicrobial use, prevention and coordinated surveillance across sectors without embedding a player in the application.',
                organization: 'World Organisation for Animal Health (WOAH)',
                ctaLabel: 'Watch the One Health explainer',
            ),
            [
                'locale' => InitiativeLocale::All,
                'type' => InitiativeType::Fundraiser,
                'title' => 'CARB-X antibacterial innovation accelerator',
                'excerpt' => 'CARB-X funds and supports early development of antibiotics, vaccines, diagnostics and other products targeting drug-resistant bacterial infections.',
                'body' => 'Its portfolio addresses the early-stage financing and technical-support gap that prevents promising antibacterial products from reaching clinical development.',
                'organization' => 'Combating Antibiotic-Resistant Bacteria Biopharmaceutical Accelerator',
                'url' => $this->shared->sources()['carb_x'],
                'preview_image_url' => 'https://medicalcountermeasures.gov/media/3zrldow3/cbrn-antibacterials.jpeg',
                'cta_label' => 'Explore the portfolio',
            ],
            [
                'locale' => InitiativeLocale::All,
                'type' => InitiativeType::Resource,
                'title' => 'GARDP treatments and access programmes',
                'excerpt' => 'GARDP develops treatments for drug-resistant infections while planning affordable and sustainable access for populations with the greatest unmet need.',
                'body' => 'The partnership combines research and development with access and stewardship so that new treatments can remain effective and reach patients.',
                'organization' => 'Global Antibiotic Research and Development Partnership',
                'url' => $this->shared->sources()['gardp'],
                'preview_image_url' => 'https://gardp.org/wp-content/uploads/2022/02/cropped-Group-6.png',
                'cta_label' => 'View GARDP programmes',
            ],
            [
                'locale' => InitiativeLocale::All,
                'type' => InitiativeType::Fundraiser,
                'title' => 'Fleming Fund surveillance capacity programme',
                'excerpt' => 'The Fleming Fund supports laboratory, surveillance and workforce capacity for antimicrobial-resistance data in low- and middle-income countries.',
                'body' => 'Reliable surveillance requires laboratory quality, connected information systems and trained people; the fund targets these practical foundations.',
                'organization' => 'Fleming Fund',
                'url' => $this->shared->sources()['fleming_fund'],
                'preview_image_url' => 'https://media.tghn.org/medialibrary/2026/04/FLEMING_FUND_LOGO.png',
                'cta_label' => 'Explore the programme',
            ],
            [
                'locale' => InitiativeLocale::All,
                'type' => InitiativeType::Fundraiser,
                'title' => 'Antimicrobial Resistance Multi-Partner Trust Fund',
                'excerpt' => 'The trust fund finances coordinated One Health action by countries and UN partners, including national plans, surveillance, prevention and stewardship.',
                'body' => 'Pooled financing helps countries turn national action plans into measurable programmes across sectors rather than leaving commitments unfunded.',
                'organization' => 'UN Multi-Partner Trust Fund Office',
                'url' => $this->shared->sources()['amr_mptf'],
                'preview_image_url' => 'https://mptf.undp.org/themes/custom/mptf/assets/img/MPTF-logo.svg',
                'cta_label' => 'Open the fund',
            ],
            [
                'locale' => InitiativeLocale::All,
                'type' => InitiativeType::Campaign,
                'title' => 'SECURE sustainable access to essential antibiotics',
                'excerpt' => 'SECURE works toward reliable access to quality-assured antibiotics while incorporating stewardship and supply sustainability.',
                'body' => 'Access failure and inappropriate use can coexist. SECURE addresses procurement and availability while preserving effective use through stewardship safeguards.',
                'organization' => 'WHO and GARDP',
                'url' => $this->shared->sources()['who_secure'],
                'preview_image_url' => 'https://cdn.who.int/media/images/default-source/2021-dha-images/secureimage.tmb-1920v.jpg?sfvrsn=e86e2fc7_2',
                'cta_label' => 'Learn about SECURE',
            ],
        ];
    }

    /** @return array<string, mixed> */
    private function youtubeInitiative(string $id, string $title, string $excerpt, string $body, string $organization, string $ctaLabel): array
    {
        return [
            'locale' => InitiativeLocale::All,
            'type' => InitiativeType::Campaign,
            'title' => $title,
            'excerpt' => $excerpt,
            'body' => $body,
            'organization' => $organization,
            'url' => 'https://www.youtube.com/watch?v='.$id,
            'content_type' => 'youtube_video',
            'external_provider' => 'youtube',
            'external_id' => $id,
            'embed_url' => 'https://www.youtube.com/embed/'.$id,
            'preview_image_url' => 'https://i.ytimg.com/vi/'.$id.'/hqdefault.jpg',
            'cta_label' => $ctaLabel,
        ];
    }
};
