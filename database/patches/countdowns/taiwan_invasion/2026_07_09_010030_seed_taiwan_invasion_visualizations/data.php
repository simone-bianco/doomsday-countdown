<?php

declare(strict_types=1);

use App\Enums\VisualizationType;

$shared = require __DIR__.'/../_shared.php';

return new class($shared)
{
    public function __construct(private object $shared) {}

    /** @return array<string, mixed> */
    public function projectionCurveVisualization(): array
    {
        return [
            'key' => 'projection_curve',
            'type' => VisualizationType::Line,
            'title' => $this->shared->t(
                en: 'Risk window curve',
                it: 'Curva della finestra di rischio',
                fr: 'Courbe de la fenêtre de risque',
                de: 'Kurve des Risikofensters',
                es: 'Curva de la ventana de riesgo',
                nl: 'Curve van het risicovenster',
                sv: 'Kurva för riskfönstret',
                pl: 'Krzywa okna ryzyka',
            ),
            'description' => $this->shared->t(
                en: 'Editorial probability curve for invasion risk, separating full invasion from blockade and quarantine scenarios.',
                it: 'Curva editoriale di probabilità del rischio invasione, distinta dagli scenari di blocco e quarantena.',
                fr: 'Courbe éditoriale de probabilité du risque d’invasion, séparant l’invasion totale des scénarios de blocus et de quarantaine.',
                de: 'Redaktionelle Wahrscheinlichkeitskurve für das Invasionsrisiko, getrennt von Blockade- und Quarantäneszenarien.',
                es: 'Curva editorial de probabilidad del riesgo de invasión, separando la invasión total de los escenarios de bloqueo y cuarentena.',
                nl: 'Redactionele waarschijnlijkheidscurve voor invasierisico, gescheiden van blokkade- en quarantainescenario’s.',
                sv: 'Redaktionell sannolikhetskurva för invasionsrisk, skild från blockad- och karantänscenarier.',
                pl: 'Redakcyjna krzywa prawdopodobieństwa ryzyka inwazji, oddzielająca pełną inwazję od scenariuszy blokady i kwarantanny.',
            ),
            'payload' => [
                'unit' => '% invasion probability',
                'labels' => ['2026-Q3', '2027-Q1', '2027-Q4', '2028-Q4', '2029-Q1'],
                'series' => [
                    ['name' => 'Pessimistic', 'color' => '#ff2a23', 'values' => [18, 24, 28, 24, 20]],
                    ['name' => 'Optimistic', 'color' => '#22c55e', 'values' => [8, 10, 12, 10, 8]],
                    ['name' => 'Neutral', 'color' => '#38bdf8', 'values' => [12, 16, 18, 16, 12]],
                ],
                'sources' => [$this->shared->sources()['dod'], $this->shared->sources()['reuters_status_quo']],
            ],
            'schema_version' => 1,
            'sort_order' => 1,
        ];
    }

    /** @return array<int, array<string, mixed>> */
    public function visualizations(): array
    {
        return [
            [
                'key' => 'key_indicators',
                'type' => VisualizationType::Kpi,
                'title' => $this->shared->t(
                    en: 'Key indicators',
                    it: 'Indicatori chiave',
                    fr: 'Indicateurs clés',
                    de: 'Schlüsselindikatoren',
                    es: 'Indicadores clave',
                    nl: 'Kernindicatoren',
                    sv: 'Nyckelindikatorer',
                    pl: 'Kluczowe wskaźniki',
                ),
                'description' => $this->shared->t(
                    en: 'Public-source pressure signals used by the editorial risk model.',
                    it: 'Segnali da fonti pubbliche usati dal modello editoriale di rischio.',
                    fr: 'Signaux de pression de sources publiques utilisés par le modèle éditorial de risque.',
                    de: 'Drucksignale aus öffentlichen Quellen, die vom redaktionellen Risikomodell verwendet werden.',
                    es: 'Señales de presión de fuentes públicas usadas por el modelo editorial de riesgo.',
                    nl: 'Druksignalen uit openbare bronnen die door het redactionele risicomodel worden gebruikt.',
                    sv: 'Trycksignaler från öppna källor som används av den redaktionella riskmodellen.',
                    pl: 'Sygnały presji ze źródeł publicznych używane przez redakcyjny model ryzyka.',
                ),
                'payload' => [
                    'items' => [
                        ['label' => 'PLA ADIZ activity 2024', 'value' => '2,771', 'direction' => 'up', 'sparkline' => [22, 33, 31, 50, 79], 'source' => $this->shared->sources()['dod']],
                        ['label' => 'Taiwan Strait trade at risk', 'value' => 'US$2.45T', 'direction' => 'up', 'sparkline' => [30, 44, 56, 70, 82], 'source' => $this->shared->sources()['csis_trade']],
                        ['label' => 'Leading-edge chips in Taiwan', 'value' => '>90%', 'direction' => 'up', 'sparkline' => [88, 90, 91, 92, 93], 'source' => $this->shared->sources()['trade_semiconductors']],
                        ['label' => 'Taiwan energy import dependence', 'value' => '95%', 'direction' => 'up', 'sparkline' => [90, 92, 94, 95, 95], 'source' => $this->shared->sources()['energy_resilience']],
                        ['label' => 'Drone special budget', 'value' => 'NT$210B', 'direction' => 'up', 'sparkline' => [20, 34, 51, 66, 82], 'source' => $this->shared->sources()['reuters_drone']],
                    ],
                ],
                'schema_version' => 1,
                'sort_order' => 1,
            ],
            $this->shared->lineVisualization(
                'pla_pressure_trend',
                $this->shared->t(
                    en: 'PLA pressure trend',
                    it: 'Trend pressione PLA',
                    fr: 'Tendance de pression de l’APL',
                    de: 'Trend des PLA-Drucks',
                    es: 'Tendencia de presión del EPL',
                    nl: 'Trend in PLA-druk',
                    sv: 'Trend för PLA-tryck',
                    pl: 'Trend presji PLA',
                ),
                $this->shared->t(
                    en: 'Annual Taiwan ADIZ activity reported in the China Military Power Report.',
                    it: 'Attività annuale ADIZ di Taiwan riportata nel China Military Power Report.',
                    fr: 'Activité annuelle de l’ADIZ de Taïwan rapportée dans le China Military Power Report.',
                    de: 'Jährliche Taiwan-ADIZ-Aktivität laut China Military Power Report.',
                    es: 'Actividad anual de la ADIZ de Taiwán reportada en el China Military Power Report.',
                    nl: 'Jaarlijkse Taiwan-ADIZ-activiteit gerapporteerd in het China Military Power Report.',
                    sv: 'Årlig taiwanesisk ADIZ-aktivitet rapporterad i China Military Power Report.',
                    pl: 'Roczna aktywność ADIZ Tajwanu raportowana w China Military Power Report.',
                ),
                ['2021', '2022', '2023', '2024'],
                [972, 1733, 1703, 2771],
                'activity count',
                [$this->shared->sources()['dod']],
                2,
            ),
            $this->shared->lineVisualization(
                'naval_pressure_2026',
                $this->shared->t(
                    en: 'Naval pressure 2026',
                    it: 'Pressione navale 2026',
                    fr: 'Pression navale 2026',
                    de: 'Maritimer Druck 2026',
                    es: 'Presión naval 2026',
                    nl: 'Marinedruk 2026',
                    sv: 'Marint tryck 2026',
                    pl: 'Presja morska 2026',
                ),
                $this->shared->t(
                    en: 'Display baseline versus July 2026 reports of more than 110 military and coast guard ships in the region.',
                    it: 'Baseline espositiva contro i report di luglio 2026 su oltre 110 navi militari e della guardia costiera nella regione.',
                    fr: 'Référence visuelle comparée aux rapports de juillet 2026 signalant plus de 110 navires militaires et garde-côtes dans la région.',
                    de: 'Anzeigebasis im Vergleich zu Berichten vom Juli 2026 über mehr als 110 Militär- und Küstenwachschiffe in der Region.',
                    es: 'Referencia visual frente a informes de julio de 2026 de más de 110 buques militares y de guardia costera en la región.',
                    nl: 'Weergavebasis tegenover rapporten van juli 2026 over meer dan 110 militaire en kustwachtschepen in de regio.',
                    sv: 'Visningsbaslinje jämfört med rapporter från juli 2026 om fler än 110 militära och kustbevakningsfartyg i regionen.',
                    pl: 'Bazowy poziom prezentacji wobec raportów z lipca 2026 r. o ponad 110 okrętach wojskowych i straży przybrzeżnej w regionie.',
                ),
                ['Baseline', 'July 2026'],
                [50, 110],
                'ships',
                [$this->shared->sources()['reuters_naval']],
                3,
            ),
            $this->shared->lineVisualization(
                'economic_exposure',
                $this->shared->t(
                    en: 'Economic exposure',
                    it: 'Esposizione economica',
                    fr: 'Exposition économique',
                    de: 'Wirtschaftliche Exponierung',
                    es: 'Exposición económica',
                    nl: 'Economische blootstelling',
                    sv: 'Ekonomisk exponering',
                    pl: 'Ekspozycja gospodarcza',
                ),
                $this->shared->t(
                    en: 'Trade, annual activity and sanctions shock ranges exposed by Taiwan Strait disruption.',
                    it: 'Commercio, attività annuale e shock da sanzioni esposti a una crisi nello Stretto di Taiwan.',
                    fr: 'Commerce, activité annuelle et fourchettes de choc de sanctions exposés par une perturbation du détroit de Taïwan.',
                    de: 'Handel, Jahresaktivität und Sanktionsschock-Spannen, die durch eine Störung der Taiwanstraße exponiert sind.',
                    es: 'Comercio, actividad anual y rangos de choque por sanciones expuestos por una disrupción del Estrecho de Taiwán.',
                    nl: 'Handel, jaarlijkse activiteit en sanctieschokbereiken blootgesteld door verstoring van de Straat van Taiwan.',
                    sv: 'Handel, årlig aktivitet och sanktionschockintervall som exponeras av en störning i Taiwansundet.',
                    pl: 'Handel, roczna aktywność i zakresy szoku sankcyjnego narażone przez zakłócenia w Cieśninie Tajwańskiej.',
                ),
                ['Strait trade', 'Annual activity at risk', 'Max sanctions shock'],
                [2.45, 2.0, 3.0],
                'US$ trillion',
                [$this->shared->sources()['csis_trade'], $this->shared->sources()['rhodium_disruptions']],
                4,
            ),
            $this->shared->lineVisualization(
                'scenario_gdp_shock',
                $this->shared->t(
                    en: 'Scenario GDP shock',
                    it: 'Shock PIL per scenario',
                    fr: 'Choc de PIB par scénario',
                    de: 'BIP-Schock nach Szenario',
                    es: 'Choque del PIB por escenario',
                    nl: 'BBP-schok per scenario',
                    sv: 'BNP-chock per scenario',
                    pl: 'Szok PKB według scenariusza',
                ),
                $this->shared->t(
                    en: 'Editorial midpoint estimates for first-year Taiwan GDP shock by scenario.',
                    it: 'Stime editoriali mediane dello shock sul PIL taiwanese nel primo anno per scenario.',
                    fr: 'Estimations éditoriales médianes du choc sur le PIB taïwanais la première année par scénario.',
                    de: 'Redaktionelle Mittelpunkt-Schätzungen für Taiwans BIP-Schock im ersten Jahr nach Szenario.',
                    es: 'Estimaciones editoriales de punto medio del choque del PIB taiwanés en el primer año por escenario.',
                    nl: 'Redactionele middelpuntsschattingen voor de Taiwanese BBP-schok in het eerste jaar per scenario.',
                    sv: 'Redaktionella mittpunktsestimat för Taiwans BNP-chock under första året per scenario.',
                    pl: 'Redakcyjne szacunki punktu środkowego szoku PKB Tajwanu w pierwszym roku według scenariusza.',
                ),
                ['Optimistic', 'Neutral', 'Pessimistic'],
                [15, 32, 45],
                '% Taiwan GDP',
                [$this->shared->sources()['rhodium_disruptions']],
                5,
            ),
            $this->shared->lineVisualization(
                'energy_resilience',
                $this->shared->t(
                    en: 'Energy resilience',
                    it: 'Resilienza energetica',
                    fr: 'Résilience énergétique',
                    de: 'Energieresilienz',
                    es: 'Resiliencia energética',
                    nl: 'Energieveerkracht',
                    sv: 'Energiresiliens',
                    pl: 'Odporność energetyczna',
                ),
                $this->shared->t(
                    en: 'Energy import dependence, oil and gas dependence, and displayed LNG reserve days.',
                    it: 'Dipendenza energetica, dipendenza da petrolio e gas e giorni di riserva LNG visualizzati.',
                    fr: 'Dépendance aux importations d’énergie, dépendance au pétrole et au gaz, et jours de réserve GNL affichés.',
                    de: 'Energieimportabhängigkeit, Öl- und Gasabhängigkeit sowie angezeigte LNG-Reservetage.',
                    es: 'Dependencia de importación energética, dependencia de petróleo y gas, y días de reserva de GNL mostrados.',
                    nl: 'Afhankelijkheid van energie-import, olie- en gasafhankelijkheid en weergegeven LNG-reservedagen.',
                    sv: 'Energimportberoende, olje- och gasberoende samt visade LNG-reservdagar.',
                    pl: 'Zależność od importu energii, ropy i gazu oraz prezentowane dni rezerw LNG.',
                ),
                ['Energy import dependence', 'Oil/gas import dependence', 'LNG reserve days'],
                [95, 99, 12],
                'value',
                [$this->shared->sources()['energy_resilience']],
                6,
            ),
        ];
    }
};
