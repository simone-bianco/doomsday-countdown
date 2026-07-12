<?php

declare(strict_types=1);

use App\Enums\ProjectionType;
use Carbon\CarbonImmutable;

$shared = require __DIR__.'/../_shared.php';

return new class($shared)
{
    public function __construct(private object $shared) {}

    /** @return list<array<string, mixed>> */
    public function projections(): array
    {
        $sources = [
            $this->shared->sources()['ddis_russia_threat_2025'],
            $this->shared->sources()['nato_five_year_assessment_2025'],
            $this->shared->sources()['nato_2029_assessment_2026'],
            $this->shared->sources()['eu_readiness_roadmap_2030'],
            $this->shared->sources()['nato_hague_declaration'],
            $this->shared->sources()['nato_ankara_declaration_2026'],
        ];

        $methodology = [
            'classification' => 'Conditional editorial security window, not an official forecast or a statistical war probability.',
            'as_of' => '2026-07-11',
            'horizon' => 'Scenario-specific deadlines at post-winter 2027, end-2030 and end-2035.',
            'current_state' => 'Russia’s full-scale war against Ukraine remains ongoing as of the assessment date, so post-freeze force-release clocks are not treated as already completed.',
            'probability_score_note' => 'The UI probability score is an editorial scenario weight, not an empirical likelihood.',
            'drivers' => ['duration and outcome of the war in Ukraine', 'Russian force reconstitution and industrial output', 'NATO political cohesion and United States support', 'EU joint procurement and delivery milestones', 'air defence, ammunition and military mobility readiness'],
            'sources' => $sources,
        ];

        return [
            [
                'type' => ProjectionType::Optimistic,
                'target_date' => CarbonImmutable::parse('2035-12-31 23:59:59', 'UTC'),
                'title' => $this->shared->t(en: 'Deterrence endures through the investment cycle', it: 'La deterrenza regge lungo il ciclo di investimento', fr: 'La dissuasion tient pendant le cycle d’investissement', de: 'Abschreckung hält über den Investitionszyklus', es: 'La disuasión se mantiene durante el ciclo de inversión', nl: 'Afschrikking houdt stand tijdens de investeringscyclus', sv: 'Avskräckningen håller genom investeringscykeln', pl: 'Odstraszanie utrzymuje się przez cykl inwestycyjny'),
                'summary' => $this->shared->t(en: 'EU capability gaps narrow by 2030 and NATO’s 2035 investment commitment matures without a breakdown in alliance cohesion.', it: 'I divari di capacità UE si riducono entro il 2030 e l’impegno NATO al 2035 matura senza rotture della coesione alleata.', fr: 'Les lacunes capacitaires de l’UE se réduisent d’ici 2030 et l’engagement d’investissement de l’OTAN pour 2035 arrive à maturité sans rupture de cohésion.', de: 'EU-Fähigkeitslücken schrumpfen bis 2030 und die NATO-Investitionszusage bis 2035 reift ohne Bruch der Bündniskohäsion.', es: 'Las brechas de capacidades de la UE se reducen para 2030 y el compromiso de inversión de la OTAN para 2035 madura sin ruptura de cohesión.', nl: 'EU-capaciteitsgaten verkleinen tegen 2030 en de NAVO-investeringsbelofte voor 2035 rijpt zonder breuk in de cohesie.', sv: 'EU:s förmågegap minskar till 2030 och Natos investeringsåtagande till 2035 mognar utan sammanbrott i alliansens enighet.', pl: 'Luki zdolności UE maleją do 2030 roku, a zobowiązanie inwestycyjne NATO do 2035 roku dojrzewa bez załamania spójności sojuszu.'),
                'confidence_score' => 54,
                'probability_score' => 30,
                'trend' => 'improving',
                'methodology' => array_merge($methodology, [
                    'scenario_target_year' => 2035,
                    'target_rationale' => 'End-2035 is the optimistic outer window because NATO’s Hague commitment reaches full 5% investment maturity then. It assumes the EU’s 2030 capability-delivery milestones materially succeed and deterrence prevents a direct conflict window from opening earlier.',
                    'milestones' => [
                        'EU joint-procurement, contract and SAFE delivery milestones are substantially met by end-2030.',
                        'Ukraine remains capable of self-defence with predictable multi-year support, limiting Russian force release.',
                        'NATO sustains Article 5 cohesion and the 5% defence-and-security investment path through 2035.',
                        'Industrial output, air defence, ammunition, logistics and military mobility convert spending into deployable capability.',
                    ],
                    'limits' => [
                        'The 2035 NATO commitment is an investment deadline, not a guarantee of delivered capability or peace.',
                        'A persistent Russian threat can continue even if European readiness improves.',
                        'The scenario does not estimate Russian intent and cannot exclude hybrid or accidental escalation before 2035.',
                    ],
                    'stop_conditions' => [
                        'Move the horizon earlier if EU 2030 capability milestones materially slip.',
                        'Move the horizon earlier if alliance cohesion or sustained support to Ukraine weakens.',
                        'Retain or move later only if independent readiness reports confirm delivery and deterrence outpace Russian reconstitution.',
                    ],
                ]),
                'sort_order' => 1,
            ],
            [
                'type' => ProjectionType::Neutral,
                'target_date' => CarbonImmutable::parse('2030-12-31 23:59:59', 'UTC'),
                'title' => $this->shared->t(en: 'Readiness race reaches its central threshold', it: 'La corsa alla prontezza raggiunge la soglia centrale', fr: 'La course à la préparation atteint son seuil central', de: 'Das Bereitschaftsrennen erreicht seine zentrale Schwelle', es: 'La carrera de preparación alcanza su umbral central', nl: 'De paraatheidsrace bereikt haar centrale drempel', sv: 'Beredskapskapplöpningen når sin centrala tröskel', pl: 'Wyścig gotowości osiąga centralny próg'),
                'summary' => $this->shared->t(en: 'After the active Pessimistic 2027 checkpoint and before the Optimistic 2035 horizon, the Neutral end-2030 readiness checkpoint marks where NATO’s public 2029/end-decade warning meets the EU’s end-2030 delivery plan.', it: 'Dopo il checkpoint Pessimistico attivo del 2027 e prima dell’orizzonte Ottimistico del 2035, il checkpoint Neutrale di prontezza a fine 2030 segna l’incontro tra l’avvertimento pubblico NATO sul 2029/fine decennio e il piano UE di consegne entro fine 2030.', fr: 'Après le jalon Pessimiste actif de 2027 et avant l’horizon Optimiste de 2035, le jalon Neutre de préparation fin 2030 marque la rencontre entre l’alerte publique de l’OTAN pour 2029/la fin de décennie et le plan de livraisons de l’UE à fin 2030.', de: 'Nach dem aktiven pessimistischen Prüfpunkt 2027 und vor dem optimistischen Horizont 2035 markiert der neutrale Bereitschaftspunkt Ende 2030 das Zusammentreffen der öffentlichen NATO-Warnung für 2029/das Jahrzehntende mit dem EU-Lieferplan bis Ende 2030.', es: 'Después del hito Pesimista activo de 2027 y antes del horizonte Optimista de 2035, el hito Neutral de preparación de finales de 2030 marca la convergencia entre la alerta pública de la OTAN para 2029/final de década y el plan de entregas de la UE para finales de 2030.', nl: 'Na het actieve Pessimistische ijkpunt van 2027 en vóór de Optimistische horizon van 2035 markeert het Neutrale paraatheidsijkpunt eind 2030 waar de openbare NAVO-waarschuwing voor 2029/einde decennium samenkomt met het EU-leveringsplan voor eind 2030.', sv: 'Efter den aktiva Pessimistiska kontrollpunkten 2027 och före den Optimistiska horisonten 2035 markerar den Neutrala beredskapspunkten i slutet av 2030 mötet mellan Natos offentliga varning för 2029/årtiondets slut och EU:s leveransplan till slutet av 2030.', pl: 'Po aktywnym Pesymistycznym punkcie z 2027 roku i przed Optymistycznym horyzontem 2035 roku Neutralny punkt gotowości pod koniec 2030 roku wyznacza miejsce, w którym publiczne ostrzeżenie NATO dotyczące 2029/końca dekady spotyka się z planem dostaw UE do końca 2030 roku.'),
                'confidence_score' => 72,
                'probability_score' => 50,
                'trend' => 'rising',
                'methodology' => array_merge($methodology, [
                    'scenario_target_year' => 2030,
                    'target_rationale' => 'End-2030 is the neutral main timer because NATO publicly cites open-source European intelligence assessments that Russia could be ready by 2029 or within five years, while the EU Readiness Roadmap sets end-2028 contracts and financing and end-2030 SAFE deliveries and capability-gap closure. The date is the crossing point of two readiness timelines, not a war date.',
                    'milestones' => [
                        'At least 40% of EU defence procurement is organised jointly by end-2027.',
                        'Projects, contracts and financing for priority capability gaps are in place and Eastern Flank Watch is functional by end-2028.',
                        'The public NATO intelligence warning window around 2029 is reassessed against actual Russian force regeneration.',
                        'All SAFE-funded procurements contributing to priority shortfalls are received by end-2030.',
                    ],
                    'limits' => [
                        'Capability to threaten NATO does not establish intent to attack.',
                        'EU milestones measure programmes and delivery, not a complete military balance.',
                        'The ongoing war in Ukraine can either delay Russian force release or accelerate adaptation and production.',
                    ],
                    'stop_conditions' => [
                        'Move earlier if a ceasefire or freeze releases major Russian forces and European 2027-2028 delivery milestones fail.',
                        'Move earlier after a direct military incident, sustained force concentration or a credible intelligence revision.',
                        'Move later if EU/NATO delivery milestones are independently met and Russian reconstitution remains constrained.',
                    ],
                ]),
                'sort_order' => 2,
            ],
            [
                'type' => ProjectionType::Pessimistic,
                'target_date' => CarbonImmutable::parse('2027-03-31 23:59:59', 'UTC'),
                'title' => $this->shared->t(en: 'Rapid force release opens an early regional window', it: 'Un rapido rilascio di forze apre una finestra regionale anticipata', fr: 'Une libération rapide des forces ouvre une fenêtre régionale précoce', de: 'Schnelle Kräftefreisetzung öffnet ein frühes Regionalfenster', es: 'Una rápida liberación de fuerzas abre una ventana regional temprana', nl: 'Snelle vrijmaking van troepen opent een vroeg regionaal venster', sv: 'Snabb frigörelse av styrkor öppnar ett tidigt regionalt fönster', pl: 'Szybkie uwolnienie sił otwiera wczesne okno regionalne'),
                'summary' => $this->shared->t(en: 'A user-selected post-winter 2027 checkpoint represents the earliest editorial risk window under rapid deterioration.', it: 'Un checkpoint post-inverno 2027 scelto dall’utente rappresenta la finestra editoriale di rischio più anticipata in caso di rapido deterioramento.', fr: 'Un jalon d’après-hiver 2027 choisi par l’utilisateur représente la fenêtre éditoriale de risque la plus précoce en cas de détérioration rapide.', de: 'Ein vom Nutzer gewählter Zeitpunkt nach dem Winter 2027 bildet bei rascher Verschlechterung das früheste redaktionelle Risikofenster ab.', es: 'Un hito posterior al invierno de 2027 elegido por el usuario representa la ventana editorial de riesgo más temprana ante un deterioro rápido.', nl: 'Een door de gebruiker gekozen ijkpunt na de winter van 2027 markeert bij snelle verslechtering het vroegste redactionele risicovenster.', sv: 'En användarvald kontrollpunkt efter vintern 2027 representerar det tidigaste redaktionella riskfönstret vid snabb försämring.', pl: 'Wybrany przez użytkownika punkt po zimie 2027 roku wyznacza najwcześniejsze redakcyjne okno ryzyka przy szybkim pogorszeniu sytuacji.'),
                'confidence_score' => 44,
                'probability_score' => 65,
                'trend' => 'deteriorating',
                'methodology' => array_merge($methodology, [
                    'scenario_target_year' => 2027,
                    'target_rationale' => '31 March 2027 is a user-directed editorial pessimistic anchor representing a post-winter checkpoint. It is intentionally not derived from the DDIS two-year post-freeze assessment or from any cited official invasion date.',
                    'target_date_semantics' => 'User-selected editorial checkpoint at the end of the first quarter after winter 2026-2027; not an official forecast, readiness date or invasion date.',
                    'source_alignment' => 'No cited source identifies 31 March 2027. Official sources provide strategic context only and do not derive this exact date.',
                    'milestones' => [
                        'Winter 2026-2027 ends without durable de-escalation or an independently verified reduction in regional military pressure.',
                        'Credible official assessments identify material force concentration or readiness deterioration by the end of the first quarter of 2027.',
                        'Immediate European readiness gaps in air defence, ammunition, logistics or military mobility remain unresolved.',
                        'Alliance cohesion or sustained support to Ukraine weakens materially.',
                    ],
                    'limits' => [
                        'No cited source identifies 31 March 2027 as an invasion, readiness or force-release date.',
                        'The DDIS two-year post-freeze clock does not derive this earlier user-selected anchor.',
                        'The date is an editorial pessimistic checkpoint, not evidence of Russian intent or a decision to attack.',
                    ],
                    'stop_conditions' => [
                        'Move the horizon later if no credible official assessment supports such an early deterioration by the end of the first quarter of 2027.',
                        'Move later if Russian forces remain heavily committed in Ukraine and near-term NATO deterrence improves.',
                        'Recalibrate only after direct escalation, major force concentration or a new credible intelligence assessment.',
                    ],
                ]),
                'sort_order' => 3,
            ],
        ];
    }
};
