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

        foreach ($this->events() as $index => $event) {
            $countdown = Countdown::query()->create([
                'slug' => $event['slug'],
                'title' => $event['title'],
                'summary' => $event['summary'],
                'description' => $event['description'],
                'causes' => $event['causes'],
                'consequences' => $event['consequences'],
                'recommended_actions' => $event['recommended_actions'],
                'icon' => $event['icon'],
                'severity' => $event['severity'],
                'status' => CountdownStatus::Active,
                'target_date' => $event['target_date'],
                'image_path' => $event['image_path'],
                'sort_order' => $index + 1,
                'is_published' => true,
            ]);

            $neutral = $countdown->projections()->create([
                'type' => ProjectionType::Neutral,
                'target_date' => $event['target_date'],
                'title' => ['en' => 'Baseline forecast', 'it' => 'Previsione base'],
                'summary' => ['en' => $event['projection_summary_en'], 'it' => $event['projection_summary_it']],
                'confidence_score' => $event['confidence'],
                'probability_score' => $event['probability'],
                'trend' => $event['trend'],
                'sort_order' => 1,
            ]);

            $countdown->projections()->create([
                'type' => ProjectionType::Pessimistic,
                'target_date' => CarbonImmutable::parse($event['target_date'])->subMonths(8),
                'title' => ['en' => 'Accelerated scenario', 'it' => 'Scenario accelerato'],
                'summary' => ['en' => 'Stress indicators compress the estimated window.', 'it' => 'Gli indicatori di stress riducono la finestra stimata.'],
                'confidence_score' => max(35, $event['confidence'] - 10),
                'probability_score' => min(98, $event['probability'] + 8),
                'trend' => 'rising',
                'sort_order' => 2,
            ]);

            $neutral->visualizations()->create([
                'key' => 'projection_curve',
                'type' => VisualizationType::Line,
                'title' => ['en' => 'Projection model', 'it' => 'Modello di proiezione'],
                'description' => ['en' => 'Probability of event occurrence.', 'it' => 'Probabilità di occorrenza dello scenario.'],
                'payload' => $this->projectionPayload($event['probability']),
                'schema_version' => 1,
                'sort_order' => 1,
            ]);

            $countdown->visualizations()->create([
                'key' => 'key_indicators',
                'type' => VisualizationType::Kpi,
                'title' => ['en' => 'Key indicators', 'it' => 'Indicatori chiave'],
                'description' => ['en' => 'Scenario pressure signals.', 'it' => 'Segnali di pressione dello scenario.'],
                'payload' => ['items' => $event['indicators']],
                'schema_version' => 1,
                'sort_order' => 1,
            ]);

            foreach ($event['news'] as $newsIndex => $news) {
                $countdown->news()->create([
                    'locale' => $news['locale'],
                    'title' => $news['title'],
                    'excerpt' => $news['excerpt'],
                    'source_name' => $news['source_name'],
                    'image_path' => $event['image_path'],
                    'published_at' => CarbonImmutable::now()->subHours($newsIndex + 2),
                    'sort_order' => $newsIndex + 1,
                    'is_featured' => $newsIndex === 0,
                ]);
            }

            foreach ($this->initiatives($event['title']['en']) as $initiativeIndex => $initiative) {
                $countdown->initiatives()->create([
                    'locale' => $initiative['locale'],
                    'type' => $initiative['type'],
                    'title' => $initiative['title'],
                    'excerpt' => $initiative['excerpt'],
                    'body' => $initiative['body'],
                    'organization' => $initiative['organization'],
                    'url' => $initiative['url'],
                    'image_path' => $event['image_path'],
                    'cta_label' => $initiative['cta_label'],
                    'starts_at' => CarbonImmutable::now()->addDays($initiativeIndex + 4),
                    'sort_order' => $initiativeIndex + 1,
                    'is_featured' => $initiativeIndex === 0,
                ]);
            }
        }
    }

    /** @return array<int, array<string, mixed>> */
    private function events(): array
    {
        return [
            [
                'slug' => 'society-collapse',
                'title' => ['en' => 'Society Collapse', 'it' => 'Collasso Sociale'],
                'summary' => ['en' => 'Social systems breakdown and civil order failure', 'it' => 'Rottura dei sistemi sociali e dell’ordine civile'],
                'description' => ['en' => 'A convergence of fragile institutions, supply shocks, and civic distrust can erode the routines that keep public life stable.', 'it' => 'La convergenza di istituzioni fragili, shock alle forniture e sfiducia civica può erodere le routine che mantengono stabile la vita pubblica.'],
                'causes' => ['en' => ['Institutional fatigue', 'Supply chain fragility', 'Civic trust decline'], 'it' => ['Logoramento istituzionale', 'Fragilità delle catene di fornitura', 'Calo della fiducia civica']],
                'consequences' => ['en' => ['Localized unrest', 'Service interruption', 'Emergency governance'], 'it' => ['Disordini locali', 'Interruzione dei servizi', 'Governance d’emergenza']],
                'recommended_actions' => ['en' => ['Monitor resilience indicators', 'Strengthen local continuity plans'], 'it' => ['Monitorare gli indicatori di resilienza', 'Rafforzare i piani locali di continuità']],
                'icon' => 'users',
                'severity' => CountdownSeverity::Critical,
                'target_date' => CarbonImmutable::now()->addYears(5)->addDays(247)->addHours(12),
                'image_path' => 'images/doomsday/society_collapse_separate.png',
                'confidence' => 74,
                'probability' => 81,
                'trend' => 'rising',
                'projection_summary_en' => 'Urban fragility indicators remain elevated across several simulated regions.',
                'projection_summary_it' => 'Gli indicatori di fragilità urbana restano elevati in diverse regioni simulate.',
                'indicators' => $this->indicators(['Trust Index' => -18, 'Supply Stress' => 42, 'Civil Unrest' => 214, 'Service Continuity' => -23]),
                'news' => $this->news('Civic stress signals rise in multiple regions', 'Local continuity planners review emergency thresholds'),
            ],
            [
                'slug' => 'fall-of-europe',
                'title' => ['en' => 'Fall of Europe', 'it' => 'Caduta dell’Europa'],
                'summary' => ['en' => 'Economic collapse and geopolitical instability', 'it' => 'Collasso economico e instabilità geopolitica'],
                'description' => ['en' => 'Europe faces a convergence of economic stagnation, political fragmentation, and demographic decline that could weaken major institutions.', 'it' => 'L’Europa affronta stagnazione economica, frammentazione politica e declino demografico che potrebbero indebolire le istituzioni principali.'],
                'causes' => ['en' => ['Low growth', 'Energy pressure', 'Political fragmentation'], 'it' => ['Bassa crescita', 'Pressione energetica', 'Frammentazione politica']],
                'consequences' => ['en' => ['Fiscal gridlock', 'Border tension', 'Institutional paralysis'], 'it' => ['Stallo fiscale', 'Tensioni ai confini', 'Paralisi istituzionale']],
                'recommended_actions' => ['en' => ['Track fiscal cohesion', 'Review energy exposure'], 'it' => ['Seguire la coesione fiscale', 'Valutare l’esposizione energetica']],
                'icon' => 'europe',
                'severity' => CountdownSeverity::Severe,
                'target_date' => CarbonImmutable::now()->addYears(3)->addDays(189)->addHours(7),
                'image_path' => 'images/doomsday/fall_of_europe_separate.png',
                'confidence' => 72,
                'probability' => 78,
                'trend' => 'decreasing',
                'projection_summary_en' => 'The baseline window remains driven by fiscal stress, inflation, and social stability indicators.',
                'projection_summary_it' => 'La finestra base resta guidata da stress fiscale, inflazione e indicatori di stabilità sociale.',
                'indicators' => $this->indicators(['GDP Growth (EU)' => -0.3, 'Energy Inflation' => 38.7, 'Political Stability' => 2.1, 'Public Debt Avg' => 89.4, 'Social Unrest' => 214]),
                'news' => $this->news('EU leaders fail to agree on new fiscal pact', 'Energy crisis worsens as winter approaches'),
            ],
            [
                'slug' => 'extreme-heat-breakpoint',
                'title' => ['en' => 'Extreme Heat Breakpoint', 'it' => 'Punto Critico del Caldo Estremo'],
                'summary' => ['en' => 'Unsurvivable heat conditions in many regions', 'it' => 'Condizioni di caldo insostenibile in molte regioni'],
                'description' => ['en' => 'Compound heat and humidity events can push urban and agricultural systems beyond safe operating limits.', 'it' => 'Eventi combinati di calore e umidità possono superare i limiti operativi sicuri di città e agricoltura.'],
                'causes' => ['en' => ['Wet-bulb heat', 'Urban heat islands', 'Water scarcity'], 'it' => ['Calore a bulbo umido', 'Isole di calore urbane', 'Scarsità idrica']],
                'consequences' => ['en' => ['Outdoor work collapse', 'Crop losses', 'Grid overload'], 'it' => ['Crollo del lavoro all’aperto', 'Perdite agricole', 'Sovraccarico della rete']],
                'recommended_actions' => ['en' => ['Map heat refuges', 'Monitor grid reserve margins'], 'it' => ['Mappare rifugi climatici', 'Monitorare i margini della rete']],
                'icon' => 'thermometer',
                'severity' => CountdownSeverity::Critical,
                'target_date' => CarbonImmutable::now()->addYears(2)->addDays(68)->addHours(19),
                'image_path' => 'images/doomsday/extreme_heat_breakpoint_separate.png',
                'confidence' => 79,
                'probability' => 86,
                'trend' => 'rising',
                'projection_summary_en' => 'Heat stress accelerates when temperature, humidity, and infrastructure load peak together.',
                'projection_summary_it' => 'Lo stress termico accelera quando temperatura, umidità e carico infrastrutturale raggiungono picchi simultanei.',
                'indicators' => $this->indicators(['Heat Index' => 49, 'Grid Load' => 88, 'Water Stress' => 63, 'Crop Risk' => 57]),
                'news' => $this->news('Heat resilience plans move to emergency review', 'Water stress warnings expand across vulnerable regions'),
            ],
            [
                'slug' => 'uninhabitable-earth',
                'title' => ['en' => 'Uninhabitable Earth', 'it' => 'Terra Inabitabile'],
                'summary' => ['en' => 'Environmental collapse makes Earth uninhabitable', 'it' => 'Il collasso ambientale rende la Terra inabitabile'],
                'description' => ['en' => 'The combined degradation of climate stability, coastal safety, food systems, and biodiversity can reduce safe human habitat.', 'it' => 'Il degrado combinato di stabilità climatica, sicurezza costiera, sistemi alimentari e biodiversità può ridurre gli habitat umani sicuri.'],
                'causes' => ['en' => ['Sea-level pressure', 'Food system failure', 'Biodiversity loss'], 'it' => ['Pressione del livello del mare', 'Crisi dei sistemi alimentari', 'Perdita di biodiversità']],
                'consequences' => ['en' => ['Mass displacement', 'Coastal abandonment', 'Habitat contraction'], 'it' => ['Spostamenti di massa', 'Abbandono costiero', 'Contrazione degli habitat']],
                'recommended_actions' => ['en' => ['Track relocation thresholds', 'Protect critical ecosystems'], 'it' => ['Monitorare le soglie di ricollocazione', 'Proteggere ecosistemi critici']],
                'icon' => 'waves',
                'severity' => CountdownSeverity::Existential,
                'target_date' => CarbonImmutable::now()->addYears(7)->addDays(310)->addHours(23),
                'image_path' => 'images/doomsday/uninhabitable_earth_separate.png',
                'confidence' => 68,
                'probability' => 64,
                'trend' => 'rising',
                'projection_summary_en' => 'Long-horizon signals remain slower but broader than the other sample scenarios.',
                'projection_summary_it' => 'I segnali di lungo periodo restano più lenti ma più ampi rispetto agli altri scenari campione.',
                'indicators' => $this->indicators(['Coastal Risk' => 71, 'Food Stability' => -24, 'Habitat Loss' => 46, 'Displacement' => 31]),
                'news' => $this->news('Coastal adaptation budgets face new pressure', 'Habitat loss indicators remain above warning band'),
            ],
        ];
    }

    /** @return array<int, array<string, mixed>> */
    private function indicators(array $values): array
    {
        $items = [];
        foreach ($values as $label => $value) {
            $items[] = [
                'label' => $label,
                'value' => is_float($value) ? sprintf('%+.1f', $value) : sprintf('%+d', $value),
                'direction' => $value >= 0 ? 'up' : 'down',
                'sparkline' => [18, 24, 20, 31, 26, 36, 30, 42, 33, 38],
            ];
        }

        return $items;
    }

    /** @return array<int, array<string, mixed>> */
    private function news(string $first, string $second): array
    {
        return [
            ['locale' => NewsLocale::All, 'title' => $first, 'excerpt' => 'Scenario monitors updated the latest public risk notes.', 'source_name' => 'Daily Monitor'],
            ['locale' => NewsLocale::En, 'title' => $second, 'excerpt' => 'Regional observers report a changing risk profile.', 'source_name' => 'Global Desk'],
            ['locale' => NewsLocale::It, 'title' => 'Nuovo aggiornamento sugli indicatori regionali', 'excerpt' => 'Gli osservatori regionali segnalano un profilo di rischio in evoluzione.', 'source_name' => 'Osservatorio'],
        ];
    }

    /** @return array<int, array<string, mixed>> */
    private function initiatives(string $eventTitle): array
    {
        return [
            [
                'locale' => InitiativeLocale::All,
                'type' => InitiativeType::Campaign,
                'title' => $eventTitle . ' resilience campaign',
                'excerpt' => 'A coordinated public action hub for monitoring, preparedness and local risk communication.',
                'body' => 'Sample initiative data for phase 1.1. It links scenario awareness with community planning resources.',
                'organization' => 'Civic Risk Network',
                'url' => 'https://example.org/doomsday/' . str($eventTitle)->slug()->toString(),
                'cta_label' => 'View campaign',
            ],
            [
                'locale' => InitiativeLocale::En,
                'type' => InitiativeType::Petition,
                'title' => 'Sign the preparedness petition',
                'excerpt' => 'A sample petition asking institutions to publish clear risk thresholds and continuity plans.',
                'body' => 'The petition is seeded content only and does not submit to an external service in phase 1.1.',
                'organization' => 'Preparedness Watch',
                'url' => 'https://example.org/petitions/preparedness',
                'cta_label' => 'Read petition',
            ],
            [
                'locale' => InitiativeLocale::It,
                'type' => InitiativeType::Resource,
                'title' => 'Risorse locali di preparazione',
                'excerpt' => 'Una scheda campione con risorse e azioni di continuità per comunità locali.',
                'body' => 'Contenuto seed per mostrare la localizzazione delle iniziative nella tab dedicata.',
                'organization' => 'Osservatorio Civico',
                'url' => 'https://example.org/risorse/preparazione',
                'cta_label' => 'Apri risorsa',
            ],
        ];
    }

    /** @return array<string, mixed> */
    private function projectionPayload(int $peak): array
    {
        return [
            'unit' => '%',
            'labels' => ['2024', '2025', '2026', '2027', '2028', '2029'],
            'series' => [max(18, $peak - 58), max(24, $peak - 46), max(35, $peak - 31), $peak, min(96, $peak + 7), min(99, $peak + 14)],
            'highlight' => $peak,
        ];
    }
}
