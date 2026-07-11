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
                'type' => InitiativeType::Resource,
                'title' => 'Whole-of-Society Defense Resilience Committee',
                'excerpt' => 'Taiwan presidential committee coordinating civil resilience, continuity and whole-of-society defense planning.',
                'body' => 'Use this source to track official resilience framing, committee priorities and public preparedness language.',
                'organization' => 'Office of the President, Taiwan',
                'url' => $this->shared->sources()['whole_society'],
                'preview_image_url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/9/94/Taipei_Taiwan_Presidential-Office-Building-01.jpg/1280px-Taipei_Taiwan_Presidential-Office-Building-01.jpg',
                'cta_label' => 'Open committee',
            ],
            [
                'locale' => InitiativeLocale::All,
                'type' => InitiativeType::Resource,
                'title' => 'In Case of Crisis: Taiwan Public Safety Guide',
                'excerpt' => 'Official public safety guide for households and communities preparing for emergencies and coercive pressure.',
                'body' => 'The guide is relevant to civil readiness, sheltering, communications and continuity behavior under crisis.',
                'organization' => 'Taiwan Ministry of National Defense',
                'url' => $this->shared->sources()['public_safety_guide'],
                'preview_image_url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/1/14/Seal_of_the_Ministry_of_National_Defense_of_the_Republic_of_China.svg/1280px-Seal_of_the_Ministry_of_National_Defense_of_the_Republic_of_China.svg.png',
                'cta_label' => 'Open guide',
            ],
            [
                'locale' => InitiativeLocale::All,
                'type' => InitiativeType::Campaign,
                'title' => '2026 Urban Resilience Exercises',
                'excerpt' => 'Exercises focused on city-level resilience and civil response under multi-domain crisis pressure.',
                'body' => 'Use this source to track how Taiwan drills city services, local response and continuity under stress.',
                'organization' => 'All-Out Defense Mobilization Agency',
                'url' => $this->shared->sources()['urban_resilience'],
                'preview_image_url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/6/61/Wan-an_Air_Raid_Drill_No._37%2C_Dalin%2C_Chiayi_%28Taiwan%29.jpg/1280px-Wan-an_Air_Raid_Drill_No._37%2C_Dalin%2C_Chiayi_%28Taiwan%29.jpg',
                'cta_label' => 'View exercises',
            ],
            [
                'locale' => InitiativeLocale::All,
                'type' => InitiativeType::Resource,
                'title' => 'Kuma Academy civil-defense education',
                'excerpt' => 'Civil-defense education network focused on public awareness, resilience and preparedness skills.',
                'body' => 'Kuma Academy is a public civil-society resource for non-government preparedness education.',
                'organization' => 'Kuma Academy',
                'url' => $this->shared->sources()['kuma'],
                'preview_image_url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/5/5e/Dr_Puma_Shen_at_Kuma_Academy_Lecture_24_Feb_2023.jpg/1280px-Dr_Puma_Shen_at_Kuma_Academy_Lecture_24_Feb_2023.jpg',
                'cta_label' => 'Open academy',
            ],
            [
                'locale' => InitiativeLocale::All,
                'type' => InitiativeType::Resource,
                'title' => 'G7 geopolitical statement on Taiwan Strait stability',
                'excerpt' => 'G7 leaders emphasized peace and stability across the Taiwan Strait as a geopolitical priority.',
                'body' => 'Use this source to track allied diplomatic signaling around deterrence and crisis response.',
                'organization' => 'G7',
                'url' => $this->shared->sources()['g7_statement'],
                'preview_image_url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/b/bd/52nd_G7_summit_family_photo.jpg/1280px-52nd_G7_summit_family_photo.jpg',
                'cta_label' => 'Read statement',
            ],
            [
                'locale' => InitiativeLocale::All,
                'type' => InitiativeType::Resource,
                'title' => 'Quad foreign ministers’ statement',
                'excerpt' => 'Regional partners reiterated support for a free, open Indo-Pacific and stability in maritime flashpoints.',
                'body' => 'Use this source to track diplomatic alignment and deterrence language among Quad partners.',
                'organization' => 'Quad',
                'url' => $this->shared->sources()['quad_statement'],
                'preview_image_url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/d/db/Quadrilateral_Security_Dialogue_Countries.svg/1280px-Quadrilateral_Security_Dialogue_Countries.svg.png',
                'cta_label' => 'Read statement',
            ],
            [
                'locale' => InitiativeLocale::It,
                'type' => InitiativeType::Resource,
                'title' => 'Guida pubblica di sicurezza in caso di crisi',
                'excerpt' => 'Versione localizzata del riferimento operativo per seguire preparazione civile e comunicazioni di emergenza.',
                'body' => 'Riga italiana dedicata per mostrare la tab iniziative localizzata senza cambiare schema dati.',
                'organization' => 'Ministero della Difesa di Taiwan',
                'url' => $this->shared->sources()['public_safety_guide'],
                'preview_image_url' => 'https://upload.wikimedia.org/wikipedia/commons/0/06/ROC_Ministry_of_National_Defense_press_conference_20130326.jpg',
                'cta_label' => 'Apri guida',
            ],
            [
                'locale' => InitiativeLocale::All,
                'type' => InitiativeType::Resource,
                'title' => 'Taiwan’s Initiatives for Civil Defense amid an Increasing Threat Environment',
                'excerpt' => 'A Global Taiwan Institute panel surveys grassroots resilience, civil-defense education and coordination challenges in Taiwan.',
                'body' => 'The discussion provides a long-form resource on how government and civil-society initiatives contribute to Taiwan’s preparedness.',
                'organization' => 'Global Taiwan Institute',
                'url' => 'https://www.youtube.com/watch?v=zV6YTjz0mbQ',
                'content_type' => 'youtube_video',
                'external_provider' => 'youtube',
                'external_id' => 'zV6YTjz0mbQ',
                'embed_url' => 'https://www.youtube.com/embed/zV6YTjz0mbQ',
                'preview_image_url' => 'https://i.ytimg.com/vi/zV6YTjz0mbQ/hqdefault.jpg',
                'cta_label' => 'Watch panel',
            ],
        ];
    }
};
