<?php

declare(strict_types=1);

use App\Enums\ProjectionType;
use Carbon\CarbonImmutable;

$shared = require __DIR__.'/../_shared.php';

return new class($shared)
{
    public function __construct(private object $shared) {}

    /** @return array{pessimistic: string, neutral: string, optimistic: string} */
    public function targetDates(): array
    {
        return [
            'pessimistic' => '2027-12-02 23:59:59',
            'neutral' => '2030-12-31 23:59:59',
            'optimistic' => '2035-12-31 23:59:59',
        ];
    }

    /** @return array<string, array<string, mixed>> */
    public function milestoneMatrix(): array
    {
        $sources = $this->shared->sources();

        return [
            'pessimistic' => [
                'target_date' => $this->targetDates()['pessimistic'],
                'milestone' => '2 December 2027: EU AI Act rules for high-risk AI systems used in employment apply.',
                'semantics' => 'Crossing this date triggers an evidence review of whether rapid deployment, weak reskilling and incomplete enforcement have produced concentrated displacement or bargaining pressure; it does not declare a job apocalypse.',
                'drivers' => ['enterprise AI deployment', 'skilled-labour shortages', 'occupational exposure', 'employment-rule enforcement capacity'],
                'reasoning' => 'This is the earliest binding labour-market governance checkpoint in the evidence set. Eurostat measured AI use in 20.0% of EU enterprises with at least 10 employees in 2025, while the EIB survey reported 37% of EU firms deploying generative AI and widespread skilled-labour shortages. If deployment and workflow redesign continue faster than training, worker participation and enforcement capacity, concentrated displacement and bargaining pressure could become materially visible by the 2027 compliance checkpoint.',
                'limits' => [
                    'The regulatory date is EU-specific and does not predict global employment outcomes.',
                    'Application of a rule is not proof that displacement has occurred or that safeguards are effective.',
                    'Enterprise adoption rates do not measure the share of jobs automated.',
                ],
                'stop_conditions' => [
                    'The legal application date for employment high-risk systems is materially amended.',
                    'Comparable adoption evidence shows a sustained reversal rather than continued deployment.',
                    'Institutional labour-market evidence no longer supports an early adjustment-risk checkpoint.',
                ],
                'sources' => [
                    $sources['eu_ai_act'],
                    $sources['eurostat_2025'],
                    $sources['eibis_2025'],
                    $sources['ilo_index'],
                ],
            ],
            'neutral' => [
                'target_date' => $this->targetDates()['neutral'],
                'milestone' => '31 December 2030: EU Digital Decade adoption and skills targets, aligned with the OECD scenario horizon through 2030.',
                'semantics' => 'Crossing this date triggers the central comparison of observed adoption, skills capacity, automation, augmentation, displacement and job creation against the 2030 institutional targets and scenarios.',
                'drivers' => ['Digital Decade adoption targets', 'digital and AI skills capacity', 'investment cycles', 'OECD AI trajectories'],
                'reasoning' => 'The neutral date is the main timer because 2030 is a synchronized, measurable checkpoint for adoption and workforce capacity rather than a generic round year. The EU Digital Decade targets 75% of firms using cloud, AI or big data, at least 80% of adults with basic digital skills and 20 million ICT specialists. OECD scenario work explicitly evaluates possible AI trajectories through 2030. Together these milestones provide a central date for testing whether augmentation, training and job creation are keeping pace with automation and exposure.',
                'limits' => [
                    'Digital Decade targets combine cloud, AI and big data and therefore are not a pure AI adoption forecast.',
                    'OECD trajectories are scenarios, not probabilities or a single official forecast.',
                    'Meeting digital targets does not establish that labour-market gains are evenly distributed.',
                ],
                'stop_conditions' => [
                    'The EU replaces or materially changes the 2030 Digital Decade target horizon.',
                    'OECD or comparable institutional scenario work adopts a materially different central horizon.',
                    'The definitions of adoption or workforce skills become non-comparable with the seeded milestone.',
                ],
                'sources' => [
                    $sources['eu_digital_decade'],
                    $sources['oecd_ai_trajectories_2030'],
                    $sources['eurostat_2025'],
                    $sources['ilo_index'],
                ],
            ],
            'optimistic' => [
                'target_date' => $this->targetDates()['optimistic'],
                'milestone' => '31 December 2035: Cedefop skills-forecast horizon for labour-market absorption and digital-transition adjustment.',
                'semantics' => 'Crossing this date triggers a review of whether longer reskilling, investment and occupational-turnover cycles have allowed augmentation and new work to absorb a material share of displacement.',
                'drivers' => ['reskilling and lifelong learning', 'job creation in AI infrastructure and R&D', 'occupational turnover', 'productivity and investment absorption'],
                'reasoning' => 'The optimistic date allows a longer investment, training and occupational-turnover cycle. Cedefop models digital and AI-related labour-market effects through 2035, including both job creation in technology-intensive activities and adjustment losses under faster deployment. US BLS projections through 2033 also show occupation-specific declines and gains rather than one aggregate employment cliff. The later horizon is therefore used to test whether augmentation, reskilling and newly created work can absorb a larger share of disruption.',
                'limits' => [
                    'Cedefop scenarios are modelled European outcomes, while BLS projections cover the United States.',
                    'Forecasts are sensitive to deployment speed, productivity, investment and policy assumptions.',
                    'A later adjustment horizon does not guarantee positive job quality or distributional outcomes.',
                ],
                'stop_conditions' => [
                    'Cedefop or another primary skills forecast moves the relevant absorption horizon materially earlier or later.',
                    'Observed displacement persistently exceeds job creation and reskilling capacity before 2035.',
                    'The 2035 forecast basis is superseded by non-comparable methods or definitions.',
                ],
                'sources' => [
                    $sources['cedefop_ai_2035'],
                    $sources['bls_ai_2033'],
                    $sources['oecd_ai_jobs'],
                    $sources['ilo_skills'],
                ],
            ],
        ];
    }

    /** @return array<int, array<string, mixed>> */
    public function projections(): array
    {
        $dates = $this->targetDates();
        $matrix = $this->milestoneMatrix();

        return [
            [
                'type' => ProjectionType::Optimistic,
                'target_date' => CarbonImmutable::parse($dates['optimistic'], 'UTC'),
                'title' => $this->shared->t(
                    en: 'Augmentation absorbs disruption by 2035',
                    it: 'Il potenziamento assorbe la discontinuità entro il 2035',
                    fr: 'L’augmentation absorbe la perturbation d’ici 2035',
                    de: 'Unterstützung absorbiert den Wandel bis 2035',
                    es: 'El aumento absorbe la disrupción para 2035',
                    nl: 'Ondersteuning vangt de verstoring op tegen 2035',
                    sv: 'Förstärkning absorberar omställningen till 2035',
                    pl: 'Wspomaganie absorbuje zakłócenia do 2035 roku',
                ),
                'summary' => $this->shared->t(
                    en: 'A longer skills, investment and occupational-turnover cycle allows augmentation and new work to absorb much of the transition by the Cedefop 2035 forecast horizon.',
                    it: 'Un ciclo più lungo di competenze, investimenti e ricambio professionale consente a potenziamento e nuovo lavoro di assorbire gran parte della transizione entro l’orizzonte Cedefop 2035.',
                    fr: 'Un cycle plus long de compétences, d’investissement et de renouvellement professionnel permet à l’augmentation et au nouveau travail d’absorber une grande partie de la transition à l’horizon Cedefop 2035.',
                    de: 'Ein längerer Qualifikations-, Investitions- und Berufswechselzyklus lässt Unterstützung und neue Arbeit einen Großteil des Übergangs bis zum Cedefop-Horizont 2035 aufnehmen.',
                    es: 'Un ciclo más largo de competencias, inversión y renovación ocupacional permite que el aumento y el nuevo trabajo absorban gran parte de la transición hacia el horizonte Cedefop 2035.',
                    nl: 'Een langere cyclus van vaardigheden, investeringen en beroepswisseling laat ondersteuning en nieuw werk veel van de overgang opvangen tegen de Cedefop-horizon 2035.',
                    sv: 'En längre cykel för kompetens, investeringar och yrkesomsättning gör att förstärkning och nytt arbete kan absorbera mycket av omställningen till Cedefops horisont 2035.',
                    pl: 'Dłuższy cykl umiejętności, inwestycji i zmian zawodowych pozwala wspomaganiu i nowej pracy wchłonąć dużą część transformacji do horyzontu Cedefop 2035.',
                ),
                'confidence_score' => 55,
                'probability_score' => 0,
                'trend' => 'stable',
                'methodology' => $this->methodology('optimistic', $matrix['optimistic']),
                'sort_order' => 1,
            ],
            [
                'type' => ProjectionType::Neutral,
                'target_date' => CarbonImmutable::parse($dates['neutral'], 'UTC'),
                'title' => $this->shared->t(
                    en: 'Uneven restructuring reaches the 2030 checkpoint',
                    it: 'La ristrutturazione diseguale raggiunge il checkpoint 2030',
                    fr: 'La restructuration inégale atteint le jalon 2030',
                    de: 'Ungleichmäßige Umstrukturierung erreicht den Prüfpunkt 2030',
                    es: 'La reestructuración desigual llega al punto de control de 2030',
                    nl: 'Ongelijke herstructurering bereikt het ijkpunt 2030',
                    sv: 'Ojämn omstrukturering når kontrollpunkten 2030',
                    pl: 'Nierówna restrukturyzacja osiąga punkt kontrolny 2030',
                ),
                'summary' => $this->shared->t(
                    en: 'After the Pessimistic checkpoint expires, the chain advances to the measurable Neutral 31 December 2030 adoption-and-skills checkpoint, where uneven gains and losses can be assessed against EU targets and OECD scenarios.',
                    it: 'Dopo la scadenza del checkpoint Pessimistic, la catena avanza al checkpoint Neutral misurabile del 31 dicembre 2030 su adozione e competenze, dove guadagni e perdite diseguali possono essere valutati rispetto ai target UE e agli scenari OECD.',
                    fr: 'Après l’expiration du jalon Pessimistic, la chaîne avance vers le jalon Neutral mesurable du 31 décembre 2030 sur l’adoption et les compétences, où gains et pertes inégaux peuvent être évalués face aux objectifs de l’UE et aux scénarios de l’OCDE.',
                    de: 'Nach Ablauf des Pessimistic-Prüfpunkts rückt die Kette zum messbaren Neutral-Prüfpunkt am 31. Dezember 2030 für Adoption und Qualifikationen vor, an dem ungleiche Gewinne und Verluste an EU-Zielen und OECD-Szenarien gemessen werden können.',
                    es: 'Tras vencer el punto Pessimistic, la cadena avanza al punto Neutral medible del 31 de diciembre de 2030 sobre adopción y competencias, donde las ganancias y pérdidas desiguales pueden evaluarse frente a objetivos de la UE y escenarios de la OCDE.',
                    nl: 'Na het verstrijken van het Pessimistic-ijkpunt gaat de keten verder naar het meetbare Neutral-ijkpunt van 31 december 2030 voor adoptie en vaardigheden, waar ongelijke winsten en verliezen kunnen worden getoetst aan EU-doelen en OECD-scenario’s.',
                    sv: 'Efter att kontrollpunkten Pessimistic har löpt ut går kedjan vidare till den mätbara Neutral-punkten den 31 december 2030 för adoption och kompetens, där ojämna vinster och förluster kan bedömas mot EU-mål och OECD-scenarier.',
                    pl: 'Po wygaśnięciu punktu Pessimistic łańcuch przechodzi do mierzalnego punktu Neutral 31 grudnia 2030 r. dla wdrożeń i umiejętności, gdzie nierówne zyski i straty można ocenić wobec celów UE i scenariuszy OECD.',
                ),
                'confidence_score' => 70,
                'probability_score' => 0,
                'trend' => 'rising',
                'methodology' => $this->methodology('neutral', $matrix['neutral']),
                'sort_order' => 2,
            ],
            [
                'type' => ProjectionType::Pessimistic,
                'target_date' => CarbonImmutable::parse($dates['pessimistic'], 'UTC'),
                'title' => $this->shared->t(
                    en: 'Adoption outpaces safeguards by the 2027 checkpoint',
                    it: 'L’adozione supera le garanzie entro il checkpoint 2027',
                    fr: 'L’adoption dépasse les garanties au jalon 2027',
                    de: 'Adoption überholt Schutzmaßnahmen bis zum Prüfpunkt 2027',
                    es: 'La adopción supera las salvaguardas en el punto de 2027',
                    nl: 'Adoptie loopt voor op waarborgen bij het ijkpunt 2027',
                    sv: 'Adoption går snabbare än skyddsåtgärder vid kontrollpunkten 2027',
                    pl: 'Wdrożenia wyprzedzają zabezpieczenia do punktu kontrolnego 2027',
                ),
                'summary' => $this->shared->t(
                    en: 'Rapid deployment and persistent skill shortages produce concentrated pressure before employment high-risk AI rules apply on 2 December 2027.',
                    it: 'Adozione rapida e persistenti carenze di competenze producono pressione concentrata prima dell’applicazione delle regole IA ad alto rischio nel lavoro il 2 dicembre 2027.',
                    fr: 'Un déploiement rapide et des pénuries persistantes de compétences créent une pression concentrée avant l’application, le 2 décembre 2027, des règles sur l’IA à haut risque dans l’emploi.',
                    de: 'Schnelle Einführung und anhaltende Qualifikationsengpässe erzeugen konzentrierten Druck, bevor die Regeln für Hochrisiko-KI im Beschäftigungsbereich am 2. Dezember 2027 gelten.',
                    es: 'El despliegue rápido y la escasez persistente de competencias generan presión concentrada antes de que se apliquen el 2 de diciembre de 2027 las reglas de IA de alto riesgo en el empleo.',
                    nl: 'Snelle invoering en aanhoudende vaardigheidstekorten veroorzaken geconcentreerde druk vóór de regels voor hoog-risico-AI in arbeid op 2 december 2027 van toepassing worden.',
                    sv: 'Snabb utrullning och ihållande kompetensbrist skapar koncentrerat tryck innan reglerna för högrisk-AI i arbetslivet börjar gälla den 2 december 2027.',
                    pl: 'Szybkie wdrożenia i trwałe niedobory umiejętności wywołują skoncentrowaną presję, zanim 2 grudnia 2027 r. zaczną obowiązywać przepisy o AI wysokiego ryzyka w zatrudnieniu.',
                ),
                'confidence_score' => 55,
                'probability_score' => 0,
                'trend' => 'rising',
                'methodology' => $this->methodology('pessimistic', $matrix['pessimistic']),
                'sort_order' => 3,
            ],
        ];
    }

    /** @param array<string, mixed> $milestone @return array<string, mixed> */
    private function methodology(string $scenario, array $milestone): array
    {
        return [
            'nature' => 'Editorial temporal milestone, not an outcome probability or official forecast.',
            'assessed_at' => '2026-07-11',
            'scenario' => $scenario,
            'target_date_basis' => $milestone['milestone'],
            'semantics' => $milestone['semantics'],
            'drivers' => $milestone['drivers'],
            'reasoning' => $milestone['reasoning'],
            'limits' => $milestone['limits'],
            'stop_conditions' => $milestone['stop_conditions'],
            'probability_score' => 'Set to zero because no cited institution assigns a point probability to this scenario.',
            'sources' => $milestone['sources'],
        ];
    }
};
