<?php

declare(strict_types=1);

use App\Enums\VisualizationType;

$shared = require __DIR__.'/../_shared.php';
$riskModel = require __DIR__.'/risk_model.php';

return new class($shared, $riskModel)
{
    public function __construct(private object $shared, private object $riskModel) {}

    /** @return array<int, array<string, mixed>> */
    public function riskInputs(): array
    {
        return $this->riskModel->inputs();
    }

    /** @return array{Pessimistic: array<int, float>, Neutral: array<int, float>, Optimistic: array<int, float>} */
    public function scenarioScores(): array
    {
        return $this->riskModel->scores();
    }

    public function riskFormula(): string
    {
        return $this->riskModel->formula();
    }

    /** @return array<string, mixed> */
    public function projectionCurveVisualization(): array
    {
        $scores = $this->scenarioScores();

        return $this->shared->chartVisualization(
            key: 'projection_curve',
            type: VisualizationType::Line,
            title: $this->shared->t(
                en: 'Editorial scenario risk index',
                it: 'Indice editoriale di rischio per scenario',
                fr: 'Indice éditorial de risque par scénario',
                de: 'Redaktioneller Szenario-Risikoindex',
                es: 'Índice editorial de riesgo por escenario',
                nl: 'Redactionele risico-index per scenario',
                sv: 'Redaktionellt riskindex per scenario',
                pl: 'Redakcyjny indeks ryzyka scenariuszy',
            ),
            description: $this->shared->t(
                en: 'A source-traceable index of cross-strait coercion and crisis pressure, with full invasion, blockade and quarantine kept as distinct pathways.',
                it: 'Indice tracciabile alle fonti della pressione coercitiva e di crisi nello Stretto, mantenendo invasione, blocco e quarantena come percorsi distinti.',
                fr: 'Indice traçable aux sources de la coercition et de la pression de crise dans le détroit, distinguant invasion, blocus et quarantaine.',
                de: 'Quellenbasierter Index für Zwangs- und Krisendruck in der Taiwanstraße, wobei Invasion, Blockade und Quarantäne getrennte Pfade bleiben.',
                es: 'Índice trazable a fuentes de coerción y presión de crisis en el Estrecho, manteniendo invasión, bloqueo y cuarentena como vías distintas.',
                nl: 'Een naar bronnen herleidbare index van dwang- en crisisdruk in de Straat, met invasie, blokkade en quarantaine als afzonderlijke routes.',
                sv: 'Ett källspårbart index för tvångs- och kristryck i sundet, där invasion, blockad och karantän hålls som skilda vägar.',
                pl: 'Indeks presji przymusu i kryzysu w Cieśninie oparty na źródłach, z inwazją, blokadą i kwarantanną jako odrębnymi ścieżkami.',
            ),
            reasoning: $this->shared->t(
                en: 'The index uses seven reviewed inputs: the PLA 2027 capability milestone, observed ADIZ activity, observed naval presence, blockade/quarantine capability, the 2028 political inflection, PLA command disruption and Taiwan asymmetric resilience. Each source fact is normalized to 0–100; risk-down signals are converted as 100 minus normalized strength. For every checkpoint and scenario, the model adds the documented scenario adjustment, clamps each input to 0–100, multiplies by its stated weight, sums contributions, clamps the total to 0–100 and rounds once to one decimal. Pessimistic adjustments are non-negative and Optimistic adjustments are non-positive for every input, so ordering follows from the inputs rather than sorting final series. This is an editorial comparison index, not an empirical forecast; static sources, normalization thresholds and future implementation of resilience are material limits.',
                it: 'L’indice usa sette input revisionati: milestone PLA 2027, attività ADIZ osservata, presenza navale osservata, capacità di blocco/quarantena, inflection politica 2028, disruption del comando PLA e resilienza asimmetrica taiwanese. Ogni fatto è normalizzato 0–100; i segnali risk-down diventano 100 meno la forza normalizzata. Per ogni checkpoint e scenario il modello aggiunge l’aggiustamento dichiarato, limita ogni input a 0–100, moltiplica per il peso, somma i contributi, limita il totale a 0–100 e arrotonda una volta a un decimale. Gli aggiustamenti Pessimistic sono non negativi e quelli Optimistic non positivi, quindi l’ordine deriva dagli input e non da un riordino finale. È un indice editoriale comparativo, non una previsione empirica; fonti statiche, soglie e futura attuazione della resilienza sono limiti sostanziali.',
                fr: 'L’indice utilise sept entrées révisées: jalon APL 2027, activité ADIZ observée, présence navale observée, capacité de blocus/quarantaine, inflexion politique de 2028, perturbation du commandement APL et résilience asymétrique taïwanaise. Chaque fait est normalisé de 0 à 100; les signaux risk-down deviennent 100 moins la force normalisée. Pour chaque jalon et scénario, le modèle ajoute l’ajustement documenté, borne chaque entrée entre 0 et 100, multiplie par son poids, additionne, borne le total et arrondit une fois à une décimale. Les ajustements pessimistes sont non négatifs et les optimistes non positifs: l’ordre vient donc des entrées, sans tri final. Il s’agit d’un indice éditorial comparatif, non d’une prévision empirique; sources statiques, seuils et mise en œuvre future de la résilience sont des limites importantes.',
                de: 'Der Index nutzt sieben geprüfte Eingaben: PLA-Meilenstein 2027, beobachtete ADIZ-Aktivität, beobachtete Marinepräsenz, Blockade-/Quarantänefähigkeit, politischen Wendepunkt 2028, Störungen der PLA-Führung und Taiwans asymmetrische Resilienz. Jeder Quellenfakt wird auf 0–100 normiert; risk-down-Signale werden als 100 minus normierte Stärke umgerechnet. Je Prüfpunkt und Szenario wird die dokumentierte Anpassung addiert, jede Eingabe auf 0–100 begrenzt, gewichtet, summiert, der Gesamtwert begrenzt und einmal auf eine Dezimalstelle gerundet. Pessimistische Anpassungen sind nie negativ und optimistische nie positiv, daher entsteht die Reihenfolge aus den Eingaben statt durch nachträgliches Sortieren. Es ist ein redaktioneller Vergleichsindex, keine empirische Prognose; statische Quellen, Schwellen und künftige Umsetzung der Resilienz sind wesentliche Grenzen.',
                es: 'El índice usa siete entradas revisadas: hito EPL 2027, actividad ADIZ observada, presencia naval observada, capacidad de bloqueo/cuarentena, inflexión política de 2028, disrupción del mando del EPL y resiliencia asimétrica taiwanesa. Cada hecho se normaliza a 0–100; las señales risk-down se convierten en 100 menos la fuerza normalizada. Para cada punto y escenario se suma el ajuste documentado, se limita cada entrada a 0–100, se multiplica por su peso, se suman contribuciones, se limita el total y se redondea una vez a un decimal. Los ajustes pesimistas no son negativos y los optimistas no son positivos, por lo que el orden surge de las entradas y no de ordenar las series finales. Es un índice editorial comparativo, no una previsión empírica; fuentes estáticas, umbrales y futura ejecución de la resiliencia son límites importantes.',
                nl: 'De index gebruikt zeven beoordeelde inputs: de PLA-mijlpaal 2027, waargenomen ADIZ-activiteit, waargenomen marineaanwezigheid, blokkade-/quarantainecapaciteit, het politieke omslagpunt van 2028, verstoring van PLA-commandovoering en Taiwanese asymmetrische veerkracht. Elk bronfeit wordt genormaliseerd naar 0–100; risk-down-signalen worden 100 min de genormaliseerde sterkte. Per ijkpunt en scenario wordt de gedocumenteerde aanpassing toegevoegd, elke input begrensd, gewogen en opgeteld, waarna het totaal wordt begrensd en eenmaal op één decimaal afgerond. Pessimistische aanpassingen zijn niet negatief en optimistische niet positief, zodat de volgorde uit de inputs volgt en niet uit achteraf sorteren. Dit is een redactionele vergelijkingsindex, geen empirische voorspelling; statische bronnen, drempels en toekomstige uitvoering van veerkracht zijn belangrijke beperkingen.',
                sv: 'Indexet använder sju granskade indata: PLA:s milstolpe 2027, observerad ADIZ-aktivitet, observerad marin närvaro, blockad-/karantänförmåga, den politiska brytpunkten 2028, störningar i PLA:s ledning och Taiwans asymmetriska motståndskraft. Varje källfaktum normaliseras till 0–100; risk-down-signaler blir 100 minus normaliserad styrka. För varje kontrollpunkt och scenario läggs den dokumenterade justeringen till, varje indata begränsas, viktas och summeras, totalen begränsas och avrundas en gång till en decimal. Pessimistiska justeringar är inte negativa och optimistiska inte positiva, så ordningen följer av indata utan slutsortering. Detta är ett redaktionellt jämförelseindex, inte en empirisk prognos; statiska källor, trösklar och framtida genomförande av motståndskraft är viktiga begränsningar.',
                pl: 'Indeks wykorzystuje siedem zweryfikowanych danych: kamień milowy PLA 2027, obserwowaną aktywność ADIZ, obserwowaną obecność morską, zdolność blokady/kwarantanny, punkt polityczny 2028, zakłócenia dowodzenia PLA i asymetryczną odporność Tajwanu. Każdy fakt normalizuje się do 0–100; sygnały risk-down przelicza się jako 100 minus znormalizowana siła. Dla każdego punktu i scenariusza dodaje się udokumentowaną korektę, ogranicza każdą daną, mnoży przez wagę, sumuje wkłady, ogranicza wynik i raz zaokrągla do jednego miejsca. Korekty pesymistyczne są nieujemne, a optymistyczne niedodatnie, więc kolejność wynika z danych bez końcowego sortowania. To redakcyjny indeks porównawczy, nie prognoza empiryczna; statyczne źródła, progi i przyszłe wdrożenie odporności są istotnymi ograniczeniami.',
            ),
            labels: array_keys($this->riskModel->checkpoints()),
            series: [
                ['name' => 'Pessimistic', 'color' => '#ff2a23', 'values' => $scores['Pessimistic']],
                ['name' => 'Neutral', 'color' => '#38bdf8', 'values' => $scores['Neutral']],
                ['name' => 'Optimistic', 'color' => '#22c55e', 'values' => $scores['Optimistic']],
            ],
            xLabel: 'Scenario checkpoint',
            xType: 'temporal',
            yLabel: 'Editorial scenario risk index',
            yUnit: 'index points',
            yFormat: 'decimal',
            sources: $this->riskModel->sources(),
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
                'sources' => [
                    $this->shared->sources()['dod'],
                    $this->shared->sources()['csis_trade'],
                    $this->shared->sources()['trade_semiconductors'],
                    $this->shared->sources()['energy_resilience'],
                    $this->shared->sources()['reuters_drone'],
                ],
                'reasoning' => $this->shared->t(
                    en: 'Headline values are direct transcriptions from the cited reports. Sparklines are min–max-normalized display sequences used only to show direction and are not additional observations.',
                    it: 'I valori principali sono trascrizioni dirette delle fonti citate. Le sparkline sono sequenze normalizzate min–max usate solo per mostrare la direzione e non rappresentano osservazioni aggiuntive.',
                    fr: 'Les valeurs principales sont des transcriptions directes des sources citées. Les sparklines sont des séquences normalisées min–max servant uniquement à montrer la direction et ne constituent pas des observations supplémentaires.',
                    de: 'Die Hauptwerte sind direkte Übernahmen aus den zitierten Quellen. Die Sparklines sind Min-Max-normalisierte Darstellungsreihen, die nur die Richtung zeigen und keine zusätzlichen Beobachtungen darstellen.',
                    es: 'Los valores principales son transcripciones directas de las fuentes citadas. Las minigráficas son secuencias normalizadas mín–máx usadas solo para mostrar dirección y no son observaciones adicionales.',
                    nl: 'De hoofdwaarden zijn rechtstreeks overgenomen uit de aangehaalde bronnen. De sparklines zijn min-max-genormaliseerde weergavereeksen die alleen de richting tonen en geen extra waarnemingen zijn.',
                    sv: 'Huvudvärdena är direkt återgivna från de citerade källorna. Miniatyrkurvorna är min–max-normaliserade visningsserier som endast visar riktning och inte är ytterligare observationer.',
                    pl: 'Wartości główne są bezpośrednio przepisane z cytowanych źródeł. Miniwykresy są sekwencjami znormalizowanymi min–max, służą wyłącznie do pokazania kierunku i nie są dodatkowymi obserwacjami.',
                ),
                'payload' => [
                    'items' => [
                        ['label' => 'PLA ADIZ activity 2024', 'value' => '2,771', 'direction' => 'up', 'sparkline' => [22, 33, 31, 50, 79]],
                        ['label' => 'Taiwan Strait trade at risk', 'value' => 'US$2.45T', 'direction' => 'up', 'sparkline' => [30, 44, 56, 70, 82]],
                        ['label' => 'Leading-edge chips in Taiwan', 'value' => '>90%', 'direction' => 'up', 'sparkline' => [88, 90, 91, 92, 93]],
                        ['label' => 'Taiwan energy import dependence', 'value' => '95%', 'direction' => 'up', 'sparkline' => [90, 92, 94, 95, 95]],
                        ['label' => 'Drone special budget', 'value' => 'NT$210B', 'direction' => 'up', 'sparkline' => [20, 34, 51, 66, 82]],
                    ],
                ],
                'schema_version' => 1,
                'sort_order' => 1,
            ],
            $this->shared->chartVisualization(
                key: 'pla_pressure_trend',
                type: VisualizationType::Line,
                title: $this->shared->t(
                    en: 'PLA pressure trend',
                    it: 'Trend pressione PLA',
                    fr: 'Tendance de pression de l’APL',
                    de: 'Trend des PLA-Drucks',
                    es: 'Tendencia de presión del EPL',
                    nl: 'Trend in PLA-druk',
                    sv: 'Trend för PLA-tryck',
                    pl: 'Trend presji PLA',
                ),
                description: $this->shared->t(
                    en: 'Annual Taiwan ADIZ activity reported in the China Military Power Report.',
                    it: 'Attività annuale ADIZ di Taiwan riportata nel China Military Power Report.',
                    fr: 'Activité annuelle de l’ADIZ de Taïwan rapportée dans le China Military Power Report.',
                    de: 'Jährliche Taiwan-ADIZ-Aktivität laut China Military Power Report.',
                    es: 'Actividad anual de la ADIZ de Taiwán reportada en el China Military Power Report.',
                    nl: 'Jaarlijkse Taiwan-ADIZ-activiteit gerapporteerd in het China Military Power Report.',
                    sv: 'Årlig taiwanesisk ADIZ-aktivitet rapporterad i China Military Power Report.',
                    pl: 'Roczna aktywność ADIZ Tajwanu raportowana w China Military Power Report.',
                ),
                reasoning: $this->shared->t(
                    en: 'The four annual totals are transcribed from the cited China Military Power reporting. No interpolation or reweighting is applied; the line only connects the observed annual counts.',
                    it: 'I quattro totali annuali sono trascritti dal rapporto China Military Power citato. Non vengono applicate interpolazioni o ponderazioni; la linea collega soltanto i conteggi annuali osservati.',
                    fr: 'Les quatre totaux annuels sont transcrits du rapport China Military Power cité. Aucune interpolation ni pondération n’est appliquée; la ligne relie uniquement les comptes annuels observés.',
                    de: 'Die vier Jahressummen sind dem zitierten China-Military-Power-Bericht entnommen. Es erfolgt keine Interpolation oder Gewichtung; die Linie verbindet nur die beobachteten Jahreswerte.',
                    es: 'Los cuatro totales anuales se transcriben del informe China Military Power citado. No se aplica interpolación ni ponderación; la línea solo conecta los recuentos anuales observados.',
                    nl: 'De vier jaartotalen zijn overgenomen uit het aangehaalde China Military Power-rapport. Er is geen interpolatie of weging toegepast; de lijn verbindt alleen de waargenomen jaartellingen.',
                    sv: 'De fyra årstotalerna är direkt hämtade från den citerade China Military Power-rapporteringen. Ingen interpolering eller viktning används; linjen binder endast samman observerade årstal.',
                    pl: 'Cztery sumy roczne są przepisane z cytowanego raportu China Military Power. Nie stosuje się interpolacji ani ważenia; linia łączy wyłącznie zaobserwowane wartości roczne.',
                ),
                labels: ['2021', '2022', '2023', '2024'],
                series: [972, 1733, 1703, 2771],
                xLabel: 'Year',
                xType: 'temporal',
                yLabel: 'PLA ADIZ activity',
                yUnit: 'events',
                yFormat: 'integer',
                sources: [$this->shared->sources()['dod']],
                sortOrder: 2,
            ),
            $this->shared->chartVisualization(
                key: 'naval_pressure_2026',
                type: VisualizationType::Bar,
                title: $this->shared->t(
                    en: 'Naval pressure 2026',
                    it: 'Pressione navale 2026',
                    fr: 'Pression navale 2026',
                    de: 'Maritimer Druck 2026',
                    es: 'Presión naval 2026',
                    nl: 'Marinedruk 2026',
                    sv: 'Marint tryck 2026',
                    pl: 'Presja morska 2026',
                ),
                description: $this->shared->t(
                    en: 'Display baseline versus July 2026 reports of more than 110 military and coast guard ships in the region.',
                    it: 'Baseline espositiva contro i report di luglio 2026 su oltre 110 navi militari e della guardia costiera nella regione.',
                    fr: 'Référence visuelle comparée aux rapports de juillet 2026 signalant plus de 110 navires militaires et garde-côtes dans la région.',
                    de: 'Anzeigebasis im Vergleich zu Berichten vom Juli 2026 über mehr als 110 Militär- und Küstenwachschiffe in der Region.',
                    es: 'Referencia visual frente a informes de julio de 2026 de más de 110 buques militares y de guardia costera en la región.',
                    nl: 'Weergavebasis tegenover rapporten van juli 2026 over meer dan 110 militaire en kustwachtschepen in de regio.',
                    sv: 'Visningsbaslinje jämfört med rapporter från juli 2026 om fler än 110 militära och kustbevakningsfartyg i regionen.',
                    pl: 'Bazowy poziom prezentacji wobec raportów z lipca 2026 r. o ponad 110 okrętach wojskowych i straży przybrzeżnej w regionie.',
                ),
                reasoning: $this->shared->t(
                    en: 'The July 2026 value of 110 ships is taken from the cited report. The 50-ship bar is an explicitly labelled display baseline equal to 45.5% of the reported level; it is not presented as a historical observation.',
                    it: 'Il valore di 110 navi nel luglio 2026 proviene dalla fonte citata. La barra da 50 navi è una baseline grafica dichiarata, pari al 45,5% del livello riportato; non è presentata come osservazione storica.',
                    fr: 'La valeur de 110 navires en juillet 2026 provient de la source citée. La barre de 50 navires est une référence visuelle explicitement indiquée, égale à 45,5% du niveau rapporté; elle n’est pas présentée comme une observation historique.',
                    de: 'Der Wert von 110 Schiffen im Juli 2026 stammt aus der zitierten Quelle. Der Balken mit 50 Schiffen ist eine ausdrücklich gekennzeichnete Darstellungsbasis von 45,5% des gemeldeten Werts und keine historische Beobachtung.',
                    es: 'El valor de 110 buques en julio de 2026 procede de la fuente citada. La barra de 50 buques es una referencia visual explícita equivalente al 45,5% del nivel informado; no se presenta como observación histórica.',
                    nl: 'De waarde van 110 schepen in juli 2026 komt uit de aangehaalde bron. De balk van 50 schepen is een expliciet gelabelde weergavebasis van 45,5% van het gemelde niveau en geen historische waarneming.',
                    sv: 'Värdet 110 fartyg i juli 2026 kommer från den citerade källan. Stapeln med 50 fartyg är en tydligt märkt visningsbaslinje på 45,5% av den rapporterade nivån och inte en historisk observation.',
                    pl: 'Wartość 110 okrętów w lipcu 2026 pochodzi z cytowanego źródła. Słupek 50 okrętów jest wyraźnie oznaczoną bazą prezentacyjną równą 45,5% zgłoszonego poziomu, a nie obserwacją historyczną.',
                ),
                labels: ['Display baseline', 'July 2026 report'],
                series: [50, 110],
                xLabel: 'Observation',
                xType: 'category',
                yLabel: 'Military and coast guard ships',
                yUnit: 'ships',
                yFormat: 'integer',
                sources: [$this->shared->sources()['reuters_naval']],
                sortOrder: 3,
            ),
            $this->shared->chartVisualization(
                key: 'economic_exposure',
                type: VisualizationType::Bar,
                title: $this->shared->t(
                    en: 'Economic exposure',
                    it: 'Esposizione economica',
                    fr: 'Exposition économique',
                    de: 'Wirtschaftliche Exponierung',
                    es: 'Exposición económica',
                    nl: 'Economische blootstelling',
                    sv: 'Ekonomisk exponering',
                    pl: 'Ekspozycja gospodarcza',
                ),
                description: $this->shared->t(
                    en: 'Trade, annual activity and sanctions shock ranges exposed by Taiwan Strait disruption.',
                    it: 'Commercio, attività annuale e shock da sanzioni esposti a una crisi nello Stretto di Taiwan.',
                    fr: 'Commerce, activité annuelle et fourchettes de choc de sanctions exposés par une perturbation du détroit de Taïwan.',
                    de: 'Handel, Jahresaktivität und Sanktionsschock-Spannen, die durch eine Störung der Taiwanstraße exponiert sind.',
                    es: 'Comercio, actividad anual y rangos de choque por sanciones expuestos por una disrupción del Estrecho de Taiwán.',
                    nl: 'Handel, jaarlijkse activiteit en sanctieschokbereiken blootgesteld door verstoring van de Straat van Taiwan.',
                    sv: 'Handel, årlig aktivitet och sanktionschockintervall som exponeras av en störning i Taiwansundet.',
                    pl: 'Handel, roczna aktywność i zakresy szoku sankcyjnego narażone przez zakłócenia w Cieśninie Tajwańskiej.',
                ),
                reasoning: $this->shared->t(
                    en: 'US$2.45 trillion is the cited Taiwan Strait trade estimate. US$2.0 trillion and US$3.0 trillion represent the annual-activity and upper sanctions-shock values reported in the cited disruption analysis; all values are displayed in constant US$ trillions for comparison.',
                    it: 'US$2,45 trilioni è la stima citata del commercio nello Stretto di Taiwan. US$2,0 e US$3,0 trilioni rappresentano i valori di attività annuale e del limite superiore dello shock da sanzioni riportati nell’analisi citata; tutti i valori sono mostrati in trilioni di dollari per il confronto.',
                    fr: 'Les 2,45 billions de dollars correspondent à l’estimation citée du commerce dans le détroit de Taïwan. Les valeurs de 2,0 et 3,0 billions représentent respectivement l’activité annuelle et la borne haute du choc de sanctions de l’analyse citée; toutes sont affichées en billions de dollars comparables.',
                    de: '2,45 Billionen US-Dollar entsprechen der zitierten Schätzung des Handels durch die Taiwanstraße. 2,0 und 3,0 Billionen US-Dollar sind die in der zitierten Analyse genannten Werte für jährliche Aktivität und die obere Sanktionsschockgrenze; alle Werte werden vergleichbar in Billionen US-Dollar dargestellt.',
                    es: 'US$2,45 billones es la estimación citada del comercio en el Estrecho de Taiwán. US$2,0 y US$3,0 billones representan la actividad anual y el límite superior del choque de sanciones del análisis citado; todos los valores se muestran en billones de dólares comparables.',
                    nl: 'US$2,45 biljoen is de aangehaalde schatting van de handel door de Straat van Taiwan. US$2,0 en US$3,0 biljoen zijn de waarden voor jaarlijkse activiteit en de bovengrens van de sanctieschok uit de aangehaalde analyse; alle waarden worden vergelijkbaar in biljoenen dollars weergegeven.',
                    sv: '2,45 biljoner US-dollar är den citerade uppskattningen av handeln genom Taiwansundet. 2,0 och 3,0 biljoner US-dollar är värdena för årlig aktivitet respektive den övre sanktionschocken i den citerade analysen; alla visas i jämförbara biljoner US-dollar.',
                    pl: '2,45 bln USD to cytowana wartość handlu przez Cieśninę Tajwańską. 2,0 i 3,0 bln USD odpowiadają wartości aktywności rocznej i górnej granicy szoku sankcyjnego z cytowanej analizy; wszystkie wartości pokazano w porównywalnych bilionach USD.',
                ),
                labels: ['Strait trade', 'Annual activity at risk', 'Max sanctions shock'],
                series: [2.45, 2.0, 3.0],
                xLabel: 'Exposure category',
                xType: 'category',
                yLabel: 'Economic value exposed',
                yUnit: 'US$ trillion',
                yFormat: 'currency',
                sources: [$this->shared->sources()['csis_trade'], $this->shared->sources()['rhodium_disruptions']],
                sortOrder: 4,
            ),
            $this->shared->chartVisualization(
                key: 'scenario_gdp_shock',
                type: VisualizationType::Bar,
                title: $this->shared->t(
                    en: 'Scenario GDP shock',
                    it: 'Shock PIL per scenario',
                    fr: 'Choc de PIB par scénario',
                    de: 'BIP-Schock nach Szenario',
                    es: 'Choque del PIB por escenario',
                    nl: 'BBP-schok per scenario',
                    sv: 'BNP-chock per scenario',
                    pl: 'Szok PKB według scenariusza',
                ),
                description: $this->shared->t(
                    en: 'Editorial midpoint estimates for first-year Taiwan GDP shock by scenario.',
                    it: 'Stime editoriali mediane dello shock sul PIL taiwanese nel primo anno per scenario.',
                    fr: 'Estimations éditoriales médianes du choc sur le PIB taïwanais la première année par scénario.',
                    de: 'Redaktionelle Mittelpunkt-Schätzungen für Taiwans BIP-Schock im ersten Jahr nach Szenario.',
                    es: 'Estimaciones editoriales de punto medio del choque del PIB taiwanés en el primer año por escenario.',
                    nl: 'Redactionele middelpuntsschattingen voor de Taiwanese BBP-schok in het eerste jaar per scenario.',
                    sv: 'Redaktionella mittpunktsestimat för Taiwans BNP-chock under första året per scenario.',
                    pl: 'Redakcyjne szacunki punktu środkowego szoku PKB Tajwanu w pierwszym roku według scenariusza.',
                ),
                reasoning: $this->shared->t(
                    en: 'The 15%, 32% and 45% values are editorial midpoints selected within the scenario ranges discussed by the cited disruption analysis. They provide a consistent comparison scale and must not be read as official point forecasts.',
                    it: 'I valori 15%, 32% e 45% sono punti medi editoriali scelti negli intervalli di scenario discussi dall’analisi citata. Forniscono una scala di confronto coerente e non devono essere letti come previsioni ufficiali puntuali.',
                    fr: 'Les valeurs de 15%, 32% et 45% sont des points médians éditoriaux choisis dans les fourchettes de scénarios de l’analyse citée. Elles fournissent une échelle de comparaison cohérente et ne constituent pas des prévisions ponctuelles officielles.',
                    de: 'Die Werte 15%, 32% und 45% sind redaktionelle Mittelpunkte innerhalb der in der zitierten Analyse diskutierten Szenariospannen. Sie dienen einer konsistenten Vergleichsskala und sind keine offiziellen Punktprognosen.',
                    es: 'Los valores 15%, 32% y 45% son puntos medios editoriales dentro de los rangos de escenarios del análisis citado. Ofrecen una escala coherente de comparación y no deben interpretarse como previsiones puntuales oficiales.',
                    nl: 'De waarden 15%, 32% en 45% zijn redactionele middelpunten binnen de scenariobandbreedtes uit de aangehaalde analyse. Ze bieden een consistente vergelijkingsschaal en zijn geen officiële puntvoorspellingen.',
                    sv: 'Värdena 15%, 32% och 45% är redaktionella mittpunkter inom scenariointervallen i den citerade analysen. De ger en konsekvent jämförelseskala och ska inte läsas som officiella punktprognoser.',
                    pl: 'Wartości 15%, 32% i 45% są redakcyjnymi punktami środkowymi w przedziałach scenariuszy omawianych w cytowanej analizie. Zapewniają spójną skalę porównania i nie są oficjalnymi prognozami punktowymi.',
                ),
                labels: ['Optimistic', 'Neutral', 'Pessimistic'],
                series: [15, 32, 45],
                xLabel: 'Scenario',
                xType: 'category',
                yLabel: 'First-year Taiwan GDP shock',
                yUnit: '%',
                yFormat: 'percent',
                sources: [$this->shared->sources()['rhodium_disruptions']],
                sortOrder: 5,
            ),
            [
                'key' => 'energy_resilience',
                'type' => VisualizationType::Kpi,
                'title' => $this->shared->t(
                    en: 'Energy resilience',
                    it: 'Resilienza energetica',
                    fr: 'Résilience énergétique',
                    de: 'Energieresilienz',
                    es: 'Resiliencia energética',
                    nl: 'Energieveerkracht',
                    sv: 'Energiresiliens',
                    pl: 'Odporność energetyczna',
                ),
                'description' => $this->shared->t(
                    en: 'Energy import dependence, oil and gas dependence, and displayed LNG reserve days.',
                    it: 'Dipendenza energetica, dipendenza da petrolio e gas e giorni di riserva LNG visualizzati.',
                    fr: 'Dépendance aux importations d’énergie, dépendance au pétrole et au gaz, et jours de réserve GNL affichés.',
                    de: 'Energieimportabhängigkeit, Öl- und Gasabhängigkeit sowie angezeigte LNG-Reservetage.',
                    es: 'Dependencia de importación energética, dependencia de petróleo y gas, y días de reserva de GNL mostrados.',
                    nl: 'Afhankelijkheid van energie-import, olie- en gasafhankelijkheid en weergegeven LNG-reservedagen.',
                    sv: 'Energimportberoende, olje- och gasberoende samt visade LNG-reservdagar.',
                    pl: 'Zależność od importu energii, ropy i gazu oraz prezentowane dni rezerw LNG.',
                ),
                'sources' => [$this->shared->sources()['energy_resilience']],
                'reasoning' => $this->shared->t(
                    en: 'The 95%, 99% and 12-day values are transcribed from the cited energy-resilience analysis. They use incompatible units, so they are intentionally presented as separate KPI values rather than combined on one chart axis.',
                    it: 'I valori 95%, 99% e 12 giorni sono trascritti dall’analisi citata sulla resilienza energetica. Usano unità incompatibili e sono quindi presentati come KPI separati, non combinati su un unico asse.',
                    fr: 'Les valeurs de 95%, 99% et 12 jours sont transcrites de l’analyse citée sur la résilience énergétique. Leurs unités étant incompatibles, elles sont volontairement présentées comme KPI séparés plutôt que sur un axe commun.',
                    de: 'Die Werte 95%, 99% und 12 Tage stammen aus der zitierten Analyse zur Energieresilienz. Da die Einheiten nicht kompatibel sind, werden sie bewusst als getrennte KPI statt auf einer gemeinsamen Achse dargestellt.',
                    es: 'Los valores 95%, 99% y 12 días se transcriben del análisis citado sobre resiliencia energética. Como usan unidades incompatibles, se muestran deliberadamente como KPI separados y no en un mismo eje.',
                    nl: 'De waarden 95%, 99% en 12 dagen zijn overgenomen uit de aangehaalde analyse over energieveerkracht. Omdat de eenheden niet compatibel zijn, worden ze bewust als afzonderlijke KPI weergegeven en niet op één as gecombineerd.',
                    sv: 'Värdena 95%, 99% och 12 dagar är hämtade från den citerade analysen av energiresiliens. Eftersom enheterna inte är kompatibla visas de avsiktligt som separata KPI-värden i stället för på en gemensam axel.',
                    pl: 'Wartości 95%, 99% i 12 dni są przepisane z cytowanej analizy odporności energetycznej. Ze względu na niezgodne jednostki są celowo prezentowane jako oddzielne KPI, a nie na wspólnej osi.',
                ),
                'payload' => [
                    'items' => [
                        ['label' => 'Energy import dependence', 'value' => '95%'],
                        ['label' => 'Oil and gas import dependence', 'value' => '99%'],
                        ['label' => 'Displayed LNG reserve', 'value' => '12 days'],
                    ],
                ],
                'schema_version' => 1,
                'sort_order' => 6,
            ],
        ];
    }
};
