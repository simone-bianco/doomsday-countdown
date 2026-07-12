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
        return $this->shared->chartVisualization(
            key: 'projection_curve',
            type: VisualizationType::Bar,
            title: $this->shared->t(
                en: 'Scenario evidence checkpoints, 2029–2050',
                it: 'Checkpoint di evidenza degli scenari, 2029–2050',
                fr: 'Points probants des scénarios, 2029–2050',
                de: 'Evidenzpunkte der Szenarien, 2029–2050',
                es: 'Puntos de evidencia de los escenarios, 2029–2050',
                nl: 'Bewijscheckpoints van de scenario’s, 2029–2050',
                sv: 'Scenariernas evidenskontroller, 2029–2050',
                pl: 'Punkty dowodowe scenariuszy, 2029–2050',
            ),
            description: $this->shared->t(
                en: 'Scenario-specific terrestrial coverage checkpoints aligned to the 2029 reporting deadline, 2030 policy review and 2050 ecological vision.',
                it: 'Checkpoint di copertura terrestre specifici per scenario, allineati alla scadenza di reporting 2029, alla verifica politica 2030 e alla visione ecologica 2050.',
                fr: 'Points de couverture terrestre propres à chaque scénario, alignés sur la date de rapport 2029, la revue politique 2030 et la vision écologique 2050.',
                de: 'Szenariospezifische terrestrische Abdeckungspunkte entlang der Berichtsfrist 2029, der politischen Prüfung 2030 und der ökologischen Vision 2050.',
                es: 'Puntos de cobertura terrestre específicos por escenario, alineados con el informe de 2029, la revisión política de 2030 y la visión ecológica de 2050.',
                nl: 'Scenario-specifieke checkpoints voor landdekking, gekoppeld aan de rapportage van 2029, de beleidsreview van 2030 en de ecologische visie van 2050.',
                sv: 'Scenariospecifika kontrollpunkter för landtäckning, kopplade till rapporteringen 2029, policygranskningen 2030 och den ekologiska visionen 2050.',
                pl: 'Punkty pokrycia lądowego właściwe dla scenariuszy, powiązane z raportem 2029, przeglądem polityki 2030 i wizją ekologiczną 2050.',
            ),
            reasoning: $this->shared->t(
                en: 'Editorial checkpoint calculation: coverage_at_checkpoint = 17.6 + closure_fraction × (30 − 17.6), rounded to two decimals. The pessimistic 2029 reporting checkpoint uses 0.2 and gives 20.08%; the neutral 2030 policy deadline uses 0.5 and gives 23.8%; the optimistic 2050 ecological horizon uses 1.0 and gives 30%. Bars are separate scenario endpoints, not one connected forecast. Area coverage is not an ecological tipping point and must be read with habitat condition, monitored-population and extinction-risk evidence.',
                it: 'Calcolo editoriale dei checkpoint: copertura_al_checkpoint = 17,6 + frazione_di_chiusura × (30 − 17,6), arrotondata a due decimali. Il checkpoint pessimista di reporting 2029 usa 0,2 e dà 20,08%; la scadenza politica neutrale 2030 usa 0,5 e dà 23,8%; l’orizzonte ecologico ottimista 2050 usa 1,0 e dà 30%. Le barre sono endpoint di scenari separati, non una previsione collegata. La copertura non è una soglia ecologica e va letta con condizioni degli habitat, popolazioni monitorate e rischio di estinzione.',
                fr: 'Calcul éditorial: couverture_au_point = 17,6 + fraction_de_fermeture × (30 − 17,6), arrondie à deux décimales. Le point pessimiste de rapport 2029 utilise 0,2 et donne 20,08%; l’échéance politique neutre 2030 utilise 0,5 et donne 23,8%; l’horizon écologique optimiste 2050 utilise 1,0 et donne 30%. Les barres sont des résultats de scénarios distincts, pas une prévision continue. La couverture n’est pas un seuil écologique et doit être lue avec l’état des habitats, les populations suivies et le risque d’extinction.',
                de: 'Redaktionelle Berechnung: Abdeckung_am_Punkt = 17,6 + Schließungsanteil × (30 − 17,6), auf zwei Dezimalstellen gerundet. Der pessimistische Berichtspunkt 2029 nutzt 0,2 und ergibt 20,08%; die neutrale politische Frist 2030 nutzt 0,5 und ergibt 23,8%; der optimistische ökologische Horizont 2050 nutzt 1,0 und ergibt 30%. Die Balken sind getrennte Szenarioendpunkte, keine verbundene Prognose. Flächenabdeckung ist kein ökologischer Kipppunkt und muss mit Habitatqualität, beobachteten Populationen und Aussterberisiko gelesen werden.',
                es: 'Cálculo editorial: cobertura_en_el_punto = 17,6 + fracción_de_cierre × (30 − 17,6), redondeada a dos decimales. El punto pesimista de informes 2029 usa 0,2 y da 20,08%; la fecha política neutral 2030 usa 0,5 y da 23,8%; el horizonte ecológico optimista 2050 usa 1,0 y da 30%. Las barras son puntos finales de escenarios separados, no una previsión conectada. La cobertura no es un umbral ecológico y debe leerse junto con condición de hábitats, poblaciones monitoreadas y riesgo de extinción.',
                nl: 'Redactionele berekening: dekking_op_checkpoint = 17,6 + sluitingsfractie × (30 − 17,6), afgerond op twee decimalen. Het pessimistische rapportagepunt 2029 gebruikt 0,2 en geeft 20,08%; de neutrale beleidsdeadline 2030 gebruikt 0,5 en geeft 23,8%; de optimistische ecologische horizon 2050 gebruikt 1,0 en geeft 30%. De balken zijn afzonderlijke scenario-eindpunten, geen verbonden voorspelling. Oppervlaktedekking is geen ecologische drempel en moet samen met habitatconditie, gemonitorde populaties en extinctierisico worden gelezen.',
                sv: 'Redaktionell beräkning: täckning_vid_kontrollpunkt = 17,6 + slutningsandel × (30 − 17,6), avrundat till två decimaler. Den pessimistiska rapporteringspunkten 2029 använder 0,2 och ger 20,08%; den neutrala policyfristen 2030 använder 0,5 och ger 23,8%; den optimistiska ekologiska horisonten 2050 använder 1,0 och ger 30%. Staplarna är separata scenarioändpunkter, inte en sammanhängande prognos. Arealtäckning är ingen ekologisk tröskel och måste läsas tillsammans med livsmiljökvalitet, övervakade populationer och utdöenderisk.',
                pl: 'Obliczenie redakcyjne: pokrycie_w_punkcie = 17,6 + udział_zamknięcia × (30 − 17,6), zaokrąglone do dwóch miejsc. Pesymistyczny punkt raportowy 2029 używa 0,2 i daje 20,08%; neutralny termin polityczny 2030 używa 0,5 i daje 23,8%; optymistyczny horyzont ekologiczny 2050 używa 1,0 i daje 30%. Słupki są odrębnymi końcami scenariuszy, a nie połączoną prognozą. Pokrycie obszarowe nie jest progiem ekologicznym i musi być odczytywane wraz ze stanem siedlisk, monitorowanymi populacjami i ryzykiem wymarcia.',
            ),
            labels: ['2029-06-30', '2030-12-31', '2050-12-31'],
            series: [20.08, 23.8, 30.0],
            xLabel: 'Scenario evidence checkpoint',
            xType: 'category',
            yLabel: 'Protected and conserved terrestrial and inland-water area',
            yUnit: '%',
            yFormat: 'percent',
            sources: [
                $this->shared->sources()['cbd_national_reports'],
                $this->shared->sources()['cbd_global_review_2026'],
                $this->shared->sources()['cbd_gbf'],
                $this->shared->sources()['protected_planet_report'],
                $this->shared->sources()['living_planet_index'],
                $this->shared->sources()['ramsar_gwo'],
                $this->shared->sources()['fao_fra_2025'],
                $this->shared->sources()['iucn_2025_birds'],
            ],
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
                'title' => $this->shared->t(
                    en: 'Global assessment headline indicators', it: 'Indicatori principali della valutazione globale', fr: 'Indicateurs clés de l’évaluation mondiale', de: 'Kernindikatoren der globalen Bewertung', es: 'Indicadores principales de la evaluación mundial', nl: 'Kernindicatoren van de mondiale beoordeling', sv: 'Huvudindikatorer i den globala bedömningen', pl: 'Główne wskaźniki oceny globalnej',
                ),
                'description' => $this->shared->t(
                    en: 'Separate headline estimates from the IPBES global assessment; incompatible units are not plotted on one axis.',
                    it: 'Stime principali separate dalla valutazione globale IPBES; unità incompatibili non sono poste sullo stesso asse.',
                    fr: 'Estimations principales séparées de l’évaluation mondiale de l’IPBES; les unités incompatibles ne partagent pas un axe.',
                    de: 'Getrennte Kernaussagen der globalen IPBES-Bewertung; inkompatible Einheiten werden nicht auf einer Achse dargestellt.',
                    es: 'Estimaciones principales separadas de la evaluación mundial de IPBES; las unidades incompatibles no comparten eje.',
                    nl: 'Afzonderlijke kernschattingen uit de mondiale IPBES-beoordeling; onverenigbare eenheden staan niet op één as.',
                    sv: 'Separata huvudskattningar från IPBES globala bedömning; oförenliga enheter visas inte på samma axel.',
                    pl: 'Oddzielne główne szacunki z globalnej oceny IPBES; niezgodne jednostki nie są umieszczane na jednej osi.',
                ),
                'sources' => [$this->shared->sources()['ipbes_spm']],
                'reasoning' => $this->shared->t(
                    en: 'Observed/estimated: IPBES reports about one million animal and plant species threatened within an estimated total of roughly eight million, 75% of land significantly altered, 66% of the ocean subject to increasing cumulative impacts and more than 85% of wetland area lost in its historical baseline. The one-million figure is a modelled global estimate, not an IUCN Red List count; each KPI retains its own denominator and unit.',
                    it: 'Osservato/stimato: IPBES riporta circa un milione di specie animali e vegetali minacciate su un totale stimato di circa otto milioni, il 75% delle terre significativamente alterato, il 66% degli oceani soggetto a impatti cumulativi crescenti e oltre l’85% delle zone umide perso rispetto alla baseline storica. Il milione è una stima modellistica globale, non un conteggio IUCN; ogni KPI conserva denominatore e unità propri.',
                    fr: 'Observé/estimé: l’IPBES indique environ un million d’espèces animales et végétales menacées sur un total estimé d’environ huit millions, 75% des terres fortement modifiées, 66% de l’océan soumis à des impacts cumulatifs croissants et plus de 85% des zones humides perdues par rapport à sa référence historique. Le million est une estimation mondiale modélisée, pas un compte UICN; chaque KPI conserve son dénominateur et son unité.',
                    de: 'Beobachtet/geschätzt: IPBES nennt rund eine Million bedrohte Tier- und Pflanzenarten bei insgesamt etwa acht Millionen geschätzten Arten, 75% stark veränderte Landflächen, 66% der Ozeane mit zunehmenden kumulativen Belastungen und mehr als 85% Verlust von Feuchtgebietsfläche gegenüber der historischen Basis. Die Million ist eine modellierte globale Schätzung, keine IUCN-Zählung; jeder KPI behält seinen eigenen Nenner und seine Einheit.',
                    es: 'Observado/estimado: IPBES informa de cerca de un millón de especies animales y vegetales amenazadas sobre un total estimado de unos ocho millones, 75% de la tierra significativamente alterada, 66% del océano sometido a impactos acumulativos crecientes y más del 85% de humedales perdidos respecto de su base histórica. El millón es una estimación global modelada, no un conteo de UICN; cada KPI conserva su denominador y unidad.',
                    nl: 'Waargenomen/geschat: IPBES meldt ongeveer één miljoen bedreigde dier- en plantensoorten binnen een geschat totaal van circa acht miljoen, 75% aanzienlijk veranderd land, 66% van de oceaan met toenemende cumulatieve effecten en meer dan 85% verlies van wetlands tegenover de historische basis. Het miljoen is een gemodelleerde wereldschatting, geen IUCN-telling; elke KPI behoudt eigen noemer en eenheid.',
                    sv: 'Observerat/uppskattat: IPBES anger omkring en miljon hotade djur- och växtarter av totalt ungefär åtta miljoner uppskattade arter, 75% tydligt förändrad landyta, 66% av havet med ökande kumulativa effekter och mer än 85% förlorad våtmarksareal jämfört med historisk baslinje. En miljon är en modellerad global uppskattning, inte ett IUCN-antal; varje KPI behåller egen nämnare och enhet.',
                    pl: 'Zaobserwowane/oszacowane: IPBES podaje około miliona zagrożonych gatunków zwierząt i roślin z szacowanych około ośmiu milionów, 75% znacząco zmienionych lądów, 66% oceanu pod rosnącym skumulowanym wpływem oraz ponad 85% utraconych mokradeł względem historycznej bazy. Milion jest modelowym szacunkiem globalnym, nie liczbą z Czerwonej Listy IUCN; każdy KPI zachowuje własny mianownik i jednostkę.',
                ),
                'payload' => [
                    'items' => [
                        ['label' => 'Species threatened (global estimate)', 'value' => '~1 million'],
                        ['label' => 'Land significantly altered', 'value' => '75%'],
                        ['label' => 'Ocean with increasing cumulative impacts', 'value' => '66%'],
                        ['label' => 'Wetland area lost from historical baseline', 'value' => '>85%'],
                    ],
                ],
                'schema_version' => 1,
                'sort_order' => 1,
            ],
            $this->shared->chartVisualization(
                key: 'living_planet_index',
                type: VisualizationType::Area,
                title: $this->shared->t(
                    en: 'Living Planet Index, 1970–2020', it: 'Living Planet Index, 1970–2020', fr: 'Indice Planète Vivante, 1970–2020', de: 'Living Planet Index, 1970–2020', es: 'Índice Planeta Vivo, 1970–2020', nl: 'Living Planet Index, 1970–2020', sv: 'Living Planet Index, 1970–2020', pl: 'Living Planet Index, 1970–2020',
                ),
                description: $this->shared->t(
                    en: 'Indexed change in the average abundance of monitored vertebrate populations.',
                    it: 'Variazione indicizzata dell’abbondanza media delle popolazioni di vertebrati monitorate.',
                    fr: 'Variation indexée de l’abondance moyenne des populations de vertébrés suivies.',
                    de: 'Indexierte Veränderung der mittleren Häufigkeit beobachteter Wirbeltierpopulationen.',
                    es: 'Cambio indexado de la abundancia media de poblaciones de vertebrados monitoreadas.',
                    nl: 'Geïndexeerde verandering in de gemiddelde omvang van gemonitorde gewervelde populaties.',
                    sv: 'Indexerad förändring i genomsnittlig förekomst hos övervakade ryggradsdjurspopulationer.',
                    pl: 'Indeksowana zmiana średniej liczebności monitorowanych populacji kręgowców.',
                ),
                reasoning: $this->shared->t(
                    en: 'Derived: the 1970 baseline is indexed to 100. The 2020 endpoint is 100 × (1 − 0.73) = 27, using the reported 73% average decline. The report subset covers 34,836 monitored populations of 5,495 vertebrate species; it is not a count of all species, all populations or individual animals, and the two-point display does not imply a linear annual path.',
                    it: 'Derivato: la baseline 1970 è indicizzata a 100. Il valore 2020 è 100 × (1 − 0,73) = 27, usando il calo medio riportato del 73%. Il sottoinsieme copre 34.836 popolazioni monitorate di 5.495 specie di vertebrati; non è un conteggio di tutte le specie, popolazioni o individui e i due punti non implicano un andamento annuo lineare.',
                    fr: 'Dérivé: la référence 1970 est indexée à 100. La valeur 2020 est 100 × (1 − 0,73) = 27, à partir du déclin moyen déclaré de 73%. Le sous-ensemble couvre 34 836 populations suivies de 5 495 espèces de vertébrés; ce n’est pas un compte de toutes les espèces, populations ou individus, et les deux points n’impliquent pas une trajectoire annuelle linéaire.',
                    de: 'Abgeleitet: Die Basis 1970 wird auf 100 gesetzt. Der Endwert 2020 ist 100 × (1 − 0,73) = 27 auf Grundlage des berichteten mittleren Rückgangs von 73%. Die Teilmenge umfasst 34.836 beobachtete Populationen von 5.495 Wirbeltierarten; sie zählt nicht alle Arten, Populationen oder Individuen, und die zwei Punkte bedeuten keinen linearen Jahresverlauf.',
                    es: 'Derivado: la base de 1970 se indexa a 100. El punto de 2020 es 100 × (1 − 0,73) = 27, usando el descenso medio informado del 73%. El subconjunto cubre 34.836 poblaciones monitoreadas de 5.495 especies de vertebrados; no cuenta todas las especies, poblaciones o individuos y los dos puntos no implican una trayectoria anual lineal.',
                    nl: 'Afgeleid: de basis van 1970 is 100. Het eindpunt in 2020 is 100 × (1 − 0,73) = 27 op basis van de gemelde gemiddelde daling van 73%. De subset omvat 34.836 gemonitorde populaties van 5.495 gewervelde soorten; het is geen telling van alle soorten, populaties of dieren en de twee punten impliceren geen lineair jaarpad.',
                    sv: 'Härlett: 1970 års baslinje sätts till 100. Slutvärdet 2020 är 100 × (1 − 0,73) = 27 utifrån den rapporterade genomsnittliga minskningen på 73%. Delmängden omfattar 34 836 övervakade populationer av 5 495 ryggradsdjursarter; den räknar inte alla arter, populationer eller individer och de två punkterna innebär ingen linjär årsutveckling.',
                    pl: 'Pochodne: bazę z 1970 r. indeksuje się do 100. Punkt dla 2020 r. to 100 × (1 − 0,73) = 27, na podstawie zgłoszonego średniego spadku o 73%. Próba obejmuje 34 836 monitorowanych populacji 5 495 gatunków kręgowców; nie jest to liczba wszystkich gatunków, populacji ani osobników, a dwa punkty nie oznaczają liniowej ścieżki rocznej.',
                ),
                labels: ['1970', '2020'],
                series: [100, 27],
                xLabel: 'Year', xType: 'temporal', yLabel: 'Living Planet Index', yUnit: 'index (1970=100)', yFormat: 'decimal',
                sources: [$this->shared->sources()['living_planet_index'], $this->shared->sources()['living_planet_technical']],
                sortOrder: 2,
            ),
            $this->shared->chartVisualization(
                key: 'wetland_extent_index',
                type: VisualizationType::Area,
                title: $this->shared->t(
                    en: 'Global wetland extent index', it: 'Indice globale dell’estensione delle zone umide', fr: 'Indice mondial de l’étendue des zones humides', de: 'Globaler Index der Feuchtgebietsfläche', es: 'Índice mundial de extensión de humedales', nl: 'Wereldwijde index voor wetlandoppervlak', sv: 'Globalt index för våtmarksutbredning', pl: 'Globalny indeks zasięgu mokradeł',
                ),
                description: $this->shared->t(
                    en: 'Indexed global wetland extent using the 2025 outlook’s estimated loss since 1970.', it: 'Estensione globale indicizzata usando la perdita stimata dal 1970 nel rapporto 2025.', fr: 'Étendue mondiale indexée à partir de la perte estimée depuis 1970 dans l’édition 2025.', de: 'Indexierte globale Feuchtgebietsfläche anhand des im Bericht 2025 geschätzten Verlusts seit 1970.', es: 'Extensión mundial indexada con la pérdida estimada desde 1970 en el informe de 2025.', nl: 'Geïndexeerd wereldwijd wetlandoppervlak op basis van het in 2025 geschatte verlies sinds 1970.', sv: 'Indexerad global våtmarksutbredning utifrån 2025 års uppskattade förlust sedan 1970.', pl: 'Indeksowany globalny zasięg mokradeł na podstawie oszacowanej w 2025 r. straty od 1970 r.',
                ),
                reasoning: $this->shared->t(
                    en: 'Derived: set estimated 1970 global wetland extent to 100. The 2025 assessment reports an estimated 22% loss since 1970, so remaining index = 100 × (1 − 0.22) = 78. The source estimates 411 million hectares lost and an ongoing 0.52% annual decline; the chart does not interpolate unobserved annual values and does not replace ecosystem-specific monitoring.',
                    it: 'Derivato: l’estensione globale stimata nel 1970 è posta a 100. La valutazione 2025 riporta una perdita stimata del 22%, quindi indice residuo = 100 × (1 − 0,22) = 78. La fonte stima 411 milioni di ettari persi e un calo annuo in corso dello 0,52%; il grafico non interpola valori annuali non osservati e non sostituisce il monitoraggio per ecosistema.',
                    fr: 'Dérivé: l’étendue mondiale estimée en 1970 vaut 100. L’évaluation 2025 estime une perte de 22%, donc indice restant = 100 × (1 − 0,22) = 78. La source estime 411 millions d’hectares perdus et un recul annuel actuel de 0,52%; le graphique n’interpole pas de valeurs annuelles non observées et ne remplace pas le suivi par écosystème.',
                    de: 'Abgeleitet: Die geschätzte globale Feuchtgebietsfläche 1970 wird auf 100 gesetzt. Die Bewertung 2025 nennt 22% Verlust, daher Restindex = 100 × (1 − 0,22) = 78. Die Quelle schätzt 411 Millionen verlorene Hektar und einen laufenden jährlichen Rückgang von 0,52%; die Grafik interpoliert keine unbeobachteten Jahreswerte und ersetzt kein ökosystemspezifisches Monitoring.',
                    es: 'Derivado: la extensión mundial estimada de 1970 se fija en 100. La evaluación de 2025 estima una pérdida del 22%, por lo que índice restante = 100 × (1 − 0,22) = 78. La fuente estima 411 millones de hectáreas perdidas y un descenso anual actual del 0,52%; el gráfico no interpola valores anuales no observados ni sustituye el monitoreo por ecosistema.',
                    nl: 'Afgeleid: het geschatte wereldwijde wetlandoppervlak in 1970 is 100. De beoordeling van 2025 meldt 22% verlies, dus resterende index = 100 × (1 − 0,22) = 78. De bron schat 411 miljoen hectare verlies en een lopende jaarlijkse daling van 0,52%; de grafiek interpoleert geen niet-waargenomen jaarwaarden en vervangt geen ecosysteemspecifieke monitoring.',
                    sv: 'Härlett: uppskattad global våtmarksutbredning 1970 sätts till 100. Bedömningen 2025 anger 22% förlust, alltså återstående index = 100 × (1 − 0,22) = 78. Källan uppskattar 411 miljoner hektar förlust och en fortsatt årlig minskning på 0,52%; diagrammet interpolerar inte oobserverade årsvärden och ersätter inte ekosystemspecifik övervakning.',
                    pl: 'Pochodne: szacowany globalny zasięg mokradeł w 1970 r. przyjmuje wartość 100. Ocena z 2025 r. podaje stratę 22%, więc pozostały indeks = 100 × (1 − 0,22) = 78. Źródło szacuje utratę 411 mln ha i trwający spadek 0,52% rocznie; wykres nie interpoluje nieobserwowanych wartości rocznych i nie zastępuje monitoringu poszczególnych ekosystemów.',
                ),
                labels: ['1970', '2025'], series: [100, 78],
                xLabel: 'Assessment year', xType: 'temporal', yLabel: 'Estimated wetland extent', yUnit: 'index (1970=100)', yFormat: 'decimal',
                sources: [$this->shared->sources()['ramsar_gwo'], $this->shared->sources()['ramsar_briefing']], sortOrder: 3,
            ),
            $this->shared->chartVisualization(
                key: 'protected_area_coverage',
                type: VisualizationType::Bar,
                title: $this->shared->t(
                    en: 'Protected and conserved area coverage versus 2030 target', it: 'Copertura di aree protette e conservate rispetto al target 2030', fr: 'Couverture des aires protégées et conservées face à la cible 2030', de: 'Abdeckung geschützter und erhaltener Gebiete gegenüber dem Ziel 2030', es: 'Cobertura de áreas protegidas y conservadas frente a la meta 2030', nl: 'Dekking van beschermde en behouden gebieden tegenover het doel voor 2030', sv: 'Täckning av skyddade och bevarade områden jämfört med 2030-målet', pl: 'Pokrycie obszarami chronionymi i zachowanymi wobec celu 2030',
                ),
                description: $this->shared->t(
                    en: 'Official 2024 global coverage compared with the 30% Target 3 threshold.', it: 'Copertura globale ufficiale 2024 confrontata con la soglia del 30% del Target 3.', fr: 'Couverture mondiale officielle de 2024 comparée au seuil de 30% de la Cible 3.', de: 'Offizielle globale Abdeckung 2024 gegenüber der 30%-Schwelle von Ziel 3.', es: 'Cobertura mundial oficial de 2024 comparada con el umbral del 30% de la Meta 3.', nl: 'Officiële wereldwijde dekking in 2024 tegenover de drempel van 30% van Doel 3.', sv: 'Officiell global täckning 2024 jämförd med tröskeln 30% i mål 3.', pl: 'Oficjalne globalne pokrycie w 2024 r. wobec progu 30% z Celu 3.',
                ),
                reasoning: $this->shared->t(
                    en: 'Observed plus policy target: Protected Planet reports 17.6% terrestrial and inland-water coverage and 8.4% marine and coastal coverage in 2024. CBD Target 3 sets 30% for each domain by 2030. The bars preserve separate geographic denominators; they do not add land and ocean percentages, and coverage does not by itself measure representativeness, management effectiveness, connectivity or equitable governance.',
                    it: 'Osservato più target politico: Protected Planet riporta nel 2024 il 17,6% per terre e acque interne e l’8,4% per aree marine e costiere. Il Target 3 CBD fissa il 30% per ciascun dominio entro il 2030. Le barre mantengono denominatori geografici separati; non sommano percentuali terrestri e marine e la copertura non misura da sola rappresentatività, efficacia, connettività o governance equa.',
                    fr: 'Observé plus cible politique: Protected Planet indique 17,6% pour les terres et eaux intérieures et 8,4% pour les zones marines et côtières en 2024. La Cible 3 fixe 30% pour chaque domaine d’ici 2030. Les barres gardent des dénominateurs géographiques séparés; elles n’additionnent pas terre et mer, et la couverture seule ne mesure ni représentativité, efficacité, connectivité ni gouvernance équitable.',
                    de: 'Beobachtet plus politisches Ziel: Protected Planet meldet 2024 17,6% für Land und Binnengewässer sowie 8,4% für Meeres- und Küstengebiete. CBD-Ziel 3 setzt für jeden Bereich 30% bis 2030. Die Balken behalten getrennte geografische Nenner; Land- und Meeresanteile werden nicht addiert, und Abdeckung allein misst weder Repräsentativität, Wirksamkeit, Vernetzung noch gerechte Governance.',
                    es: 'Observado más meta política: Protected Planet informa 17,6% terrestre y de aguas continentales y 8,4% marino y costero en 2024. La Meta 3 fija 30% para cada dominio en 2030. Las barras mantienen denominadores geográficos separados; no suman tierra y océano, y la cobertura sola no mide representatividad, eficacia, conectividad ni gobernanza equitativa.',
                    nl: 'Waargenomen plus beleidsdoel: Protected Planet meldt in 2024 17,6% voor land en binnenwater en 8,4% voor zee en kust. CBD-doel 3 stelt voor elk domein 30% in 2030. De balken behouden afzonderlijke geografische noemers; land- en zeepercentages worden niet opgeteld en dekking alleen meet geen representativiteit, effectiviteit, verbinding of rechtvaardig bestuur.',
                    sv: 'Observerat plus policymål: Protected Planet anger 17,6% för land och inlandsvatten samt 8,4% för hav och kust 2024. CBD-mål 3 sätter 30% för varje domän till 2030. Staplarna behåller separata geografiska nämnare; land- och havsandelar adderas inte och täckning ensam mäter inte representativitet, effektivitet, konnektivitet eller rättvis styrning.',
                    pl: 'Zaobserwowane plus cel polityczny: Protected Planet podaje w 2024 r. 17,6% dla lądów i wód śródlądowych oraz 8,4% dla mórz i wybrzeży. Cel 3 CBD ustanawia 30% dla każdej domeny do 2030 r. Słupki zachowują osobne mianowniki geograficzne; nie sumują lądów i oceanów, a samo pokrycie nie mierzy reprezentatywności, skuteczności, łączności ani sprawiedliwego zarządzania.',
                ),
                labels: ['Terrestrial and inland waters', 'Marine and coastal'],
                series: [
                    ['name' => '2024 observed', 'color' => '#38bdf8', 'values' => [17.6, 8.4]],
                    ['name' => '2030 policy target', 'color' => '#22c55e', 'values' => [30, 30]],
                ],
                xLabel: 'Geographic domain', xType: 'category', yLabel: 'Area covered', yUnit: '%', yFormat: 'percent',
                sources: [$this->shared->sources()['protected_planet_report'], $this->shared->sources()['cbd_target_3']], sortOrder: 4,
            ),
            $this->shared->chartVisualization(
                key: 'threatened_vertebrate_groups',
                type: VisualizationType::Bar,
                title: $this->shared->t(
                    en: 'Threatened share in comprehensively assessed vertebrate groups', it: 'Quota minacciata nei gruppi di vertebrati valutati in modo completo', fr: 'Part menacée dans les groupes de vertébrés évalués de façon complète', de: 'Bedrohter Anteil umfassend bewerteter Wirbeltiergruppen', es: 'Proporción amenazada en grupos de vertebrados evaluados exhaustivamente', nl: 'Bedreigd aandeel in volledig beoordeelde gewervelde groepen', sv: 'Hotad andel i heltäckande bedömda ryggradsdjursgrupper', pl: 'Udział zagrożony w kompleksowo ocenionych grupach kręgowców',
                ),
                description: $this->shared->t(
                    en: 'IUCN percentages for five comprehensively assessed vertebrate groups in the 2023 amphibian assessment context.', it: 'Percentuali IUCN per cinque gruppi di vertebrati valutati in modo completo nel contesto della valutazione anfibi 2023.', fr: 'Pourcentages UICN pour cinq groupes de vertébrés évalués de manière complète dans le contexte de l’évaluation 2023 des amphibiens.', de: 'IUCN-Anteile für fünf umfassend bewertete Wirbeltiergruppen im Kontext der Amphibienbewertung 2023.', es: 'Porcentajes de UICN para cinco grupos de vertebrados evaluados exhaustivamente en el contexto de la evaluación de anfibios de 2023.', nl: 'IUCN-percentages voor vijf volledig beoordeelde gewervelde groepen in de context van de amfibieënbeoordeling van 2023.', sv: 'IUCN-andelar för fem heltäckande bedömda ryggradsdjursgrupper i 2023 års amfibiebedömning.', pl: 'Odsetki IUCN dla pięciu kompleksowo ocenionych grup kręgowców w kontekście oceny płazów z 2023 r.',
                ),
                reasoning: $this->shared->t(
                    en: 'Observed: threatened shares are 41% amphibians, 37% sharks/rays/chimaeras, 27% mammals, 21% reptiles and 13% birds. Each percentage uses the assessed species in that taxonomic group as its denominator. The bars compare proportions, not absolute species counts, and do not claim equal assessment date, geographic detectability or threat certainty for unassessed taxa.',
                    it: 'Osservato: le quote minacciate sono 41% anfibi, 37% squali/razze/chimere, 27% mammiferi, 21% rettili e 13% uccelli. Ogni percentuale usa come denominatore le specie valutate del gruppo. Le barre confrontano proporzioni, non conteggi assoluti, e non assumono uguale data, rilevabilità geografica o certezza per taxa non valutati.',
                    fr: 'Observé: les parts menacées sont 41% amphibiens, 37% requins/raies/chimères, 27% mammifères, 21% reptiles et 13% oiseaux. Chaque pourcentage a pour dénominateur les espèces évaluées du groupe. Les barres comparent des proportions, pas des nombres absolus, et ne supposent ni même date, ni détectabilité géographique, ni certitude pour les taxons non évalués.',
                    de: 'Beobachtet: Bedrohte Anteile sind 41% Amphibien, 37% Haie/Rochen/Chimären, 27% Säugetiere, 21% Reptilien und 13% Vögel. Nenner sind jeweils die bewerteten Arten der Gruppe. Die Balken vergleichen Anteile, keine absoluten Artenzahlen, und behaupten keine gleiche Bewertungszeit, geografische Erfassbarkeit oder Sicherheit für unbewertete Taxa.',
                    es: 'Observado: las proporciones amenazadas son 41% anfibios, 37% tiburones/rayas/quimeras, 27% mamíferos, 21% reptiles y 13% aves. Cada porcentaje usa las especies evaluadas del grupo como denominador. Las barras comparan proporciones, no números absolutos, y no presuponen igual fecha, detectabilidad geográfica ni certeza para taxones no evaluados.',
                    nl: 'Waargenomen: bedreigde aandelen zijn 41% amfibieën, 37% haaien/roggen/chimaera’s, 27% zoogdieren, 21% reptielen en 13% vogels. Elke noemer bestaat uit de beoordeelde soorten in die groep. De balken vergelijken verhoudingen, geen absolute aantallen, en veronderstellen geen gelijke datum, geografische detecteerbaarheid of zekerheid voor niet-beoordeelde taxa.',
                    sv: 'Observerat: hotade andelar är 41% amfibier, 37% hajar/rockor/havsmusfiskar, 27% däggdjur, 21% reptiler och 13% fåglar. Varje procent använder bedömda arter i gruppen som nämnare. Staplarna jämför andelar, inte absoluta artantal, och antar inte samma bedömningsdatum, geografiska upptäckbarhet eller säkerhet för ej bedömda taxa.',
                    pl: 'Zaobserwowane: udziały zagrożone wynoszą 41% dla płazów, 37% dla rekinów/płaszczek/chimer, 27% dla ssaków, 21% dla gadów i 13% dla ptaków. Mianownikiem są ocenione gatunki danej grupy. Słupki porównują udziały, nie bezwzględne liczby gatunków, i nie zakładają tej samej daty oceny, wykrywalności geograficznej ani pewności dla nieocenionych taksonów.',
                ),
                labels: ['Amphibians', 'Sharks, rays and chimaeras', 'Mammals', 'Reptiles', 'Birds'], series: [41, 37, 27, 21, 13],
                xLabel: 'Comprehensively assessed taxonomic group', xType: 'category', yLabel: 'Assessed species threatened', yUnit: '%', yFormat: 'percent',
                sources: [$this->shared->sources()['iucn_amphibians'], $this->shared->sources()['iucn_summary']], sortOrder: 5,
            ),
            $this->shared->chartVisualization(
                key: 'forest_net_loss_rate',
                type: VisualizationType::Line,
                title: $this->shared->t(
                    en: 'Global net forest-area loss rate', it: 'Tasso globale di perdita netta di superficie forestale', fr: 'Taux mondial de perte nette de superficie forestière', de: 'Globale Nettoverlustrate der Waldfläche', es: 'Tasa mundial de pérdida neta de superficie forestal', nl: 'Wereldwijde nettoverliesgraad van bosoppervlak', sv: 'Global nettominskning av skogsareal', pl: 'Globalne tempo utraty netto powierzchni leśnej',
                ),
                description: $this->shared->t(
                    en: 'FAO decade averages for net forest-area change, not gross deforestation.', it: 'Medie decennali FAO della variazione netta di superficie forestale, non deforestazione lorda.', fr: 'Moyennes décennales FAO du changement net de superficie forestière, pas de la déforestation brute.', de: 'FAO-Dekadenmittel der Nettoänderung der Waldfläche, nicht der Bruttoentwaldung.', es: 'Promedios decenales de FAO del cambio neto de superficie forestal, no deforestación bruta.', nl: 'FAO-decenniumgemiddelden voor nettoverandering van bosoppervlak, niet bruto ontbossing.', sv: 'FAO:s årtiondesmedel för nettoförändring av skogsareal, inte bruttoavskogning.', pl: 'Średnie dekadowe FAO dla zmiany netto powierzchni lasów, nie wylesiania brutto.',
                ),
                reasoning: $this->shared->t(
                    en: 'Observed: FAO FRA 2020 reports average annual net forest-area losses of 7.8 million hectares in 1990–2000, 5.2 million in 2000–2010 and 4.7 million in 2010–2020. Values are transcribed without interpolation. Net change combines losses and gains and must not be read as gross deforestation or as a complete biodiversity condition measure.',
                    it: 'Osservato: FAO FRA 2020 riporta perdite nette medie annue di 7,8 milioni di ettari nel 1990–2000, 5,2 milioni nel 2000–2010 e 4,7 milioni nel 2010–2020. I valori sono trascritti senza interpolazione. La variazione netta combina perdite e guadagni e non equivale a deforestazione lorda né a una misura completa della biodiversità.',
                    fr: 'Observé: la FRA 2020 de la FAO indique des pertes nettes annuelles moyennes de 7,8 millions d’hectares en 1990–2000, 5,2 millions en 2000–2010 et 4,7 millions en 2010–2020. Les valeurs sont transcrites sans interpolation. Le changement net combine pertes et gains et ne correspond ni à la déforestation brute ni à une mesure complète de l’état de la biodiversité.',
                    de: 'Beobachtet: FAO FRA 2020 meldet mittlere jährliche Nettoverluste von 7,8 Mio. Hektar 1990–2000, 5,2 Mio. 2000–2010 und 4,7 Mio. 2010–2020. Die Werte werden ohne Interpolation übernommen. Nettoänderung kombiniert Verluste und Gewinne und ist weder Bruttoentwaldung noch ein vollständiges Biodiversitätsmaß.',
                    es: 'Observado: FRA 2020 de FAO informa pérdidas netas medias anuales de 7,8 millones de hectáreas en 1990–2000, 5,2 millones en 2000–2010 y 4,7 millones en 2010–2020. Los valores se transcriben sin interpolación. El cambio neto combina pérdidas y ganancias y no equivale a deforestación bruta ni a una medida completa de biodiversidad.',
                    nl: 'Waargenomen: FAO FRA 2020 meldt gemiddeld jaarlijks nettoverlies van 7,8 miljoen hectare in 1990–2000, 5,2 miljoen in 2000–2010 en 4,7 miljoen in 2010–2020. De waarden zijn zonder interpolatie overgenomen. Nettoverandering combineert verlies en winst en is geen bruto ontbossing of volledige biodiversiteitsmaat.',
                    sv: 'Observerat: FAO FRA 2020 anger genomsnittlig årlig nettoförlust på 7,8 miljoner hektar 1990–2000, 5,2 miljoner 2000–2010 och 4,7 miljoner 2010–2020. Värdena är direkt återgivna utan interpolation. Nettoförändring kombinerar förluster och vinster och är varken bruttoavskogning eller ett fullständigt mått på biologisk mångfald.',
                    pl: 'Zaobserwowane: FAO FRA 2020 podaje średnią roczną utratę netto 7,8 mln ha w latach 1990–2000, 5,2 mln w 2000–2010 i 4,7 mln w 2010–2020. Wartości przepisano bez interpolacji. Zmiana netto łączy straty i przyrosty i nie jest wylesianiem brutto ani pełną miarą stanu różnorodności biologicznej.',
                ),
                labels: ['1990–2000', '2000–2010', '2010–2020'], series: [7.8, 5.2, 4.7],
                xLabel: 'Assessment period', xType: 'ordinal', yLabel: 'Average annual net forest-area loss', yUnit: 'million ha/year', yFormat: 'decimal',
                sources: [$this->shared->sources()['fao_fra_2020']], sortOrder: 6,
            ),
        ];
    }
};
