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
        $sources = $this->shared->sources();

        return $this->shared->chartVisualization(
            key: 'projection_curve',
            type: VisualizationType::Line,
            title: $this->shared->t('AMR operational-horizon scenarios, 2025–2036', 'Scenari dell’orizzonte operativo AMR, 2025–2036', 'Scénarios d’horizon opérationnel de la RAM, 2025–2036', 'Szenarien des operativen AMR-Horizonts, 2025–2036', 'Escenarios del horizonte operativo de la RAM, 2025–2036', 'Scenario’s voor de operationele AMR-horizon, 2025–2036', 'Scenarier för AMR:s operativa tidshorisont, 2025–2036', 'Scenariusze operacyjnego horyzontu AMR, 2025–2036'),
            description: $this->shared->t('Editorial composite-index paths to three distinct assessment windows; lower values indicate a smaller burden signal.', 'Percorsi editoriali di un indice composito verso tre finestre di valutazione distinte; valori inferiori indicano un segnale di burden minore.', 'Trajectoires éditoriales d’un indice composite vers trois fenêtres d’évaluation distinctes; une valeur plus basse indique un signal de charge moindre.', 'Redaktionelle Pfade eines zusammengesetzten Index zu drei unterschiedlichen Bewertungsfenstern; niedrigere Werte bedeuten ein geringeres Belastungssignal.', 'Trayectorias editoriales de un índice compuesto hacia tres ventanas de evaluación distintas; valores menores indican una señal de carga inferior.', 'Redactionele paden van een samengestelde index naar drie verschillende evaluatievensters; lagere waarden betekenen een kleiner lastsignaal.', 'Redaktionella banor för ett sammansatt index mot tre olika utvärderingsfönster; lägre värden betyder mindre bördesignal.', 'Redakcyjne ścieżki indeksu złożonego do trzech różnych okien oceny; niższa wartość oznacza mniejszy sygnał obciążenia.'),
            reasoning: $this->shared->t(
                'Editorial temporal scenario, not an official forecast. For scenario endpoint E, target year T and year y: index_y = 100 + (E - 100) × min(max(y - 2025, 0), T - 2025) / (T - 2025). Values plateau after T. Pessimistic uses T=2029, E=130; neutral T=2032, E=110; optimistic T=2036, E=90. The dates are operational assessment windows derived from resistance, consumption, access, diagnostics, pipeline and policy timing; the index is not a mortality estimate.',
                'Scenario temporale editoriale, non previsione ufficiale. Per endpoint E, anno target T e anno y: indice_y = 100 + (E - 100) × min(max(y - 2025, 0), T - 2025) / (T - 2025). Dopo T i valori restano costanti. Pessimistico: T=2029, E=130; neutrale: T=2032, E=110; ottimistico: T=2036, E=90. Le date sono finestre operative derivate da resistenza, consumi, accesso, diagnostica, pipeline e policy; l’indice non stima la mortalità.',
                'Scénario temporel éditorial, non prévision officielle. Pour une borne E, une année cible T et l’année y : indice_y = 100 + (E - 100) × min(max(y - 2025, 0), T - 2025) / (T - 2025). Les valeurs plafonnent après T. Pessimiste : T=2029, E=130; neutre : T=2032, E=110; optimiste : T=2036, E=90. Les dates sont des fenêtres opérationnelles dérivées de la résistance, de la consommation, de l’accès, du diagnostic, de la pipeline et des politiques; l’indice n’est pas une estimation de mortalité.',
                'Redaktionelles Zeitszenario, keine offizielle Prognose. Für Endwert E, Zieljahr T und Jahr y gilt: Index_y = 100 + (E - 100) × min(max(y - 2025, 0), T - 2025) / (T - 2025). Nach T bleibt der Wert konstant. Pessimistisch: T=2029, E=130; neutral: T=2032, E=110; optimistisch: T=2036, E=90. Die Daten sind operative Bewertungsfenster aus Resistenz-, Verbrauchs-, Zugangs-, Diagnostik-, Pipeline- und Politiksignalen; der Index ist keine Mortalitätsschätzung.',
                'Escenario temporal editorial, no previsión oficial. Para extremo E, año objetivo T y año y: índice_y = 100 + (E - 100) × min(max(y - 2025, 0), T - 2025) / (T - 2025). Los valores se estabilizan tras T. Pesimista: T=2029, E=130; neutral: T=2032, E=110; optimista: T=2036, E=90. Las fechas son ventanas operativas derivadas de resistencia, consumo, acceso, diagnóstico, cartera y política; el índice no estima mortalidad.',
                'Redactioneel tijdscenario, geen officiële voorspelling. Voor eindpunt E, doeljaar T en jaar y: index_y = 100 + (E - 100) × min(max(y - 2025, 0), T - 2025) / (T - 2025). Na T blijft de waarde constant. Pessimistisch: T=2029, E=130; neutraal: T=2032, E=110; optimistisch: T=2036, E=90. De data zijn operationele evaluatievensters uit resistentie, gebruik, toegang, diagnostiek, pijplijn en beleid; de index is geen sterfteschatting.',
                'Redaktionellt tidsscenario, inte en officiell prognos. För slutpunkt E, målår T och år y: index_y = 100 + (E - 100) × min(max(y - 2025, 0), T - 2025) / (T - 2025). Efter T ligger värdet stilla. Pessimistisk: T=2029, E=130; neutral: T=2032, E=110; optimistisk: T=2036, E=90. Datumen är operativa utvärderingsfönster härledda från resistens, användning, tillgång, diagnostik, pipeline och policy; indexet är ingen dödlighetsskattning.',
                'Redakcyjny scenariusz czasowy, nie oficjalna prognoza. Dla wartości końcowej E, roku docelowego T i roku y: indeks_y = 100 + (E - 100) × min(max(y - 2025, 0), T - 2025) / (T - 2025). Po T wartość pozostaje stała. Pesymistyczny: T=2029, E=130; neutralny: T=2032, E=110; optymistyczny: T=2036, E=90. Daty są operacyjnymi oknami oceny wynikającymi z oporności, zużycia, dostępu, diagnostyki, rozwoju leków i polityki; indeks nie szacuje śmiertelności.',
            ),
            labels: ['2025', '2026', '2027', '2028', '2029', '2030', '2031', '2032', '2033', '2034', '2035', '2036'],
            series: [
                ['name' => 'Optimistic — 2036', 'color' => '#22c55e', 'values' => [100.0, 99.1, 98.2, 97.3, 96.4, 95.5, 94.5, 93.6, 92.7, 91.8, 90.9, 90.0]],
                ['name' => 'Neutral — 2032', 'color' => '#38bdf8', 'values' => [100.0, 101.4, 102.9, 104.3, 105.7, 107.1, 108.6, 110.0, 110.0, 110.0, 110.0, 110.0]],
                ['name' => 'Pessimistic — 2029', 'color' => '#ff2a23', 'values' => [100.0, 107.5, 115.0, 122.5, 130.0, 130.0, 130.0, 130.0, 130.0, 130.0, 130.0, 130.0]],
            ],
            xLabel: 'Year',
            xType: 'temporal',
            yLabel: 'Composite AMR operational-risk index',
            yUnit: 'index (2025=100)',
            yFormat: 'decimal',
            sources: [$sources['un_amr_declaration_2024'], $sources['who_report_2025'], $sources['ecdc_ears_2024'], $sources['ecdc_esac_2024'], $sources['who_pipeline_2025'], $sources['who_diagnostics_2025'], $sources['who_gap_2026'], $sources['gram_burden_2021'], $sources['oecd_one_health']],
            sortOrder: 1,
        );
    }

    /** @return array<int, array<string, mixed>> */
    public function visualizations(): array
    {
        return [
            [
                'key' => 'key_indicators',
                'type' => VisualizationType::Kpi,
                'title' => $this->shared->t('Key AMR indicators', 'Indicatori chiave AMR', 'Indicateurs clés de la RAM', 'Zentrale AMR-Indikatoren', 'Indicadores clave de RAM', 'Belangrijkste AMR-indicatoren', 'Viktiga AMR-indikatorer', 'Kluczowe wskaźniki AMR'),
                'description' => $this->shared->t('Observed global and European signals kept separate because their definitions and units differ.', 'Segnali globali ed europei osservati, mantenuti separati perché definizioni e unità differiscono.', 'Signaux mondiaux et européens observés, séparés car définitions et unités diffèrent.', 'Beobachtete globale und europäische Signale, wegen unterschiedlicher Definitionen und Einheiten getrennt.', 'Señales mundiales y europeas observadas, separadas por diferencias de definición y unidad.', 'Waargenomen mondiale en Europese signalen, gescheiden wegens verschillende definities en eenheden.', 'Observerade globala och europeiska signaler, separerade på grund av olika definitioner och enheter.', 'Obserwowane sygnały globalne i europejskie, rozdzielone z powodu różnych definicji i jednostek.'),
                'sources' => [$this->shared->sources()['who_report_2025'], $this->shared->sources()['gram_burden_2021'], $this->shared->sources()['ecdc_burden'], $this->shared->sources()['who_pipeline_2025']],
                'reasoning' => $this->shared->t(
                    'Observed values: one in six laboratory-confirmed bacterial infections in 2023 was resistant; the 2021 study estimated 1.14 million attributable and 4.71 million associated deaths; ECDC reports more than 35,000 EU/EEA deaths annually; WHO counted 90 clinical antibacterial candidates in 2025. Attributable and associated deaths are different counterfactual definitions and are not added. Mixed units are shown as separate KPI cards, never on one axis.',
                    'Valori osservati: nel 2023 una infezione batterica confermata in laboratorio su sei era resistente; lo studio 2021 stima 1,14 milioni di decessi attribuibili e 4,71 milioni associati; ECDC riporta oltre 35.000 decessi annui UE/SEE; WHO conta 90 candidati antibatterici clinici nel 2025. Attribuibile e associato sono definizioni controfattuali diverse e non si sommano. Le unità miste restano KPI separati.',
                    'Valeurs observées : en 2023, une infection bactérienne confirmée sur six était résistante; l’étude 2021 estime 1,14 million de décès attribuables et 4,71 millions associés; l’ECDC rapporte plus de 35 000 décès annuels UE/EEE; l’OMS recense 90 candidats antibactériens cliniques en 2025. Attribuable et associé sont des définitions contrefactuelles distinctes et ne s’additionnent pas. Les unités mixtes restent des KPI séparés.',
                    'Beobachtete Werte: 2023 war eine von sechs laborbestätigten bakteriellen Infektionen resistent; die Studie 2021 schätzt 1,14 Mio. zurechenbare und 4,71 Mio. assoziierte Todesfälle; ECDC meldet über 35.000 Todesfälle pro Jahr in EU/EWR; WHO zählte 2025 90 klinische antibakterielle Kandidaten. Zurechenbar und assoziiert sind verschiedene kontrafaktische Definitionen und werden nicht addiert. Gemischte Einheiten bleiben getrennte KPI.',
                    'Valores observados: en 2023 una de cada seis infecciones bacterianas confirmadas era resistente; el estudio 2021 estimó 1,14 millones de muertes atribuibles y 4,71 millones asociadas; ECDC informa más de 35.000 muertes anuales UE/EEE; OMS contó 90 candidatos antibacterianos clínicos en 2025. Atribuible y asociada son definiciones contrafactuales distintas y no se suman. Las unidades mixtas se muestran como KPI separados.',
                    'Waargenomen waarden: in 2023 was één op zes laboratoriumbevestigde bacteriële infecties resistent; de studie over 2021 schatte 1,14 miljoen toerekenbare en 4,71 miljoen geassocieerde sterfgevallen; ECDC meldt jaarlijks meer dan 35.000 doden in EU/EER; WHO telde in 2025 90 klinische antibacteriële kandidaten. Toerekenbaar en geassocieerd zijn verschillende contrafeitelijke definities en worden niet opgeteld. Gemengde eenheden blijven aparte KPI.',
                    'Observerade värden: 2023 var en av sex laboratoriebekräftade bakteriella infektioner resistent; 2021-studien skattade 1,14 miljoner hänförliga och 4,71 miljoner associerade dödsfall; ECDC rapporterar över 35 000 dödsfall årligen i EU/EES; WHO räknade 90 kliniska antibakteriella kandidater 2025. Hänförliga och associerade dödsfall har olika kontrafaktiska definitioner och adderas inte. Blandade enheter visas som separata KPI.',
                    'Wartości obserwowane: w 2023 r. jedna na sześć laboratoryjnie potwierdzonych infekcji bakteryjnych była oporna; badanie 2021 oszacowało 1,14 mln zgonów przypisywanych i 4,71 mln związanych; ECDC podaje ponad 35 tys. zgonów rocznie w UE/EOG; WHO naliczyła 90 kandydatów klinicznych w 2025 r. Zgony przypisywane i związane mają różne definicje kontrfaktyczne i nie są sumowane. Różne jednostki pozostają oddzielnymi KPI.',
                ),
                'payload' => [
                    'items' => [
                        ['label' => 'Resistant bacterial infections, 2023', 'value' => '1 in 6'],
                        ['label' => 'AMR-attributable deaths, 2021', 'value' => '1.14M'],
                        ['label' => 'AMR-associated deaths, 2021', 'value' => '4.71M'],
                        ['label' => 'EU/EEA deaths annually', 'value' => '>35,000'],
                        ['label' => 'Clinical antibacterial candidates, 2025', 'value' => '90'],
                    ],
                ],
                'schema_version' => 1,
                'sort_order' => 1,
            ],
            $this->shared->chartVisualization(
                key: 'global_burden_2021',
                type: VisualizationType::Bar,
                title: $this->shared->t('Global bacterial AMR mortality burden, 2021', 'Burden globale di mortalità da AMR batterica, 2021', 'Charge mondiale de mortalité liée à la RAM bactérienne, 2021', 'Globale Sterblichkeitslast bakterieller AMR, 2021', 'Carga mundial de mortalidad por RAM bacteriana, 2021', 'Wereldwijde sterftelast door bacteriële AMR, 2021', 'Global dödlighetsbörda av bakteriell AMR, 2021', 'Globalne obciążenie zgonami z powodu bakteryjnej AMR, 2021'),
                description: $this->shared->t('Two published estimates for the same year, shown with their distinct causal definitions.', 'Due stime pubblicate per lo stesso anno, mostrate con definizioni causali distinte.', 'Deux estimations publiées pour la même année, avec des définitions causales distinctes.', 'Zwei veröffentlichte Schätzungen für dasselbe Jahr mit unterschiedlichen Kausaldefinitionen.', 'Dos estimaciones publicadas para el mismo año con definiciones causales distintas.', 'Twee gepubliceerde schattingen voor hetzelfde jaar met verschillende causale definities.', 'Två publicerade skattningar för samma år med olika kausala definitioner.', 'Dwa opublikowane oszacowania dla tego samego roku, z odmiennymi definicjami przyczynowymi.'),
                reasoning: $this->shared->t(
                    'Observed estimates from the 2021 global burden study: 1.14 million deaths attributable to bacterial AMR and 4.71 million deaths associated with bacterial AMR. “Attributable” compares with a counterfactual in which resistant infections become susceptible; “associated” compares with no infection. The bars are not additive and do not represent two independent death groups.',
                    'Stime osservate dello studio globale 2021: 1,14 milioni di decessi attribuibili ad AMR batterica e 4,71 milioni associati. “Attribuibile” confronta con il controfattuale in cui le infezioni resistenti diventano sensibili; “associato” con l’assenza di infezione. Le barre non sono additive né gruppi indipendenti.',
                    'Estimations observées de l’étude mondiale 2021 : 1,14 million de décès attribuables à la RAM bactérienne et 4,71 millions associés. « Attribuable » compare avec des infections résistantes devenues sensibles; « associé » avec l’absence d’infection. Les barres ne s’additionnent pas et ne sont pas des groupes indépendants.',
                    'Beobachtete Schätzungen der globalen Studie 2021: 1,14 Mio. auf bakterielle AMR zurechenbare und 4,71 Mio. damit assoziierte Todesfälle. „Zurechenbar“ vergleicht mit empfindlichen statt resistenten Infektionen; „assoziiert“ mit keiner Infektion. Die Balken sind weder additiv noch unabhängige Gruppen.',
                    'Estimaciones observadas del estudio mundial 2021: 1,14 millones de muertes atribuibles a RAM bacteriana y 4,71 millones asociadas. “Atribuible” compara con infecciones resistentes convertidas en sensibles; “asociada” con ausencia de infección. Las barras no se suman ni son grupos independientes.',
                    'Waargenomen schattingen uit de wereldstudie 2021: 1,14 miljoen aan bacteriële AMR toerekenbare en 4,71 miljoen ermee geassocieerde sterfgevallen. “Toerekenbaar” vergelijkt met gevoelige infecties; “geassocieerd” met geen infectie. De balken zijn niet optelbaar en geen onafhankelijke groepen.',
                    'Observerade skattningar från den globala studien 2021: 1,14 miljoner dödsfall hänförliga till bakteriell AMR och 4,71 miljoner associerade. ”Hänförlig” jämför med känslig infektion; ”associerad” med ingen infektion. Staplarna är inte additiva eller oberoende grupper.',
                    'Obserwowane szacunki badania globalnego 2021: 1,14 mln zgonów przypisywanych bakteryjnej AMR i 4,71 mln z nią związanych. „Przypisywane” porównuje z zakażeniem wrażliwym, „związane” z brakiem zakażenia. Słupki nie sumują się i nie są niezależnymi grupami.',
                ),
                labels: ['Attributable deaths', 'Associated deaths'],
                series: [1.14, 4.71],
                xLabel: 'Counterfactual definition',
                xType: 'category',
                yLabel: 'Estimated deaths in 2021',
                yUnit: 'million deaths',
                yFormat: 'decimal',
                sources: [$this->shared->sources()['gram_burden_2021']],
                sortOrder: 2,
            ),
            $this->shared->chartVisualization(
                key: 'eu_resistant_bsi_2024',
                type: VisualizationType::Bar,
                title: $this->shared->t('EU/EEA resistant bloodstream-infection indicators', 'Indicatori UE/SEE di infezioni resistenti del sangue', 'Indicateurs UE/EEE d’infections sanguines résistantes', 'EU/EWR-Indikatoren resistenter Blutstrominfektionen', 'Indicadores UE/EEE de infecciones sanguíneas resistentes', 'EU/EER-indicatoren voor resistente bloedbaaninfecties', 'EU/EES-indikatorer för resistenta blodinfektioner', 'Wskaźniki UE/EOG opornych zakażeń krwi'),
                description: $this->shared->t('Observed 2024 incidence and corresponding 2030 targets for two separately defined pathogen–drug indicators.', 'Incidenza osservata 2024 e target 2030 per due indicatori patogeno–farmaco definiti separatamente.', 'Incidence observée en 2024 et cibles 2030 pour deux indicateurs pathogène–médicament distincts.', 'Beobachtete Inzidenz 2024 und Ziele 2030 für zwei getrennte Erreger–Wirkstoff-Indikatoren.', 'Incidencia observada en 2024 y objetivos 2030 para dos indicadores patógeno–fármaco separados.', 'Waargenomen incidentie 2024 en doelen 2030 voor twee afzonderlijke pathogeen–middelindicatoren.', 'Observerad incidens 2024 och mål 2030 för två separata patogen–läkemedelsindikatorer.', 'Obserwowana zapadalność 2024 i cele 2030 dla dwóch odrębnych wskaźników patogen–lek.'),
                reasoning: $this->shared->t(
                    'Observed EARS-Net 2024 bloodstream-infection incidence per 100,000 population: third-generation-cephalosporin-resistant E. coli 11.03, target 9.38; carbapenem-resistant K. pneumoniae 3.51, target 2.07. All four bars use the same specimen class, population denominator and report year/target framework. Each pathogen–drug pair remains explicitly labelled; the chart does not compare resistance percentages.',
                    'Incidenza osservata EARS-Net 2024 per 100.000 abitanti: E. coli resistente alle cefalosporine di terza generazione 11,03, target 9,38; K. pneumoniae resistente ai carbapenemi 3,51, target 2,07. Le barre condividono specimen, denominatore e quadro anno/target. Ogni coppia patogeno–farmaco resta esplicita; non si confrontano percentuali di resistenza.',
                    'Incidence observée EARS-Net 2024 pour 100 000 habitants : E. coli résistant aux céphalosporines de troisième génération 11,03, cible 9,38; K. pneumoniae résistant aux carbapénèmes 3,51, cible 2,07. Même type de prélèvement, dénominateur et cadre année/cible. Chaque couple est nommé; aucune comparaison de pourcentages de résistance.',
                    'Beobachtete EARS-Net-Inzidenz 2024 je 100.000 Einwohner: gegen Cephalosporine der dritten Generation resistente E. coli 11,03, Ziel 9,38; carbapenemresistente K. pneumoniae 3,51, Ziel 2,07. Probenklasse, Nenner und Jahres-/Zielrahmen sind gleich. Jedes Erreger–Wirkstoff-Paar ist benannt; Resistenzprozente werden nicht verglichen.',
                    'Incidencia observada EARS-Net 2024 por 100.000 habitantes: E. coli resistente a cefalosporinas de tercera generación 11,03, objetivo 9,38; K. pneumoniae resistente a carbapenémicos 3,51, objetivo 2,07. Mismo espécimen, denominador y marco año/objetivo. Cada par queda identificado; no se comparan porcentajes de resistencia.',
                    'Waargenomen EARS-Net-incidentie 2024 per 100.000 inwoners: E. coli resistent tegen derdegeneratiecefalosporinen 11,03, doel 9,38; carbapenemresistente K. pneumoniae 3,51, doel 2,07. Zelfde monstertype, noemer en jaar/doelkader. Elk pathogeen–middelpaar blijft benoemd; resistentiepercentages worden niet vergeleken.',
                    'Observerad EARS-Net-incidens 2024 per 100 000 invånare: E. coli resistent mot tredje generationens cefalosporiner 11,03, mål 9,38; karbapenemresistent K. pneumoniae 3,51, mål 2,07. Samma provklass, nämnare och år/målram. Varje patogen–läkemedelspar namnges; resistensprocent jämförs inte.',
                    'Obserwowana zapadalność EARS-Net 2024 na 100 tys. ludności: E. coli oporna na cefalosporyny III generacji 11,03, cel 9,38; K. pneumoniae oporna na karbapenemy 3,51, cel 2,07. Ten sam rodzaj próbki, mianownik i ramy roku/celu. Każda para patogen–lek jest nazwana; nie porównuje się odsetków oporności.',
                ),
                labels: ['E. coli observed 2024', 'E. coli target 2030', 'K. pneumoniae observed 2024', 'K. pneumoniae target 2030'],
                series: [11.03, 9.38, 3.51, 2.07],
                xLabel: 'Pathogen–drug surveillance indicator',
                xType: 'category',
                yLabel: 'Resistant bloodstream infections',
                yUnit: 'infections/100,000 population',
                yFormat: 'decimal',
                sources: [$this->shared->sources()['ecdc_ears_2024']],
                sortOrder: 3,
            ),
            $this->shared->chartVisualization(
                key: 'carbapenem_klebsiella_trend',
                type: VisualizationType::Line,
                title: $this->shared->t('Carbapenem-resistant K. pneumoniae bloodstream infections', 'Infezioni del sangue da K. pneumoniae resistente ai carbapenemi', 'Infections sanguines à K. pneumoniae résistant aux carbapénèmes', 'Blutstrominfektionen mit carbapenemresistenter K. pneumoniae', 'Infecciones sanguíneas por K. pneumoniae resistente a carbapenémicos', 'Bloedbaaninfecties met carbapenemresistente K. pneumoniae', 'Blodinfektioner med karbapenemresistent K. pneumoniae', 'Zakażenia krwi K. pneumoniae oporną na karbapenemy'),
                description: $this->shared->t('EU/EEA incidence for one fixed pathogen–drug–specimen definition.', 'Incidenza UE/SEE per una definizione fissa patogeno–farmaco–specimen.', 'Incidence UE/EEE pour une définition fixe pathogène–médicament–prélèvement.', 'EU/EWR-Inzidenz für eine feste Erreger–Wirkstoff–Proben-Definition.', 'Incidencia UE/EEE para una definición fija patógeno–fármaco–espécimen.', 'EU/EER-incidentie voor één vaste pathogeen–middel–monsterdefinitie.', 'EU/EES-incidens för en fast patogen–läkemedel–provdefinition.', 'Zapadalność UE/EOG dla jednej stałej definicji patogen–lek–próbka.'),
                reasoning: $this->shared->t(
                    'The 2023 and 2024 incidences, 3.97 and 3.51 per 100,000, are observed EARS-Net values. ECDC reports 2024 as 61.0% above the 2019 baseline; therefore baseline_2019 = 3.51 / 1.61 = 2.18 after rounding to two decimals. The line uses the same carbapenem-resistant K. pneumoniae bloodstream-infection definition throughout and does not interpolate missing years.',
                    'Le incidenze 2023 e 2024, 3,97 e 3,51 per 100.000, sono valori EARS-Net osservati. ECDC indica il 2024 al 61,0% sopra la base 2019; quindi base_2019 = 3,51 / 1,61 = 2,18, arrotondato a due decimali. La definizione resta K. pneumoniae resistente ai carbapenemi in infezioni del sangue e non si interpolano anni mancanti.',
                    'Les incidences 2023 et 2024, 3,97 et 3,51 pour 100 000, sont observées par EARS-Net. L’ECDC indique 2024 à 61,0% au-dessus de 2019; donc base_2019 = 3,51 / 1,61 = 2,18, arrondi à deux décimales. Même définition K. pneumoniae résistant aux carbapénèmes dans le sang, sans interpolation des années manquantes.',
                    'Die Inzidenzen 2023 und 2024 von 3,97 und 3,51 je 100.000 sind beobachtete EARS-Net-Werte. ECDC nennt 2024 61,0% über 2019; daher Basis_2019 = 3,51 / 1,61 = 2,18, auf zwei Dezimalen gerundet. Definition bleibt carbapenemresistente K. pneumoniae im Blutstrom; fehlende Jahre werden nicht interpoliert.',
                    'Las incidencias 2023 y 2024, 3,97 y 3,51 por 100.000, son valores observados EARS-Net. ECDC sitúa 2024 un 61,0% sobre 2019; por tanto base_2019 = 3,51 / 1,61 = 2,18, redondeado a dos decimales. Se mantiene la misma definición y no se interpolan años faltantes.',
                    'De incidenties 2023 en 2024, 3,97 en 3,51 per 100.000, zijn waargenomen EARS-Net-waarden. ECDC meldt 2024 61,0% boven 2019; dus basis_2019 = 3,51 / 1,61 = 2,18, afgerond op twee decimalen. De definitie blijft gelijk en ontbrekende jaren worden niet geïnterpoleerd.',
                    'Incidenserna 2023 och 2024, 3,97 och 3,51 per 100 000, är observerade EARS-Net-värden. ECDC anger 2024 som 61,0% över 2019; därför bas_2019 = 3,51 / 1,61 = 2,18, avrundat till två decimaler. Definitionen är densamma och saknade år interpoleras inte.',
                    'Zapadalność 2023 i 2024, 3,97 i 3,51 na 100 tys., to wartości obserwowane EARS-Net. ECDC podaje, że 2024 był o 61,0% powyżej 2019; zatem baza_2019 = 3,51 / 1,61 = 2,18 po zaokrągleniu. Definicja pozostaje stała, a brakujące lata nie są interpolowane.',
                ),
                labels: ['2019', '2023', '2024'],
                series: [2.18, 3.97, 3.51],
                xLabel: 'Reporting year',
                xType: 'temporal',
                yLabel: 'Carbapenem-resistant K. pneumoniae bloodstream-infection incidence',
                yUnit: 'infections/100,000 population',
                yFormat: 'decimal',
                sources: [$this->shared->sources()['ecdc_ears_2023'], $this->shared->sources()['ecdc_ears_2024']],
                sortOrder: 4,
            ),
            $this->shared->chartVisualization(
                key: 'community_antibiotic_consumption',
                type: VisualizationType::Area,
                title: $this->shared->t('EU/EEA community antibiotic consumption', 'Consumo comunitario di antibiotici UE/SEE', 'Consommation communautaire d’antibiotiques UE/EEE', 'Antibiotikaverbrauch in der EU/EWR-Gemeinschaft', 'Consumo comunitario de antibióticos UE/EEE', 'Antibioticagebruik in de EU/EER-gemeenschap', 'Antibiotikaanvändning i samhället EU/EES', 'Zużycie antybiotyków w społeczności UE/EOG'),
                description: $this->shared->t('Observed systemic antibacterial consumption in the community using the standard DDD denominator.', 'Consumo osservato di antibatterici sistemici nella comunità con denominatore DDD standard.', 'Consommation observée d’antibactériens systémiques en ville avec dénominateur DDD standard.', 'Beobachteter systemischer Antibiotikaverbrauch im ambulanten Bereich mit Standard-DDD-Nenner.', 'Consumo observado de antibacterianos sistémicos en la comunidad con denominador DDD estándar.', 'Waargenomen systemisch antibioticagebruik buiten het ziekenhuis met standaard-DDD-noemer.', 'Observerad användning av systemiska antibakteriella medel i samhället med standardiserad DDD-nämnare.', 'Obserwowane zużycie antybiotyków ogólnoustrojowych w społeczności ze standardowym mianownikiem DDD.'),
                reasoning: $this->shared->t(
                    'Observed ESAC-Net values: 18.3 defined daily doses per 1,000 inhabitants per day in 2023 and 18.8 in 2024 for systemic antibacterials consumed in the community. The area connects two annual observations only; it does not estimate intermediate months or hospital consumption.',
                    'Valori osservati ESAC-Net: 18,3 dosi definite giornaliere per 1.000 abitanti al giorno nel 2023 e 18,8 nel 2024 per antibatterici sistemici in comunità. L’area collega solo due osservazioni annuali; non stima mesi intermedi né consumo ospedaliero.',
                    'Valeurs observées ESAC-Net : 18,3 doses définies journalières pour 1 000 habitants par jour en 2023 et 18,8 en 2024 pour les antibactériens systémiques en ville. La zone relie deux observations annuelles; elle n’estime ni mois intermédiaires ni consommation hospitalière.',
                    'Beobachtete ESAC-Net-Werte: 18,3 definierte Tagesdosen je 1.000 Einwohner und Tag 2023 und 18,8 im Jahr 2024 für systemische Antibiotika im ambulanten Bereich. Die Fläche verbindet nur zwei Jahresbeobachtungen; Zwischenmonate und Krankenhausverbrauch werden nicht geschätzt.',
                    'Valores observados ESAC-Net: 18,3 dosis diarias definidas por 1.000 habitantes y día en 2023 y 18,8 en 2024 para antibacterianos sistémicos comunitarios. El área une dos observaciones anuales; no estima meses intermedios ni consumo hospitalario.',
                    'Waargenomen ESAC-Net-waarden: 18,3 gedefinieerde dagdoseringen per 1.000 inwoners per dag in 2023 en 18,8 in 2024 voor systemische antibacteriële middelen in de gemeenschap. Het vlak verbindt alleen twee jaarwaarnemingen; geen tussenmaanden of ziekenhuisgebruik.',
                    'Observerade ESAC-Net-värden: 18,3 definierade dygnsdoser per 1 000 invånare och dag 2023 och 18,8 år 2024 för systemiska antibakteriella medel i samhället. Ytan binder endast två årsobservationer; mellanliggande månader och sjukhusanvändning skattas inte.',
                    'Obserwowane wartości ESAC-Net: 18,3 zdefiniowane dawki dobowe na 1000 mieszkańców dziennie w 2023 r. i 18,8 w 2024 r. dla antybiotyków ogólnoustrojowych w społeczności. Obszar łączy tylko dwa pomiary roczne; nie szacuje miesięcy ani zużycia szpitalnego.',
                ),
                labels: ['2023', '2024'],
                series: [18.3, 18.8],
                xLabel: 'Year',
                xType: 'temporal',
                yLabel: 'Community systemic antibacterial consumption',
                yUnit: 'DDD/1,000 inhabitants/day',
                yFormat: 'decimal',
                sources: [$this->shared->sources()['ecdc_esac_2023'], $this->shared->sources()['ecdc_esac_2024']],
                sortOrder: 5,
            ),
            $this->shared->chartVisualization(
                key: 'access_antibiotic_share',
                type: VisualizationType::Bar,
                title: $this->shared->t('Access antibiotic share and 2030 target', 'Quota antibiotici Access e target 2030', 'Part des antibiotiques Access et cible 2030', 'Anteil der Access-Antibiotika und Ziel 2030', 'Cuota de antibióticos Access y objetivo 2030', 'Aandeel Access-antibiotica en doel 2030', 'Andel Access-antibiotika och mål 2030', 'Udział antybiotyków Access i cel 2030'),
                description: $this->shared->t('EU/EEA consumption share for WHO Access antibiotics compared with the agreed target.', 'Quota di consumo UE/SEE degli antibiotici WHO Access rispetto al target concordato.', 'Part de consommation UE/EEE des antibiotiques Access de l’OMS comparée à la cible.', 'EU/EWR-Verbrauchsanteil der WHO-Access-Antibiotika gegenüber dem Ziel.', 'Cuota de consumo UE/EEE de antibióticos Access de OMS frente al objetivo.', 'EU/EER-gebruikaandeel van WHO Access-antibiotica tegenover het doel.', 'EU/EES-andel för WHO Access-antibiotika jämfört med målet.', 'Udział zużycia antybiotyków WHO Access w UE/EOG względem celu.'),
                reasoning: $this->shared->t(
                    'Observed ESAC-Net 2024 Access share is 60.3%. The 2030 target is at least 65%. The displayed target uses the threshold value 65.0; target gap = 65.0 - 60.3 = 4.7 percentage points. Both bars share the same consumption-share denominator and classification.',
                    'La quota Access osservata ESAC-Net 2024 è 60,3%. Il target 2030 è almeno 65%. La barra target usa la soglia 65,0; gap = 65,0 - 60,3 = 4,7 punti percentuali. Le barre condividono denominatore e classificazione.',
                    'La part Access observée ESAC-Net 2024 est 60,3%. La cible 2030 est au moins 65%. La barre cible utilise 65,0; écart = 65,0 - 60,3 = 4,7 points de pourcentage. Même dénominateur et même classification.',
                    'Der beobachtete ESAC-Net-Access-Anteil 2024 beträgt 60,3%. Das Ziel 2030 liegt bei mindestens 65%. Der Zielbalken nutzt 65,0; Lücke = 65,0 - 60,3 = 4,7 Prozentpunkte. Beide Balken haben denselben Verbrauchsnenner und dieselbe Klassifikation.',
                    'La cuota Access observada ESAC-Net 2024 es 60,3%. El objetivo 2030 es al menos 65%. La barra usa 65,0; brecha = 65,0 - 60,3 = 4,7 puntos porcentuales. Ambas barras comparten denominador y clasificación.',
                    'Het waargenomen ESAC-Net-Access-aandeel 2024 is 60,3%. Het doel voor 2030 is minstens 65%. De doelbalk gebruikt 65,0; kloof = 65,0 - 60,3 = 4,7 procentpunt. Beide balken hebben dezelfde noemer en classificatie.',
                    'Observerad ESAC-Net-Access-andel 2024 är 60,3%. Målet 2030 är minst 65%. Målstapeln använder 65,0; gap = 65,0 - 60,3 = 4,7 procentenheter. Båda staplarna har samma nämnare och klassificering.',
                    'Obserwowany udział Access ESAC-Net 2024 wynosi 60,3%. Cel 2030 to co najmniej 65%. Słupek celu używa 65,0; luka = 65,0 - 60,3 = 4,7 punktu procentowego. Oba słupki mają ten sam mianownik i klasyfikację.',
                ),
                labels: ['EU/EEA 2024', '2030 target threshold'],
                series: [60.3, 65.0],
                xLabel: 'Observed and target share',
                xType: 'category',
                yLabel: 'Access antibiotics as share of consumption',
                yUnit: '%',
                yFormat: 'percent',
                sources: [$this->shared->sources()['ecdc_esac_2024'], $this->shared->sources()['who_aware']],
                sortOrder: 6,
            ),
            $this->shared->chartVisualization(
                key: 'clinical_pipeline_2025',
                type: VisualizationType::Bar,
                title: $this->shared->t('Clinical antibacterial pipeline, 2025', 'Pipeline clinica antibatterica, 2025', 'Pipeline clinique antibactérienne, 2025', 'Klinische antibakterielle Pipeline, 2025', 'Cartera clínica antibacteriana, 2025', 'Klinische antibacteriële pijplijn, 2025', 'Klinisk antibakteriell pipeline, 2025', 'Kliniczny rozwój leków przeciwbakteryjnych, 2025'),
                description: $this->shared->t('Traditional and non-traditional clinical candidates counted by WHO.', 'Candidati clinici tradizionali e non tradizionali conteggiati da WHO.', 'Candidats cliniques traditionnels et non traditionnels recensés par l’OMS.', 'Von der WHO gezählte traditionelle und nichttraditionelle klinische Kandidaten.', 'Candidatos clínicos tradicionales y no tradicionales contabilizados por OMS.', 'Door WHO getelde traditionele en niet-traditionele klinische kandidaten.', 'Traditionella och icke-traditionella kliniska kandidater räknade av WHO.', 'Tradycyjni i nietradycyjni kandydaci kliniczni policzeni przez WHO.'),
                reasoning: $this->shared->t(
                    'Observed WHO 2025 pipeline counts: 50 traditional antibacterial candidates plus 40 non-traditional candidates = 90 total. WHO reported 97 candidates in 2023, so the total decline is 97 - 90 = 7 candidates. The bars compare candidate categories at the same pipeline snapshot, not approvals or expected successful products.',
                    'Conteggi osservati WHO 2025: 50 candidati antibatterici tradizionali + 40 non tradizionali = 90 totali. WHO riportava 97 candidati nel 2023, quindi il calo è 97 - 90 = 7. Le barre confrontano categorie nello stesso snapshot, non approvazioni o prodotti attesi.',
                    'Comptages observés OMS 2025 : 50 candidats traditionnels + 40 non traditionnels = 90. L’OMS en rapportait 97 en 2023, soit une baisse de 97 - 90 = 7. Les barres comparent des catégories au même instant, pas des autorisations ni des succès attendus.',
                    'Beobachtete WHO-Zahlen 2025: 50 traditionelle plus 40 nichttraditionelle Kandidaten = 90. 2023 waren es 97, somit Rückgang 97 - 90 = 7. Die Balken vergleichen Kategorien desselben Pipeline-Stands, nicht Zulassungen oder erwartete Erfolge.',
                    'Recuentos observados OMS 2025: 50 candidatos tradicionales + 40 no tradicionales = 90. En 2023 eran 97, por lo que la caída es 97 - 90 = 7. Las barras comparan categorías en la misma foto de cartera, no aprobaciones ni productos exitosos esperados.',
                    'Waargenomen WHO-tellingen 2025: 50 traditionele plus 40 niet-traditionele kandidaten = 90. In 2023 waren het er 97, dus daling 97 - 90 = 7. De balken vergelijken categorieën op hetzelfde pijplijnmoment, niet goedkeuringen of verwachte successen.',
                    'Observerade WHO-tal 2025: 50 traditionella plus 40 icke-traditionella kandidater = 90. År 2023 var totalen 97, alltså minskning 97 - 90 = 7. Staplarna jämför kategorier i samma ögonblick, inte godkännanden eller väntade framgångar.',
                    'Obserwowane dane WHO 2025: 50 kandydatów tradycyjnych + 40 nietradycyjnych = 90. W 2023 r. było 97, spadek wynosi 97 - 90 = 7. Słupki porównują kategorie w tym samym stanie rozwoju, nie zatwierdzenia ani oczekiwane sukcesy.',
                ),
                labels: ['Traditional candidates', 'Non-traditional candidates'],
                series: [50, 40],
                xLabel: 'Candidate category',
                xType: 'category',
                yLabel: 'Clinical antibacterial candidates',
                yUnit: 'candidates',
                yFormat: 'integer',
                sources: [$this->shared->sources()['who_pipeline_2025'], $this->shared->sources()['who_pipeline_report_2025']],
                sortOrder: 7,
            ),
        ];
    }
};
