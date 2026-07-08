<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\CountdownSeverity;
use App\Enums\CountdownStatus;
use App\Enums\InitiativeLocale;
use App\Enums\InitiativeType;
use App\Enums\NewsLocale;
use App\Enums\ProjectionType;
use App\Enums\VisualizationType;
use App\Models\Countdown;
use Carbon\CarbonImmutable;
use Illuminate\Database\Seeder;

final class DoomsdaySeeder extends Seeder
{
    public function run(): void
    {
        Countdown::query()->delete();

        $countdown = Countdown::query()->create([
            'slug' => 'taiwan-invasion',
            'title' => [
                'en' => 'Taiwan Invasion',
                'it' => 'Invasione di Taiwan',
            ],
            'summary' => [
                'en' => 'China–Taiwan conflict risk and the 2027 readiness window',
                'it' => 'Rischio di conflitto Cina–Taiwan e finestra di prontezza 2027',
            ],
            'description' => [
                'en' => 'A large-scale amphibious invasion of Taiwan is plausible but remains a lower-probability path than blockade, quarantine, cyber pressure, sabotage and other coercive grey-zone operations in the near term. The countdown uses public-source indicators to frame a 2027 readiness window, not a certain event date.',
                'it' => 'Una invasione anfibia su larga scala di Taiwan è plausibile, ma nel breve termine resta meno probabile di blocco, quarantena, pressione cyber, sabotaggio e altre operazioni coercitive grey-zone. Il countdown usa indicatori da fonti pubbliche per inquadrare una finestra di prontezza 2027, non una data certa.',
            ],
            'causes' => [
                'en' => [
                    'PLA modernization and the 2027 readiness marker.',
                    'Grey-zone pressure, coast guard normalization and blockade rehearsal.',
                    'Domestic legitimacy and coercive bargaining risk.',
                    'Taiwan semiconductor concentration and strategic geography.',
                ],
                'it' => [
                    'Modernizzazione del PLA e soglia di prontezza 2027.',
                    'Pressione grey-zone, normalizzazione della guardia costiera e prove di blocco.',
                    'Legittimità interna e rischio di coercizione negoziale.',
                    'Concentrazione dei semiconduttori a Taiwan e geografia strategica.',
                ],
            ],
            'consequences' => [
                'en' => [
                    'Missile, cyber and information opening phase followed by blockade pressure.',
                    'Regional escalation risk involving U.S., Japan and Philippine posture.',
                    'Multi-trillion-dollar trade and semiconductor shock.',
                    'Energy, logistics and civil-resilience stress inside Taiwan.',
                    'Severe humanitarian pressure and displacement risk.',
                ],
                'it' => [
                    'Fase iniziale missilistica, cyber e informativa seguita da pressione di blocco.',
                    'Rischio di escalation regionale con postura di Stati Uniti, Giappone e Filippine.',
                    'Shock da migliaia di miliardi su commercio e semiconduttori.',
                    'Stress energetico, logistico e di resilienza civile a Taiwan.',
                    'Grave pressione umanitaria e rischio di sfollamento.',
                ],
            ],
            'recommended_actions' => [
                'en' => [
                    'Monitor PLA naval, coast guard and ADIZ activity separately from invasion headlines.',
                    'Track Taiwan civil-resilience drills, energy stocks and drone procurement.',
                    'Follow allied deterrence statements, sanctions coordination and evacuation planning.',
                    'Separate invasion risk from blockade, quarantine and coercive pressure scenarios.',
                ],
                'it' => [
                    'Monitorare attività navale, guardia costiera e ADIZ del PLA separandole dai titoli sull’invasione.',
                    'Seguire esercitazioni di resilienza civile, scorte energetiche e acquisti di droni di Taiwan.',
                    'Seguire dichiarazioni alleate di deterrenza, coordinamento sanzioni e piani di evacuazione.',
                    'Separare il rischio di invasione da blocco, quarantena e pressione coercitiva.',
                ],
            ],
            'icon' => 'shield',
            'severity' => CountdownSeverity::Critical,
            'status' => CountdownStatus::Active,
            'target_date' => CarbonImmutable::parse('2027-10-01 00:00:00', 'UTC'),
            'image_path' => 'images/doomsday/taiwan_invasion.png',
            'sort_order' => 1,
            'is_published' => true,
        ]);

        foreach ($this->projections() as $projection) {
            $createdProjection = $countdown->projections()->create($projection);

            if ($projection['type'] === ProjectionType::Neutral) {
                $createdProjection->visualizations()->create([
                    'key' => 'projection_curve',
                    'type' => VisualizationType::Line,
                    'title' => ['en' => 'Risk window curve', 'it' => 'Curva della finestra di rischio'],
                    'description' => ['en' => 'Editorial probability curve for invasion risk, separating full invasion from blockade and quarantine scenarios.', 'it' => 'Curva editoriale di probabilità del rischio invasione, distinta dagli scenari di blocco e quarantena.'],
                    'payload' => [
                        'unit' => '% invasion probability',
                        'labels' => ['2026-Q3', '2027-Q1', '2027-Q4', '2028-Q4', '2029-Q1'],
                        'series' => [
                            ['name' => 'Pessimistic', 'color' => '#ff2a23', 'values' => [18, 24, 28, 24, 20]],
                            ['name' => 'Optimistic', 'color' => '#22c55e', 'values' => [8, 10, 12, 10, 8]],
                            ['name' => 'Neutral', 'color' => '#38bdf8', 'values' => [12, 16, 18, 16, 12]],
                        ],
                        'sources' => [$this->sources()['dod'], $this->sources()['reuters_status_quo']],
                    ],
                    'schema_version' => 1,
                    'sort_order' => 1,
                ]);
            }
        }

        foreach ($this->visualizations() as $visualization) {
            $countdown->visualizations()->create($visualization);
        }

        foreach ($this->news() as $index => $news) {
            $countdown->news()->create(array_merge($news, [
                'image_path' => 'images/doomsday/taiwan_invasion.png',
                'sort_order' => $index + 1,
                'is_featured' => $index === 0,
            ]));
        }

        foreach ($this->initiatives() as $index => $initiative) {
            $countdown->initiatives()->create(array_merge($initiative, [
                'image_path' => 'images/doomsday/taiwan_invasion.png',
                'sort_order' => $index + 1,
                'is_featured' => $index === 0,
            ]));
        }
    }

    /** @return array<int, array<string, mixed>> */
    private function projections(): array
    {
        $methodology = [
            'assumption' => 'Editorial risk estimate as of 2026-07-08; dates are scenario windows, not predictions.',
            'drivers' => ['PLA 2027 readiness marker', 'ADIZ and naval activity', 'blockade and quarantine alternatives', 'Taiwan civil resilience', 'allied deterrence'],
            'sources' => [$this->sources()['dod'], $this->sources()['reuters_status_quo'], $this->sources()['reuters_naval'], $this->sources()['reuters_drone']],
        ];

        return [
            [
                'type' => ProjectionType::Optimistic,
                'target_date' => CarbonImmutable::parse('2029-01-15 00:00:00', 'UTC'),
                'title' => ['en' => 'Deterrence holds', 'it' => 'La deterrenza regge'],
                'summary' => ['en' => 'Coercion remains below full invasion; a major crisis is avoided while grey-zone pressure persists.', 'it' => 'La coercizione resta sotto la soglia dell’invasione; la crisi maggiore viene evitata mentre continua la pressione grey-zone.'],
                'confidence_score' => 54,
                'probability_score' => 12,
                'trend' => 'stable',
                'methodology' => $methodology,
                'sort_order' => 1,
            ],
            [
                'type' => ProjectionType::Neutral,
                'target_date' => CarbonImmutable::parse('2027-10-01 00:00:00', 'UTC'),
                'title' => ['en' => 'Baseline risk window', 'it' => 'Finestra base di rischio'],
                'summary' => ['en' => 'Full amphibious invasion remains lower-probability than blockade or quarantine, but 2027 readiness and grey-zone escalation make the risk non-negligible.', 'it' => 'L’invasione anfibia completa resta meno probabile di blocco o quarantena, ma prontezza 2027 ed escalation grey-zone rendono il rischio non trascurabile.'],
                'confidence_score' => 62,
                'probability_score' => 18,
                'trend' => 'rising',
                'methodology' => $methodology,
                'sort_order' => 2,
            ],
            [
                'type' => ProjectionType::Pessimistic,
                'target_date' => CarbonImmutable::parse('2027-03-15 00:00:00', 'UTC'),
                'title' => ['en' => 'Accelerated crisis', 'it' => 'Crisi accelerata'],
                'summary' => ['en' => 'Missile and cyber opening moves, blockade pressure and coercive operations compress the crisis window; amphibious risk rises if deterrence and civil logistics fail.', 'it' => 'Apertura missilistica e cyber, pressione di blocco e operazioni coercitive comprimono la finestra di crisi; il rischio anfibio cresce se deterrenza e logistica civile falliscono.'],
                'confidence_score' => 48,
                'probability_score' => 28,
                'trend' => 'rising',
                'methodology' => $methodology,
                'sort_order' => 3,
            ],
        ];
    }

    /** @return array<int, array<string, mixed>> */
    private function visualizations(): array
    {
        return [
            [
                'key' => 'key_indicators',
                'type' => VisualizationType::Kpi,
                'title' => ['en' => 'Key indicators', 'it' => 'Indicatori chiave'],
                'description' => ['en' => 'Public-source pressure signals used by the editorial risk model.', 'it' => 'Segnali da fonti pubbliche usati dal modello editoriale di rischio.'],
                'payload' => [
                    'items' => [
                        ['label' => 'PLA ADIZ activity 2024', 'value' => '2,771', 'direction' => 'up', 'sparkline' => [22, 33, 31, 50, 79], 'source' => $this->sources()['dod']],
                        ['label' => 'Taiwan Strait trade at risk', 'value' => 'US$2.45T', 'direction' => 'up', 'sparkline' => [30, 44, 56, 70, 82], 'source' => $this->sources()['csis_trade']],
                        ['label' => 'Leading-edge chips in Taiwan', 'value' => '>90%', 'direction' => 'up', 'sparkline' => [88, 90, 91, 92, 93], 'source' => $this->sources()['trade_semiconductors']],
                        ['label' => 'Taiwan energy import dependence', 'value' => '95%', 'direction' => 'up', 'sparkline' => [90, 92, 94, 95, 95], 'source' => $this->sources()['energy_resilience']],
                        ['label' => 'Drone special budget', 'value' => 'NT$210B', 'direction' => 'up', 'sparkline' => [20, 34, 51, 66, 82], 'source' => $this->sources()['reuters_drone']],
                    ],
                ],
                'schema_version' => 1,
                'sort_order' => 1,
            ],
            $this->lineVisualization('pla_pressure_trend', ['en' => 'PLA pressure trend', 'it' => 'Trend pressione PLA'], ['en' => 'Annual Taiwan ADIZ activity reported in the China Military Power Report.', 'it' => 'Attività annuale ADIZ di Taiwan riportata nel China Military Power Report.'], ['2021', '2022', '2023', '2024'], [972, 1733, 1703, 2771], 'activity count', [$this->sources()['dod']], 2),
            $this->lineVisualization('naval_pressure_2026', ['en' => 'Naval pressure 2026', 'it' => 'Pressione navale 2026'], ['en' => 'Display baseline versus July 2026 reports of more than 110 military and coast guard ships in the region.', 'it' => 'Baseline espositiva contro i report di luglio 2026 su oltre 110 navi militari e della guardia costiera nella regione.'], ['Baseline', 'July 2026'], [50, 110], 'ships', [$this->sources()['reuters_naval']], 3),
            $this->lineVisualization('economic_exposure', ['en' => 'Economic exposure', 'it' => 'Esposizione economica'], ['en' => 'Trade, annual activity and sanctions shock ranges exposed by Taiwan Strait disruption.', 'it' => 'Commercio, attività annuale e shock da sanzioni esposti a una crisi nello Stretto di Taiwan.'], ['Strait trade', 'Annual activity at risk', 'Max sanctions shock'], [2.45, 2.0, 3.0], 'US$ trillion', [$this->sources()['csis_trade'], $this->sources()['rhodium_disruptions']], 4),
            $this->lineVisualization('scenario_gdp_shock', ['en' => 'Scenario GDP shock', 'it' => 'Shock PIL per scenario'], ['en' => 'Editorial midpoint estimates for first-year Taiwan GDP shock by scenario.', 'it' => 'Stime editoriali mediane dello shock sul PIL taiwanese nel primo anno per scenario.'], ['Optimistic', 'Neutral', 'Pessimistic'], [15, 32, 45], '% Taiwan GDP', [$this->sources()['rhodium_disruptions']], 5),
            $this->lineVisualization('energy_resilience', ['en' => 'Energy resilience', 'it' => 'Resilienza energetica'], ['en' => 'Energy import dependence, oil and gas dependence, and displayed LNG reserve days.', 'it' => 'Dipendenza energetica, dipendenza da petrolio e gas e giorni di riserva LNG visualizzati.'], ['Energy import dependence', 'Oil/gas import dependence', 'LNG reserve days'], [95, 99, 12], 'value', [$this->sources()['energy_resilience']], 6),
        ];
    }

    /** @param array<string, string> $title @param array<string, string> $description @param array<int, string> $labels @param array<int, int|float> $series @param array<int, string> $sources @return array<string, mixed> */
    private function lineVisualization(string $key, array $title, array $description, array $labels, array $series, string $unit, array $sources, int $sortOrder): array
    {
        return [
            'key' => $key,
            'type' => VisualizationType::Line,
            'title' => $title,
            'description' => $description,
            'payload' => [
                'labels' => $labels,
                'series' => $series,
                'unit' => $unit,
                'sources' => $sources,
            ],
            'schema_version' => 1,
            'sort_order' => $sortOrder,
        ];
    }

    /** @return array<int, array<string, mixed>> */
    private function news(): array
    {
        return [
            ['locale' => NewsLocale::All, 'title' => 'Taiwan says China risks creating a new status quo', 'excerpt' => 'Taipei warned that repeated pressure around Taiwan can normalize coercive activity even below the threshold of war.', 'source_name' => 'Reuters', 'source_url' => $this->sources()['reuters_status_quo'], 'published_at' => CarbonImmutable::parse('2026-07-08 12:00:00', 'UTC')],
            ['locale' => NewsLocale::All, 'title' => 'Taiwan says attack preparations are not provocation', 'excerpt' => 'Senior officials framed civil and military preparedness as deterrence and resilience rather than escalation.', 'source_name' => 'Reuters', 'source_url' => $this->sources()['reuters_preparedness'], 'published_at' => CarbonImmutable::parse('2026-07-07 12:00:00', 'UTC')],
            ['locale' => NewsLocale::All, 'title' => 'Taiwan tracks upward trend in Chinese naval movements', 'excerpt' => 'Taiwan reported an upward trend in Chinese naval and coast guard activity, with more than 110 ships in the region.', 'source_name' => 'Reuters', 'source_url' => $this->sources()['reuters_naval'], 'published_at' => CarbonImmutable::parse('2026-07-06 12:00:00', 'UTC')],
            ['locale' => NewsLocale::All, 'title' => 'China launches coast guard patrol east of Taiwan', 'excerpt' => 'The patrol highlighted a pressure pattern beyond the median line and around Taiwan’s eastern approaches.', 'source_name' => 'Reuters', 'source_url' => $this->sources()['reuters_coast_guard'], 'published_at' => CarbonImmutable::parse('2026-07-04 12:00:00', 'UTC')],
            ['locale' => NewsLocale::All, 'title' => 'Taiwan drills a worst-case blockade and invasion chain', 'excerpt' => 'Exercises combined blockade, earthquake, sabotage and invasion stress to test whole-of-society response.', 'source_name' => 'Reuters', 'source_url' => $this->sources()['reuters_drill'], 'published_at' => CarbonImmutable::parse('2026-07-03 12:00:00', 'UTC')],
            ['locale' => NewsLocale::All, 'title' => 'Drone deterrence plan calls for a hornet’s nest', 'excerpt' => 'A proposed NT$210B drone package underlined Taiwan’s move toward distributed asymmetric deterrence.', 'source_name' => 'Reuters', 'source_url' => $this->sources()['reuters_drone'], 'published_at' => CarbonImmutable::parse('2026-07-02 12:00:00', 'UTC')],
            ['locale' => NewsLocale::It, 'title' => 'Il commercio nello Stretto è una vulnerabilità globale', 'excerpt' => 'Le analisi CSIS indicano che un’interruzione nello Stretto avrebbe effetti severi su Cina, Taiwan e catene globali.', 'source_name' => 'CSIS', 'source_url' => $this->sources()['csis_trade'], 'published_at' => CarbonImmutable::parse('2026-06-20 12:00:00', 'UTC')],
            ['locale' => NewsLocale::All, 'title' => 'China Military Power Report details Taiwan pressure indicators', 'excerpt' => 'The report provides the baseline for PLA modernization, ADIZ activity and regional military balance assumptions.', 'source_name' => 'U.S. Department of Defense', 'source_url' => $this->sources()['dod'], 'published_at' => CarbonImmutable::parse('2025-12-23 12:00:00', 'UTC')],
        ];
    }

    /** @return array<int, array<string, mixed>> */
    private function initiatives(): array
    {
        return [
            ['locale' => InitiativeLocale::All, 'type' => InitiativeType::Resource, 'title' => 'Whole-of-Society Defense Resilience Committee', 'excerpt' => 'Taiwan presidential committee coordinating civil resilience, continuity and whole-of-society defense planning.', 'body' => 'Use this source to track official resilience framing, committee priorities and public preparedness language.', 'organization' => 'Office of the President, Taiwan', 'url' => $this->sources()['whole_society'], 'cta_label' => 'Open committee'],
            ['locale' => InitiativeLocale::All, 'type' => InitiativeType::Resource, 'title' => 'In Case of Crisis: Taiwan Public Safety Guide', 'excerpt' => 'Official public safety guide for households and communities preparing for emergencies and coercive pressure.', 'body' => 'The guide is relevant to civil readiness, sheltering, communications and continuity behavior under crisis.', 'organization' => 'Taiwan Ministry of National Defense', 'url' => $this->sources()['public_safety_guide'], 'cta_label' => 'Open guide'],
            ['locale' => InitiativeLocale::All, 'type' => InitiativeType::Campaign, 'title' => '2026 Urban Resilience Exercises', 'excerpt' => 'Exercises focused on city-level resilience and civil response under multi-domain crisis pressure.', 'body' => 'Use this source to track how Taiwan drills city services, local response and continuity under stress.', 'organization' => 'All-Out Defense Mobilization Agency', 'url' => $this->sources()['urban_resilience'], 'cta_label' => 'View exercises'],
            ['locale' => InitiativeLocale::All, 'type' => InitiativeType::Resource, 'title' => 'Kuma Academy civil-defense education', 'excerpt' => 'Civil-defense education network focused on public awareness, resilience and preparedness skills.', 'body' => 'Kuma Academy is a public civil-society resource for non-government preparedness education.', 'organization' => 'Kuma Academy', 'url' => $this->sources()['kuma'], 'cta_label' => 'Open academy'],
            ['locale' => InitiativeLocale::All, 'type' => InitiativeType::Resource, 'title' => 'G7 geopolitical statement on Taiwan Strait stability', 'excerpt' => 'G7 leaders emphasized peace and stability across the Taiwan Strait as a geopolitical priority.', 'body' => 'Use this source to track allied diplomatic signaling around deterrence and crisis response.', 'organization' => 'G7', 'url' => $this->sources()['g7_statement'], 'cta_label' => 'Read statement'],
            ['locale' => InitiativeLocale::All, 'type' => InitiativeType::Resource, 'title' => 'Quad foreign ministers’ statement', 'excerpt' => 'Regional partners reiterated support for a free, open Indo-Pacific and stability in maritime flashpoints.', 'body' => 'Use this source to track diplomatic alignment and deterrence language among Quad partners.', 'organization' => 'Quad', 'url' => $this->sources()['quad_statement'], 'cta_label' => 'Read statement'],
            ['locale' => InitiativeLocale::It, 'type' => InitiativeType::Resource, 'title' => 'Guida pubblica di sicurezza in caso di crisi', 'excerpt' => 'Versione localizzata del riferimento operativo per seguire preparazione civile e comunicazioni di emergenza.', 'body' => 'Riga italiana dedicata per mostrare la tab iniziative localizzata senza cambiare schema dati.', 'organization' => 'Ministero della Difesa di Taiwan', 'url' => $this->sources()['public_safety_guide'], 'cta_label' => 'Apri guida'],
        ];
    }

    /** @return array<string, string> */
    private function sources(): array
    {
        return [
            'dod' => 'https://media.defense.gov/2025/Dec/23/2003849070/-1/-1/1/ANNUAL-REPORT-TO-CONGRESS-MILITARY-AND-SECURITY-DEVELOPMENTS-INVOLVING-THE-PEOPLES-REPUBLIC-OF-CHINA-2025.PDF',
            'reuters_status_quo' => 'https://www.reuters.com/world/china/chinas-actions-risk-creation-new-status-quo-taiwan-official-says-2026-07-08/',
            'reuters_preparedness' => 'https://www.reuters.com/business/aerospace-defense/taiwans-preparations-face-chinese-attack-are-not-provocation-senior-official-2026-07-07/',
            'reuters_naval' => 'https://www.reuters.com/world/china/taiwan-says-it-is-tracking-upward-trend-chinese-naval-movements-2026-07-06/',
            'reuters_coast_guard' => 'https://www.reuters.com/world/china/china-launches-coast-guard-patrol-east-taiwan-despite-international-pushback-2026-07-04/',
            'reuters_drill' => 'https://www.reuters.com/world/china/inside-taiwans-nightmare-scenario-chinese-blockade-earthquake-sabotage-invasion-2026-07-03/',
            'reuters_drone' => 'https://www.reuters.com/world/china/taiwan-needs-hornets-nest-drones-deter-conflict-us-diplomat-says-2026-07-02/',
            'csis_trade' => 'https://www.csis.org/analysis/disruptions-trade-taiwan-strait-would-severely-impact-chinas-economy',
            'rhodium_disruptions' => 'https://rhg.com/research/taiwan-economic-disruptions/',
            'trade_semiconductors' => 'https://www.trade.gov/country-commercial-guides/taiwan-semiconductors-including-chip-design-ai',
            'energy_resilience' => 'https://www.atlanticcouncil.org/blogs/energysource/the-iran-war-tests-taiwans-energy-resilience/',
            'whole_society' => 'https://english.president.gov.tw/Page/669',
            'public_safety_guide' => 'https://adma.mnd.gov.tw/files/web/191/file_up/100004/66/InCaseofCrisisTaiwanPublicSafetyGuide.pdf',
            'urban_resilience' => 'https://adma.mnd.gov.tw/uniten/100005/8074',
            'kuma' => 'https://kuma-academy.org/about?lang=en',
            'g7_statement' => 'https://www.elysee.fr/en/G7evian/2026/06/17/g7-leaders-statement-on-geopolitical-issues',
            'quad_statement' => 'https://www.state.gov/releases/office-of-the-spokesperson/2026/05/joint-statement-from-the-quad-foreign-ministers-meeting-in-new-delhi',
        ];
    }
}
