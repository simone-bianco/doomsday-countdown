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
            ['locale' => InitiativeLocale::All, 'type' => InitiativeType::Resource, 'title' => 'Whole-of-Society Defense Resilience Committee', 'excerpt' => 'Taiwan presidential committee coordinating civil resilience, continuity and whole-of-society defense planning.', 'body' => 'Use this source to track official resilience framing, committee priorities and public preparedness language.', 'organization' => 'Office of the President, Taiwan', 'url' => $this->shared->sources()['whole_society'], 'cta_label' => 'Open committee'],
            ['locale' => InitiativeLocale::All, 'type' => InitiativeType::Resource, 'title' => 'In Case of Crisis: Taiwan Public Safety Guide', 'excerpt' => 'Official public safety guide for households and communities preparing for emergencies and coercive pressure.', 'body' => 'The guide is relevant to civil readiness, sheltering, communications and continuity behavior under crisis.', 'organization' => 'Taiwan Ministry of National Defense', 'url' => $this->shared->sources()['public_safety_guide'], 'cta_label' => 'Open guide'],
            ['locale' => InitiativeLocale::All, 'type' => InitiativeType::Campaign, 'title' => '2026 Urban Resilience Exercises', 'excerpt' => 'Exercises focused on city-level resilience and civil response under multi-domain crisis pressure.', 'body' => 'Use this source to track how Taiwan drills city services, local response and continuity under stress.', 'organization' => 'All-Out Defense Mobilization Agency', 'url' => $this->shared->sources()['urban_resilience'], 'cta_label' => 'View exercises'],
            ['locale' => InitiativeLocale::All, 'type' => InitiativeType::Resource, 'title' => 'Kuma Academy civil-defense education', 'excerpt' => 'Civil-defense education network focused on public awareness, resilience and preparedness skills.', 'body' => 'Kuma Academy is a public civil-society resource for non-government preparedness education.', 'organization' => 'Kuma Academy', 'url' => $this->shared->sources()['kuma'], 'cta_label' => 'Open academy'],
            ['locale' => InitiativeLocale::All, 'type' => InitiativeType::Resource, 'title' => 'G7 geopolitical statement on Taiwan Strait stability', 'excerpt' => 'G7 leaders emphasized peace and stability across the Taiwan Strait as a geopolitical priority.', 'body' => 'Use this source to track allied diplomatic signaling around deterrence and crisis response.', 'organization' => 'G7', 'url' => $this->shared->sources()['g7_statement'], 'cta_label' => 'Read statement'],
            ['locale' => InitiativeLocale::All, 'type' => InitiativeType::Resource, 'title' => 'Quad foreign ministers’ statement', 'excerpt' => 'Regional partners reiterated support for a free, open Indo-Pacific and stability in maritime flashpoints.', 'body' => 'Use this source to track diplomatic alignment and deterrence language among Quad partners.', 'organization' => 'Quad', 'url' => $this->shared->sources()['quad_statement'], 'cta_label' => 'Read statement'],
            ['locale' => InitiativeLocale::It, 'type' => InitiativeType::Resource, 'title' => 'Guida pubblica di sicurezza in caso di crisi', 'excerpt' => 'Versione localizzata del riferimento operativo per seguire preparazione civile e comunicazioni di emergenza.', 'body' => 'Riga italiana dedicata per mostrare la tab iniziative localizzata senza cambiare schema dati.', 'organization' => 'Ministero della Difesa di Taiwan', 'url' => $this->shared->sources()['public_safety_guide'], 'cta_label' => 'Apri guida'],
        ];
    }
};
