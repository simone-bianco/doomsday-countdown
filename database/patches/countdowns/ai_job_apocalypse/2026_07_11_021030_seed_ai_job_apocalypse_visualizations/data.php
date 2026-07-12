<?php

declare(strict_types=1);

use App\Enums\VisualizationType;
use Carbon\CarbonImmutable;

$shared = require __DIR__.'/../_shared.php';

return new class($shared)
{
    public function __construct(private object $shared) {}

    public function projectionBaselineDate(): string
    {
        return '2025-12-31 23:59:59';
    }

    /** @return array<int, string> */
    public function projectionCheckpoints(): array
    {
        return [
            $this->projectionBaselineDate(),
            '2027-12-02 23:59:59',
            '2030-12-31 23:59:59',
            '2035-12-31 23:59:59',
        ];
    }

    /** @return array{Pessimistic: string, Neutral: string, Optimistic: string} */
    public function scenarioTargetDates(): array
    {
        return [
            'Pessimistic' => '2027-12-02 23:59:59',
            'Neutral' => '2030-12-31 23:59:59',
            'Optimistic' => '2035-12-31 23:59:59',
        ];
    }

    /** @return array<string, array<int, float>> */
    public function scenarioProgressValues(): array
    {
        $baseline = CarbonImmutable::parse($this->projectionBaselineDate(), 'UTC');
        $checkpoints = array_map(
            static fn (string $date): CarbonImmutable => CarbonImmutable::parse($date, 'UTC'),
            $this->projectionCheckpoints(),
        );

        $series = [];
        foreach ($this->scenarioTargetDates() as $name => $targetDate) {
            $target = CarbonImmutable::parse($targetDate, 'UTC');
            $duration = max(1, $target->getTimestamp() - $baseline->getTimestamp());
            $series[$name] = array_map(
                static fn (CarbonImmutable $checkpoint): float => round(
                    min(1, max(0, ($checkpoint->getTimestamp() - $baseline->getTimestamp()) / $duration)) * 100,
                    1,
                ),
                $checkpoints,
            );
        }

        return $series;
    }

    /** @return array<string, array{g3: float, g4: float}> */
    public function weightedExposureInputs(): array
    {
        return [
            'Male' => ['g3' => 3.1, 'g4' => 2.4],
            'Female' => ['g3' => 5.7, 'g4' => 4.7],
        ];
    }

    /** @return array{g3: float, g4: float} */
    public function weightedExposureScores(): array
    {
        return ['g3' => 0.55, 'g4' => 0.60];
    }

    /** @return array<string, array{level_2025: float, increase_from_2024: float}> */
    public function leadingAdopterInputs(): array
    {
        return [
            'Denmark' => ['level_2025' => 42.0, 'increase_from_2024' => 14.5],
            'Finland' => ['level_2025' => 37.8, 'increase_from_2024' => 13.5],
        ];
    }

    /** @return array<string, float> */
    public function reskillingInputs(): array
    {
        return ['employees_reporting_need' => 39.0, 'employees_trained' => 12.0];
    }

    /** @return array<string, mixed> */
    public function projectionCurveVisualization(): array
    {
        $progress = $this->scenarioProgressValues();
        $series = array_map(
            static fn (string $name, array $values): array => ['name' => $name, 'values' => $values],
            array_keys($progress),
            array_values($progress),
        );
        $labels = array_map(
            static fn (string $date): string => CarbonImmutable::parse($date, 'UTC')->format('Y-m-d'),
            $this->projectionCheckpoints(),
        );

        return $this->shared->chartVisualization(
            key: 'projection_curve',
            type: VisualizationType::Line,
            title: $this->shared->t(
                en: 'Verified AI-work milestone horizons',
                it: 'Orizzonti verificati delle milestone IA e lavoro',
                fr: 'Horizons vérifiés des jalons IA et travail',
                de: 'Verifizierte Meilensteine für KI und Arbeit',
                es: 'Horizontes verificados de hitos de IA y trabajo',
                nl: 'Geverifieerde mijlpalen voor AI en werk',
                sv: 'Verifierade milstolpar för AI och arbete',
                pl: 'Zweryfikowane horyzonty kamieni milowych AI i pracy',
            ),
            description: $this->shared->t(
                en: 'A normalized timeline toward the 2027 regulatory, 2030 adoption-and-skills and 2035 labour-market-adjustment checkpoints.',
                it: 'Una timeline normalizzata verso i checkpoint regolatorio 2027, adozione e competenze 2030 e adattamento del lavoro 2035.',
                fr: 'Une chronologie normalisée vers les jalons réglementaire 2027, adoption et compétences 2030 et ajustement du travail 2035.',
                de: 'Eine normalisierte Zeitachse zu den Regulierungspunkten 2027, Adoption und Qualifikation 2030 sowie Arbeitsmarktanpassung 2035.',
                es: 'Una línea temporal normalizada hacia los puntos de regulación 2027, adopción y competencias 2030 y ajuste laboral 2035.',
                nl: 'Een genormaliseerde tijdlijn naar regulering in 2027, adoptie en vaardigheden in 2030 en arbeidsmarktaanpassing in 2035.',
                sv: 'En normaliserad tidslinje mot regleringspunkten 2027, adoption och kompetens 2030 samt arbetsmarknadsanpassning 2035.',
                pl: 'Znormalizowana oś czasu do punktów regulacyjnych 2027, wdrożeń i umiejętności 2030 oraz dostosowania rynku pracy 2035.',
            ),
            reasoning: $this->shared->t(
                en: 'Temporal normalization, not a forecast of employment loss. Baseline: 31 December 2025. For each scenario and checkpoint, progress = round(100 × clamp((checkpoint_timestamp − baseline_timestamp) ÷ (target_timestamp − baseline_timestamp), 0, 1), 1). Targets are 2 December 2027 for the pessimistic EU AI Act employment-rule checkpoint, 31 December 2030 for the neutral EU Digital Decade and OECD scenario horizon, and 31 December 2035 for the optimistic Cedefop labour-market adjustment horizon. Values only show elapsed share of each interval; they do not combine exposure, adoption, automation or job-loss percentages.',
                it: 'Normalizzazione temporale, non previsione di perdita occupazionale. Baseline: 31 dicembre 2025. Per ogni scenario e checkpoint: progresso = round(100 × clamp((timestamp_checkpoint − timestamp_baseline) ÷ (timestamp_target − timestamp_baseline), 0, 1), 1). Target: 2 dicembre 2027 per il checkpoint pessimistico delle regole UE sull’IA nel lavoro, 31 dicembre 2030 per l’orizzonte neutrale del Decennio digitale UE e degli scenari OECD, 31 dicembre 2035 per l’orizzonte ottimistico di adattamento Cedefop. I valori mostrano solo la quota temporale trascorsa; non combinano esposizione, adozione, automazione o perdita di posti.',
                fr: 'Normalisation temporelle, pas une prévision de pertes d’emploi. Référence: 31 décembre 2025. Pour chaque scénario: progression = round(100 × clamp((timestamp_jalon − timestamp_référence) ÷ (timestamp_cible − timestamp_référence), 0, 1), 1). Cibles: 2 décembre 2027 pour le jalon pessimiste des règles UE sur l’IA dans l’emploi, 31 décembre 2030 pour l’horizon neutre de la Décennie numérique et des scénarios OCDE, 31 décembre 2035 pour l’ajustement optimiste Cedefop. Les valeurs montrent seulement le temps écoulé, sans combiner exposition, adoption, automatisation ou pertes.',
                de: 'Zeitliche Normalisierung, keine Prognose von Jobverlusten. Basis: 31. Dezember 2025. Je Szenario gilt: Fortschritt = round(100 × clamp((Prüfpunkt-Zeitstempel − Basis-Zeitstempel) ÷ (Ziel-Zeitstempel − Basis-Zeitstempel), 0, 1), 1). Ziele: 2. Dezember 2027 für den pessimistischen EU-Regulierungspunkt, 31. Dezember 2030 für den neutralen Digital-Decade- und OECD-Horizont, 31. Dezember 2035 für die optimistische Cedefop-Anpassung. Werte zeigen nur verstrichene Zeit und kombinieren keine Expositions-, Adoptions-, Automatisierungs- oder Jobverlustquoten.',
                es: 'Normalización temporal, no previsión de pérdida de empleo. Base: 31 de diciembre de 2025. Para cada escenario: progreso = round(100 × clamp((timestamp_punto − timestamp_base) ÷ (timestamp_objetivo − timestamp_base), 0, 1), 1). Objetivos: 2 de diciembre de 2027 para el punto pesimista de regulación laboral de IA de la UE, 31 de diciembre de 2030 para el horizonte neutral de la Década Digital y escenarios OCDE, y 31 de diciembre de 2035 para el ajuste optimista de Cedefop. Los valores muestran solo tiempo transcurrido y no mezclan exposición, adopción, automatización o pérdidas.',
                nl: 'Tijdnormalisatie, geen voorspelling van baanverlies. Basis: 31 december 2025. Per scenario: voortgang = round(100 × clamp((tijdstip_ijkpunt − tijdstip_basis) ÷ (tijdstip_doel − tijdstip_basis), 0, 1), 1). Doelen: 2 december 2027 voor het pessimistische EU-reguleringspunt, 31 december 2030 voor de neutrale Digital Decade- en OECD-horizon, en 31 december 2035 voor optimistische Cedefop-aanpassing. Waarden tonen alleen verstreken tijd en combineren geen blootstelling, adoptie, automatisering of baanverlies.',
                sv: 'Tidsnormalisering, inte en prognos över jobbförluster. Baslinje: 31 december 2025. För varje scenario: framsteg = round(100 × clamp((tidpunkt_kontrollpunkt − tidpunkt_baslinje) ÷ (tidpunkt_mål − tidpunkt_baslinje), 0, 1), 1). Mål: 2 december 2027 för EU:s pessimistiska regleringspunkt, 31 december 2030 för neutral Digital Decade- och OECD-horisont, samt 31 december 2035 för optimistisk Cedefop-anpassning. Värden visar endast förfluten tid och blandar inte exponering, adoption, automation eller jobbförlust.',
                pl: 'Normalizacja czasu, nie prognoza utraty pracy. Bazą jest 31 grudnia 2025 r. Dla każdego scenariusza: postęp = round(100 × clamp((czas_punktu − czas_bazy) ÷ (czas_celu − czas_bazy), 0, 1), 1). Cele: 2 grudnia 2027 r. dla pesymistycznego punktu regulacyjnego UE, 31 grudnia 2030 r. dla neutralnego horyzontu Cyfrowej Dekady i OECD oraz 31 grudnia 2035 r. dla optymistycznego dostosowania Cedefop. Wartości pokazują tylko upływ czasu i nie łączą ekspozycji, wdrożeń, automatyzacji ani utraty miejsc pracy.',
            ),
            labels: $labels,
            series: $series,
            xLabel: 'Verified checkpoint date',
            xType: 'temporal',
            yLabel: 'Elapsed share of scenario horizon',
            yUnit: '% of scenario interval',
            yFormat: 'percent',
            sources: [
                $this->shared->sources()['ilo_index'],
                $this->shared->sources()['eurostat_2025'],
                $this->shared->sources()['eibis_2025'],
                $this->shared->sources()['eu_ai_act'],
                $this->shared->sources()['eu_digital_decade'],
                $this->shared->sources()['oecd_ai_trajectories_2030'],
                $this->shared->sources()['cedefop_ai_2035'],
                $this->shared->sources()['bls_ai_2033'],
            ],
            sortOrder: 1,
        );
    }

    /** @return array<int, array<string, mixed>> */
    public function visualizations(): array
    {
        $adopters = $this->leadingAdopterInputs();
        $weightedInputs = $this->weightedExposureInputs();
        $weightedScores = $this->weightedExposureScores();
        $reskilling = $this->reskillingInputs();

        $weightedValues = array_map(
            static fn (array $input): float => round(
                ($input['g3'] * $weightedScores['g3']) + ($input['g4'] * $weightedScores['g4']),
                2,
            ),
            $weightedInputs,
        );

        return [
            $this->shared->kpiVisualization(
                key: 'key_indicators',
                title: $this->shared->t(
                    en: 'Key indicators: different questions, not one forecast',
                    it: 'Indicatori chiave: domande diverse, non un’unica previsione',
                    fr: 'Indicateurs clés: questions différentes, pas une prévision unique',
                    de: 'Kernindikatoren: unterschiedliche Fragen, keine Gesamtprognose',
                    es: 'Indicadores clave: preguntas distintas, no una única previsión',
                    nl: 'Kernindicatoren: verschillende vragen, geen enkele voorspelling',
                    sv: 'Nyckelindikatorer: olika frågor, inte en enda prognos',
                    pl: 'Kluczowe wskaźniki: różne pytania, nie jedna prognoza',
                ),
                description: $this->shared->t(
                    en: 'Exposure, highest-exposure employment, enterprise adoption and automation risk use different populations and definitions.',
                    it: 'Esposizione, occupazione ad alta esposizione, adozione d’impresa e rischio automazione usano popolazioni e definizioni diverse.',
                    fr: 'Exposition, emploi fortement exposé, adoption des entreprises et risque d’automatisation utilisent des populations et définitions différentes.',
                    de: 'Exposition, höchste Exposition, Unternehmensadoption und Automatisierungsrisiko nutzen verschiedene Grundgesamtheiten und Definitionen.',
                    es: 'Exposición, empleo de alta exposición, adopción empresarial y riesgo de automatización usan poblaciones y definiciones distintas.',
                    nl: 'Blootstelling, hoogste blootstelling, bedrijfsadoptie en automatiseringsrisico gebruiken verschillende populaties en definities.',
                    sv: 'Exponering, högsta exponering, företagsadoption och automatiseringsrisk använder olika populationer och definitioner.',
                    pl: 'Ekspozycja, najwyższa ekspozycja, wdrożenia firm i ryzyko automatyzacji używają różnych populacji i definicji.',
                ),
                reasoning: $this->shared->t(
                    en: 'Observed headline values transcribed from the cited institutions: ILO reports about one in four jobs with some GenAI exposure and 3.3% in the highest global exposure gradient; Eurostat reports 20.0% of EU enterprises with 10+ employees using AI in 2025; OECD estimates 27% of OECD employment in occupations at highest automation risk when all automation technologies are considered. These indicators are intentionally separate: exposure is not automation, displacement or net job loss.',
                    it: 'Valori osservati trascritti dalle istituzioni citate: ILO indica circa un lavoro su quattro con qualche esposizione alla GenAI e il 3,3% nel gradiente globale più alto; Eurostat il 20,0% delle imprese UE con almeno 10 addetti che usa IA nel 2025; OECD il 27% dell’occupazione in professioni a più alto rischio considerando tutte le tecnologie di automazione. Gli indicatori restano separati: esposizione non equivale ad automazione, spiazzamento o perdita netta.',
                    fr: 'Valeurs observées des institutions citées: l’OIT indique environ un emploi sur quatre exposé à la GenAI et 3,3% dans le gradient mondial le plus élevé; Eurostat 20,0% des entreprises de l’UE de 10 salariés ou plus utilisant l’IA en 2025; l’OCDE 27% de l’emploi dans les professions au risque d’automatisation le plus élevé en considérant toutes les technologies. Exposition ne signifie pas automatisation, déplacement ou perte nette.',
                    de: 'Beobachtete Werte der zitierten Institutionen: ILO etwa jeder vierte Job mit GenAI-Exposition und 3,3% im höchsten globalen Gradienten; Eurostat 20,0% der EU-Unternehmen ab 10 Beschäftigten mit KI-Nutzung 2025; OECD 27% der Beschäftigung in Berufen mit höchstem Automatisierungsrisiko unter allen Technologien. Exposition ist nicht Automatisierung, Verdrängung oder Nettoverlust.',
                    es: 'Valores observados de las instituciones citadas: OIT, uno de cada cuatro empleos con alguna exposición y 3,3% en el gradiente mundial más alto; Eurostat, 20,0% de empresas UE de 10 o más empleados usando IA en 2025; OCDE, 27% del empleo en ocupaciones con mayor riesgo al considerar todas las tecnologías de automatización. Exposición no equivale a automatización, desplazamiento o pérdida neta.',
                    nl: 'Waargenomen waarden van de aangehaalde instellingen: ILO ongeveer één op vier banen met enige GenAI-blootstelling en 3,3% in de hoogste mondiale categorie; Eurostat 20,0% van EU-bedrijven met 10+ werknemers die in 2025 AI gebruiken; OECD 27% van de werkgelegenheid in beroepen met het hoogste automatiseringsrisico bij alle technologieën. Blootstelling is niet automatisering, verdringing of nettoverlies.',
                    sv: 'Observerade värden från citerade institutioner: ILO cirka ett av fyra jobb med viss GenAI-exponering och 3,3% i högsta globala gradienten; Eurostat 20,0% av EU-företag med minst 10 anställda som använde AI 2025; OECD 27% av sysselsättningen i yrken med högst automationsrisk när all automation räknas. Exponering är inte automation, undanträngning eller nettoförlust.',
                    pl: 'Zaobserwowane wartości z cytowanych instytucji: ILO około jednego na cztery miejsca pracy z ekspozycją na GenAI i 3,3% w najwyższej globalnej kategorii; Eurostat 20,0% firm UE z co najmniej 10 pracownikami używających AI w 2025; OECD 27% zatrudnienia w zawodach o najwyższym ryzyku automatyzacji przy wszystkich technologiach. Ekspozycja nie oznacza automatyzacji, wypierania ani straty netto.',
                ),
                items: [
                    ['label' => 'Jobs with some GenAI exposure', 'value' => 'about 1 in 4'],
                    ['label' => 'Global employment in highest exposure gradient', 'value' => '3.3%'],
                    ['label' => 'EU enterprises using AI, 2025', 'value' => '20.0%'],
                    ['label' => 'OECD employment at highest automation risk, all technologies', 'value' => '27%'],
                ],
                sources: [
                    $this->shared->sources()['ilo_index'],
                    $this->shared->sources()['eurostat_2025'],
                    $this->shared->sources()['oecd_ai_jobs'],
                ],
                sortOrder: 1,
            ),
            $this->shared->chartVisualization(
                key: 'eu_enterprise_ai_adoption',
                type: VisualizationType::Line,
                title: $this->shared->t(
                    en: 'AI use in EU enterprises',
                    it: 'Uso dell’IA nelle imprese UE',
                    fr: 'Utilisation de l’IA dans les entreprises de l’UE',
                    de: 'KI-Nutzung in EU-Unternehmen',
                    es: 'Uso de IA en empresas de la UE',
                    nl: 'AI-gebruik in EU-bedrijven',
                    sv: 'AI-användning i EU-företag',
                    pl: 'Wykorzystanie AI w firmach UE',
                ),
                description: $this->shared->t(
                    en: 'Observed share of EU enterprises with at least 10 employees using one or more AI technologies.',
                    it: 'Quota osservata di imprese UE con almeno 10 addetti che usa una o più tecnologie IA.',
                    fr: 'Part observée des entreprises de l’UE d’au moins 10 salariés utilisant une ou plusieurs technologies d’IA.',
                    de: 'Beobachteter Anteil der EU-Unternehmen ab 10 Beschäftigten mit mindestens einer KI-Technologie.',
                    es: 'Porcentaje observado de empresas UE con al menos 10 empleados que usa una o más tecnologías de IA.',
                    nl: 'Waargenomen aandeel EU-bedrijven met minstens 10 werknemers dat één of meer AI-technologieën gebruikt.',
                    sv: 'Observerad andel EU-företag med minst 10 anställda som använder en eller flera AI-tekniker.',
                    pl: 'Zaobserwowany odsetek firm UE z co najmniej 10 pracownikami używających jednej lub więcej technologii AI.',
                ),
                reasoning: $this->shared->t(
                    en: 'Observed Eurostat values for the same enterprise-size scope: 7.7% in 2021, 8.1% in 2023, 13.5% in 2024 and 20.0% in 2025. The missing 2022 point is not interpolated. Adoption measures enterprise use, not the share of jobs automated or displaced.',
                    it: 'Valori Eurostat osservati per lo stesso perimetro dimensionale: 7,7% nel 2021, 8,1% nel 2023, 13,5% nel 2024 e 20,0% nel 2025. Il 2022 mancante non è interpolato. L’adozione misura l’uso nelle imprese, non i posti automatizzati o spiazzati.',
                    fr: 'Valeurs Eurostat observées pour le même périmètre: 7,7% en 2021, 8,1% en 2023, 13,5% en 2024 et 20,0% en 2025. Le point 2022 absent n’est pas interpolé. L’adoption mesure l’usage des entreprises, pas les emplois automatisés ou déplacés.',
                    de: 'Beobachtete Eurostat-Werte für dieselbe Unternehmensgröße: 7,7% 2021, 8,1% 2023, 13,5% 2024 und 20,0% 2025. 2022 wird nicht interpoliert. Adoption misst Unternehmensnutzung, nicht automatisierte oder verdrängte Jobs.',
                    es: 'Valores Eurostat observados para el mismo tamaño empresarial: 7,7% en 2021, 8,1% en 2023, 13,5% en 2024 y 20,0% en 2025. No se interpola 2022. La adopción mide uso empresarial, no empleos automatizados o desplazados.',
                    nl: 'Waargenomen Eurostat-waarden voor dezelfde bedrijfsgrootte: 7,7% in 2021, 8,1% in 2023, 13,5% in 2024 en 20,0% in 2025. 2022 wordt niet geïnterpoleerd. Adoptie meet bedrijfsgebruik, niet geautomatiseerde of verdrongen banen.',
                    sv: 'Observerade Eurostat-värden för samma företagsstorlek: 7,7% 2021, 8,1% 2023, 13,5% 2024 och 20,0% 2025. År 2022 interpoleras inte. Adoption mäter företagsanvändning, inte automatiserade eller undanträngda jobb.',
                    pl: 'Zaobserwowane wartości Eurostatu dla tego samego zakresu firm: 7,7% w 2021, 8,1% w 2023, 13,5% w 2024 i 20,0% w 2025. Brakujący 2022 nie jest interpolowany. Wdrożenie mierzy użycie w firmach, nie automatyzację ani wypieranie miejsc pracy.',
                ),
                labels: ['2021', '2023', '2024', '2025'],
                series: [7.7, 8.1, 13.5, 20.0],
                xLabel: 'Year',
                xType: 'temporal',
                yLabel: 'Enterprises using AI',
                yUnit: '% of enterprises with 10+ employees',
                yFormat: 'percent',
                sources: [$this->shared->sources()['eurostat_2025'], $this->shared->sources()['eurostat_dataset']],
                sortOrder: 2,
            ),
            $this->shared->chartVisualization(
                key: 'leading_ai_adopters',
                type: VisualizationType::Line,
                title: $this->shared->t(
                    en: 'Leading EU adopters: reconstructed 2024–2025 change',
                    it: 'Leader UE: variazione 2024–2025 ricostruita',
                    fr: 'Leaders de l’UE: évolution 2024–2025 reconstruite',
                    de: 'Führende EU-Länder: rekonstruierte Veränderung 2024–2025',
                    es: 'Líderes de la UE: cambio 2024–2025 reconstruido',
                    nl: 'EU-koplopers: gereconstrueerde verandering 2024–2025',
                    sv: 'Ledande EU-länder: rekonstruerad förändring 2024–2025',
                    pl: 'Liderzy UE: odtworzona zmiana 2024–2025',
                ),
                description: $this->shared->t(
                    en: 'Observed 2025 levels and 2024 levels derived from Eurostat’s reported percentage-point increases.',
                    it: 'Livelli 2025 osservati e livelli 2024 derivati dagli aumenti in punti percentuali riportati da Eurostat.',
                    fr: 'Niveaux 2025 observés et niveaux 2024 dérivés des hausses en points de pourcentage publiées par Eurostat.',
                    de: 'Beobachtete Werte 2025 und aus den Eurostat-Anstiegen in Prozentpunkten abgeleitete Werte 2024.',
                    es: 'Niveles 2025 observados y niveles 2024 derivados de los aumentos en puntos porcentuales de Eurostat.',
                    nl: 'Waargenomen niveaus 2025 en uit Eurostat-procentpuntstijgingen afgeleide niveaus 2024.',
                    sv: 'Observerade nivåer 2025 och 2024-nivåer härledda från Eurostats ökningar i procentenheter.',
                    pl: 'Zaobserwowane poziomy 2025 i poziomy 2024 wyliczone ze wzrostów w punktach procentowych Eurostatu.',
                ),
                reasoning: $this->shared->t(
                    en: 'Mixed observed/derived series. Eurostat reports 2025 levels of 42.0% for Denmark and 37.8% for Finland, with increases of 14.5 and 13.5 percentage points from 2024. Formula: level_2024 = level_2025 − increase_pp, yielding 27.5% and 24.3%. No other years are estimated.',
                    it: 'Serie osservata/derivata. Eurostat riporta nel 2025 42,0% per la Danimarca e 37,8% per la Finlandia, con aumenti di 14,5 e 13,5 punti dal 2024. Formula: livello_2024 = livello_2025 − aumento_pp, pari a 27,5% e 24,3%. Nessun altro anno è stimato.',
                    fr: 'Série observée/dérivée. Eurostat publie 42,0% au Danemark et 37,8% en Finlande en 2025, avec +14,5 et +13,5 points depuis 2024. Formule: niveau_2024 = niveau_2025 − hausse_pp, soit 27,5% et 24,3%. Aucune autre année n’est estimée.',
                    de: 'Beobachtete/abgeleitete Reihe. Eurostat meldet 2025 42,0% für Dänemark und 37,8% für Finnland, mit +14,5 bzw. +13,5 Prozentpunkten seit 2024. Formel: Wert_2024 = Wert_2025 − Anstieg_pp, also 27,5% und 24,3%. Keine weiteren Jahre werden geschätzt.',
                    es: 'Serie observada/derivada. Eurostat informa 42,0% para Dinamarca y 37,8% para Finlandia en 2025, con aumentos de 14,5 y 13,5 puntos desde 2024. Fórmula: nivel_2024 = nivel_2025 − aumento_pp, dando 27,5% y 24,3%. No se estiman otros años.',
                    nl: 'Waargenomen/afgeleide reeks. Eurostat meldt voor 2025 42,0% in Denemarken en 37,8% in Finland, met +14,5 en +13,5 procentpunt sinds 2024. Formule: niveau_2024 = niveau_2025 − stijging_pp, dus 27,5% en 24,3%. Andere jaren worden niet geschat.',
                    sv: 'Observerad/härledd serie. Eurostat anger 42,0% för Danmark och 37,8% för Finland 2025, med ökningar på 14,5 och 13,5 procentenheter från 2024. Formel: nivå_2024 = nivå_2025 − ökning_pp, vilket ger 27,5% och 24,3%. Inga andra år skattas.',
                    pl: 'Seria obserwowana/pochodna. Eurostat podaje 42,0% dla Danii i 37,8% dla Finlandii w 2025 oraz wzrosty o 14,5 i 13,5 pkt proc. od 2024. Wzór: poziom_2024 = poziom_2025 − wzrost_pp, co daje 27,5% i 24,3%. Innych lat nie szacuje się.',
                ),
                labels: ['2024', '2025'],
                series: [
                    ['name' => 'Denmark', 'values' => [round($adopters['Denmark']['level_2025'] - $adopters['Denmark']['increase_from_2024'], 1), $adopters['Denmark']['level_2025']]],
                    ['name' => 'Finland', 'values' => [round($adopters['Finland']['level_2025'] - $adopters['Finland']['increase_from_2024'], 1), $adopters['Finland']['level_2025']]],
                ],
                xLabel: 'Year',
                xType: 'temporal',
                yLabel: 'Enterprises using AI',
                yUnit: '% of enterprises with 10+ employees',
                yFormat: 'percent',
                sources: [$this->shared->sources()['eurostat_2025']],
                sortOrder: 3,
            ),
            $this->shared->chartVisualization(
                key: 'exposure_by_income_group',
                type: VisualizationType::Bar,
                title: $this->shared->t(
                    en: 'GenAI exposure by country income group',
                    it: 'Esposizione alla GenAI per gruppo di reddito',
                    fr: 'Exposition à la GenAI par groupe de revenu',
                    de: 'GenAI-Exposition nach Länder-Einkommensgruppe',
                    es: 'Exposición a GenAI por grupo de ingresos',
                    nl: 'GenAI-blootstelling naar inkomensgroep',
                    sv: 'GenAI-exponering efter inkomstgrupp',
                    pl: 'Ekspozycja na GenAI według grupy dochodowej',
                ),
                description: $this->shared->t(
                    en: 'ILO estimates of employment with some exposure gradient, rounded at the published level.',
                    it: 'Stime ILO dell’occupazione con un livello di esposizione, arrotondate come pubblicate.',
                    fr: 'Estimations OIT de l’emploi présentant un gradient d’exposition, arrondies au niveau publié.',
                    de: 'ILO-Schätzungen der Beschäftigung mit einem Expositionsgrad, entsprechend der Veröffentlichung gerundet.',
                    es: 'Estimaciones OIT del empleo con algún gradiente de exposición, redondeadas al nivel publicado.',
                    nl: 'ILO-schattingen van werkgelegenheid met enige blootstellingsgraad, afgerond zoals gepubliceerd.',
                    sv: 'ILO-skattningar av sysselsättning med någon exponeringsgrad, avrundade enligt publikationen.',
                    pl: 'Szacunki ILO zatrudnienia z pewnym poziomem ekspozycji, zaokrąglone jak w publikacji.',
                ),
                reasoning: $this->shared->t(
                    en: 'Observed ILO estimates: about 11% in low-income countries, about one in four globally (displayed as 25%), and 34% in high-income countries. The categories use the same GenAI occupational-exposure framework. Exposure is an upper-bound potential for task transformation and does not mean that the same share of jobs will be automated or lost.',
                    it: 'Stime ILO osservate: circa 11% nei paesi a basso reddito, circa un lavoro su quattro a livello globale (mostrato come 25%) e 34% nei paesi ad alto reddito. Le categorie usano lo stesso quadro di esposizione professionale. Esposizione è potenziale di trasformazione, non quota di posti automatizzati o persi.',
                    fr: 'Estimations OIT observées: environ 11% dans les pays à faible revenu, un emploi sur quatre dans le monde (affiché 25%) et 34% dans les pays à revenu élevé. Même cadre d’exposition professionnelle. L’exposition est un potentiel de transformation, pas une part d’emplois automatisés ou perdus.',
                    de: 'Beobachtete ILO-Schätzungen: etwa 11% in Niedrigeinkommensländern, weltweit etwa jeder vierte Job (als 25% dargestellt) und 34% in Hocheinkommensländern. Die Kategorien nutzen denselben Expositionsrahmen. Exposition ist Transformationspotenzial, nicht Anteil automatisierter oder verlorener Jobs.',
                    es: 'Estimaciones observadas de la OIT: cerca del 11% en países de bajos ingresos, uno de cada cuatro empleos globalmente (25% en el gráfico) y 34% en países de altos ingresos. Se usa el mismo marco de exposición. Exposición es potencial de transformación, no cuota de empleos automatizados o perdidos.',
                    nl: 'Waargenomen ILO-schattingen: circa 11% in lage-inkomenslanden, mondiaal ongeveer één op vier banen (25% weergegeven) en 34% in hoge-inkomenslanden. Dezelfde blootstellingsmethode wordt gebruikt. Blootstelling is transformatiepotentieel, niet het aandeel geautomatiseerde of verloren banen.',
                    sv: 'Observerade ILO-skattningar: cirka 11% i låginkomstländer, globalt ungefär ett av fyra jobb (visat som 25%) och 34% i höginkomstländer. Samma exponeringsram används. Exponering är omställningspotential, inte andelen automatiserade eller förlorade jobb.',
                    pl: 'Zaobserwowane szacunki ILO: około 11% w krajach o niskich dochodach, globalnie około jedno na cztery miejsca pracy (pokazane jako 25%) i 34% w krajach wysokodochodowych. Kategorie używają tej samej metody ekspozycji. Ekspozycja to potencjał transformacji, nie udział miejsc zautomatyzowanych lub utraconych.',
                ),
                labels: ['Low-income countries', 'Global', 'High-income countries'],
                series: [11, 25, 34],
                xLabel: 'Income group',
                xType: 'category',
                yLabel: 'Employment with some GenAI exposure',
                yUnit: '% of employment',
                yFormat: 'percent',
                sources: [$this->shared->sources()['ilo_index']],
                sortOrder: 4,
            ),
            $this->shared->chartVisualization(
                key: 'high_exposure_by_gender',
                type: VisualizationType::Bar,
                title: $this->shared->t(
                    en: 'Higher GenAI exposure gradients by gender',
                    it: 'Gradienti di maggiore esposizione per genere',
                    fr: 'Gradients d’exposition élevée selon le genre',
                    de: 'Höhere GenAI-Expositionsgrade nach Geschlecht',
                    es: 'Gradientes de mayor exposición por género',
                    nl: 'Hogere GenAI-blootstellingsgraden naar gender',
                    sv: 'Högre GenAI-exponeringsgrader efter kön',
                    pl: 'Wyższe poziomy ekspozycji na GenAI według płci',
                ),
                description: $this->shared->t(
                    en: 'Global employment shares in ILO exposure gradients 3 and 4.',
                    it: 'Quote globali di occupazione nei gradienti ILO 3 e 4.',
                    fr: 'Parts mondiales de l’emploi dans les gradients OIT 3 et 4.',
                    de: 'Globale Beschäftigungsanteile in den ILO-Expositionsgraden 3 und 4.',
                    es: 'Porcentajes mundiales de empleo en los gradientes OIT 3 y 4.',
                    nl: 'Mondiale werkgelegenheidsaandelen in ILO-graden 3 en 4.',
                    sv: 'Globala sysselsättningsandelar i ILO-gradienterna 3 och 4.',
                    pl: 'Globalne udziały zatrudnienia w poziomach ILO 3 i 4.',
                ),
                reasoning: $this->shared->t(
                    en: 'Observed global ILO shares using the same employment denominator: men 3.1% in gradient 3 and 2.4% in gradient 4; women 5.7% and 4.7%. The chart compares exposure categories, not observed job losses. Differences partly reflect occupational segregation and the concentration of clerical work.',
                    it: 'Quote globali ILO osservate con lo stesso denominatore occupazionale: uomini 3,1% nel gradiente 3 e 2,4% nel 4; donne 5,7% e 4,7%. Il grafico confronta categorie di esposizione, non perdite osservate. Le differenze riflettono anche segregazione professionale e concentrazione del lavoro impiegatizio.',
                    fr: 'Parts mondiales OIT observées avec le même dénominateur: hommes 3,1% au gradient 3 et 2,4% au 4; femmes 5,7% et 4,7%. Le graphique compare des catégories d’exposition, pas des pertes d’emploi. Les écarts reflètent notamment la ségrégation professionnelle et le travail administratif.',
                    de: 'Beobachtete globale ILO-Anteile mit gleichem Beschäftigungsnenner: Männer 3,1% in Grad 3 und 2,4% in Grad 4; Frauen 5,7% und 4,7%. Verglichen werden Expositionskategorien, nicht Jobverluste. Unterschiede spiegeln auch Berufssegregation und Büroarbeit wider.',
                    es: 'Cuotas globales OIT observadas con el mismo denominador: hombres 3,1% en gradiente 3 y 2,4% en 4; mujeres 5,7% y 4,7%. El gráfico compara exposición, no pérdidas de empleo. Las diferencias reflejan segregación ocupacional y concentración del trabajo administrativo.',
                    nl: 'Waargenomen mondiale ILO-aandelen met dezelfde werkgelegenheidsnoemer: mannen 3,1% in graad 3 en 2,4% in graad 4; vrouwen 5,7% en 4,7%. De grafiek vergelijkt blootstelling, niet baanverlies. Verschillen weerspiegelen ook beroepssegregatie en administratief werk.',
                    sv: 'Observerade globala ILO-andelar med samma sysselsättningsnämnare: män 3,1% i gradient 3 och 2,4% i 4; kvinnor 5,7% och 4,7%. Diagrammet jämför exponering, inte jobbförluster. Skillnader speglar också yrkessegregering och kontorsarbete.',
                    pl: 'Zaobserwowane globalne udziały ILO z tym samym mianownikiem zatrudnienia: mężczyźni 3,1% w poziomie 3 i 2,4% w 4; kobiety 5,7% i 4,7%. Wykres porównuje ekspozycję, nie utratę pracy. Różnice odzwierciedlają też segregację zawodową i koncentrację pracy biurowej.',
                ),
                labels: ['Exposure gradient 3', 'Exposure gradient 4'],
                series: [
                    ['name' => 'Male', 'values' => [$weightedInputs['Male']['g3'], $weightedInputs['Male']['g4']]],
                    ['name' => 'Female', 'values' => [$weightedInputs['Female']['g3'], $weightedInputs['Female']['g4']]],
                ],
                xLabel: 'ILO exposure category',
                xType: 'category',
                yLabel: 'Employment in exposure category',
                yUnit: '% of sex-specific employment',
                yFormat: 'percent',
                sources: [$this->shared->sources()['ilo_index'], $this->shared->sources()['ilo_gender']],
                sortOrder: 5,
            ),
            $this->shared->chartVisualization(
                key: 'weighted_high_exposure',
                type: VisualizationType::Bar,
                title: $this->shared->t(
                    en: 'Derived weighted high-exposure comparison',
                    it: 'Confronto derivato dell’alta esposizione ponderata',
                    fr: 'Comparaison dérivée de forte exposition pondérée',
                    de: 'Abgeleiteter gewichteter Vergleich hoher Exposition',
                    es: 'Comparación derivada de alta exposición ponderada',
                    nl: 'Afgeleide gewogen vergelijking van hoge blootstelling',
                    sv: 'Härledd viktad jämförelse av hög exponering',
                    pl: 'Pochodne ważone porównanie wysokiej ekspozycji',
                ),
                description: $this->shared->t(
                    en: 'A transparent lower-bound weighted summary of global gradients 3 and 4, using compatible sex-specific employment shares.',
                    it: 'Sintesi ponderata trasparente e prudenziale dei gradienti globali 3 e 4, con quote occupazionali comparabili per genere.',
                    fr: 'Synthèse pondérée transparente et prudente des gradients mondiaux 3 et 4, avec parts d’emploi comparables selon le genre.',
                    de: 'Transparente konservative gewichtete Zusammenfassung der globalen Grade 3 und 4 mit vergleichbaren geschlechtsspezifischen Anteilen.',
                    es: 'Resumen ponderado transparente y prudente de los gradientes globales 3 y 4 con cuotas comparables por género.',
                    nl: 'Transparante conservatieve gewogen samenvatting van mondiale graden 3 en 4 met vergelijkbare aandelen per gender.',
                    sv: 'Transparent konservativ viktad sammanfattning av globala gradienter 3 och 4 med jämförbara könsspecifika andelar.',
                    pl: 'Przejrzyste konserwatywne ważone podsumowanie globalnych poziomów 3 i 4 z porównywalnymi udziałami według płci.',
                ),
                reasoning: $this->shared->t(
                    en: 'Derived, not published by ILO. Compatible global sex-specific employment shares are weighted with representative lower-bound scores: gradient 3 score 0.55 (midpoint of 0.50–0.60) and gradient 4 score 0.60 (its conservative threshold). Formula: weighted_exposure = share_g3 × 0.55 + share_g4 × 0.60. Male = 3.1×0.55 + 2.4×0.60 = 3.15 points; female = 5.7×0.55 + 4.7×0.60 = 5.96 points. This is a comparison index, not a displacement rate.',
                    it: 'Dato derivato, non pubblicato da ILO. Quote globali comparabili per genere sono ponderate con punteggi prudenziali: gradiente 3 = 0,55 (punto medio 0,50–0,60) e gradiente 4 = 0,60 (soglia conservativa). Formula: esposizione_ponderata = quota_g3×0,55 + quota_g4×0,60. Uomini = 3,15 punti; donne = 5,96. È un indice di confronto, non un tasso di spiazzamento.',
                    fr: 'Valeur dérivée, non publiée par l’OIT. Les parts mondiales comparables selon le genre sont pondérées par 0,55 pour le gradient 3 (milieu de 0,50–0,60) et 0,60 pour le gradient 4 (seuil prudent). Formule: exposition_pondérée = part_g3×0,55 + part_g4×0,60. Hommes = 3,15 points; femmes = 5,96. C’est un indice comparatif, pas un taux de déplacement.',
                    de: 'Abgeleitet, nicht von der ILO veröffentlicht. Vergleichbare globale geschlechtsspezifische Anteile werden mit 0,55 für Grad 3 (Mitte 0,50–0,60) und 0,60 für Grad 4 (konservative Schwelle) gewichtet. Formel: gewichtete_Exposition = Anteil_g3×0,55 + Anteil_g4×0,60. Männer 3,15 Punkte; Frauen 5,96. Vergleichsindex, keine Verdrängungsrate.',
                    es: 'Dato derivado, no publicado por la OIT. Cuotas globales comparables por género se ponderan con 0,55 para gradiente 3 (punto medio 0,50–0,60) y 0,60 para gradiente 4 (umbral prudente). Fórmula: exposición_ponderada = cuota_g3×0,55 + cuota_g4×0,60. Hombres 3,15 puntos; mujeres 5,96. Índice comparativo, no tasa de desplazamiento.',
                    nl: 'Afgeleid, niet door ILO gepubliceerd. Vergelijkbare mondiale aandelen per gender worden gewogen met 0,55 voor graad 3 (midden van 0,50–0,60) en 0,60 voor graad 4 (conservatieve drempel). Formule: gewogen_blootstelling = aandeel_g3×0,55 + aandeel_g4×0,60. Mannen 3,15 punten; vrouwen 5,96. Vergelijkingsindex, geen verdringingspercentage.',
                    sv: 'Härlett, inte publicerat av ILO. Jämförbara globala könsspecifika andelar viktas med 0,55 för gradient 3 (mittpunkt 0,50–0,60) och 0,60 för gradient 4 (konservativ tröskel). Formel: viktad_exponering = andel_g3×0,55 + andel_g4×0,60. Män 3,15 poäng; kvinnor 5,96. Jämförelseindex, inte undanträngningsgrad.',
                    pl: 'Wartość pochodna, niepublikowana przez ILO. Porównywalne globalne udziały według płci waży się 0,55 dla poziomu 3 (środek 0,50–0,60) i 0,60 dla poziomu 4 (konserwatywny próg). Wzór: ważona_ekspozycja = udział_g3×0,55 + udział_g4×0,60. Mężczyźni 3,15 pkt; kobiety 5,96. To indeks porównawczy, nie stopa wypierania.',
                ),
                labels: array_keys($weightedValues),
                series: array_values($weightedValues),
                xLabel: 'Sex',
                xType: 'category',
                yLabel: 'Weighted high-exposure index',
                yUnit: 'weighted exposure points',
                yFormat: 'decimal',
                sources: [$this->shared->sources()['ilo_index']],
                sortOrder: 6,
            ),
            $this->shared->kpiVisualization(
                key: 'reskilling_gap',
                title: $this->shared->t(
                    en: 'Observed reskilling gap: Slovakia workplace survey',
                    it: 'Gap di riqualificazione osservato: indagine slovacca',
                    fr: 'Écart de reconversion observé: enquête slovaque',
                    de: 'Beobachtete Weiterbildungslücke: slowakische Erhebung',
                    es: 'Brecha de recualificación observada: encuesta eslovaca',
                    nl: 'Waargenomen omscholingskloof: Slowaakse enquête',
                    sv: 'Observerat kompetensgap: slovakisk undersökning',
                    pl: 'Zaobserwowana luka przekwalifikowania: badanie słowackie',
                ),
                description: $this->shared->t(
                    en: 'Country-specific evidence on perceived AI-skill need and recent training participation.',
                    it: 'Evidenza nazionale sul bisogno percepito di competenze IA e sulla partecipazione recente alla formazione.',
                    fr: 'Données nationales sur le besoin perçu de compétences IA et la participation récente à la formation.',
                    de: 'Länderspezifische Evidenz zu wahrgenommenem KI-Kompetenzbedarf und jüngster Schulungsteilnahme.',
                    es: 'Evidencia nacional sobre necesidad percibida de competencias IA y participación reciente en formación.',
                    nl: 'Landspecifiek bewijs over ervaren AI-vaardigheidsbehoefte en recente training.',
                    sv: 'Landsspecifika uppgifter om upplevt behov av AI-kompetens och nyligt utbildningsdeltagande.',
                    pl: 'Dane krajowe o odczuwanej potrzebie kompetencji AI i niedawnym udziale w szkoleniach.',
                ),
                reasoning: $this->shared->t(
                    en: 'Observed country-specific survey values: 39% of employees reported a need to develop AI skills and 12% participated in relevant training in the previous year. Derived gap formula: 39% − 12% = 27 percentage points. This is not an EU-wide estimate and does not measure job displacement.',
                    it: 'Valori osservati di un’indagine nazionale: il 39% dei dipendenti segnala bisogno di competenze IA e il 12% ha seguito formazione pertinente nell’anno precedente. Formula del gap derivato: 39% − 12% = 27 punti percentuali. Non è una stima UE né misura lo spiazzamento.',
                    fr: 'Valeurs observées d’une enquête nationale: 39% des salariés déclarent devoir développer leurs compétences IA et 12% ont suivi une formation pertinente l’année précédente. Formule de l’écart: 39% − 12% = 27 points. Ce n’est ni une estimation de l’UE ni une mesure de déplacement.',
                    de: 'Beobachtete nationale Umfragewerte: 39% der Beschäftigten sehen KI-Qualifikationsbedarf, 12% nahmen im Vorjahr an entsprechender Schulung teil. Abgeleitete Lücke: 39% − 12% = 27 Prozentpunkte. Keine EU-Schätzung und kein Verdrängungsmaß.',
                    es: 'Valores observados de una encuesta nacional: 39% de empleados necesita desarrollar competencias IA y 12% participó en formación pertinente el año anterior. Brecha derivada: 39% − 12% = 27 puntos. No es una estimación UE ni mide desplazamiento.',
                    nl: 'Waargenomen nationale enquêtewaarden: 39% van werknemers meldt behoefte aan AI-vaardigheden en 12% volgde het vorige jaar relevante training. Afgeleide kloof: 39% − 12% = 27 procentpunt. Geen EU-schatting en geen maat voor verdringing.',
                    sv: 'Observerade nationella enkätvärden: 39% av anställda uppgav behov av AI-kompetens och 12% deltog i relevant utbildning föregående år. Härlett gap: 39% − 12% = 27 procentenheter. Inte en EU-skattning och inte ett mått på undanträngning.',
                    pl: 'Zaobserwowane wartości badania krajowego: 39% pracowników zgłasza potrzebę kompetencji AI, a 12% uczestniczyło w odpowiednim szkoleniu w poprzednim roku. Pochodna luka: 39% − 12% = 27 pkt proc. To nie jest szacunek UE ani miara wypierania pracy.',
                ),
                items: [
                    ['label' => 'Employees reporting need for AI skills', 'value' => $reskilling['employees_reporting_need'].'%'],
                    ['label' => 'Employees trained in previous year', 'value' => $reskilling['employees_trained'].'%'],
                    ['label' => 'Derived training gap', 'value' => ($reskilling['employees_reporting_need'] - $reskilling['employees_trained']).' pp'],
                ],
                sources: [$this->shared->sources()['slovakia_skills']],
                sortOrder: 7,
            ),
        ];
    }
};
