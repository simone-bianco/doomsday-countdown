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
            'title' => $this->t(
                en: 'Taiwan Invasion',
                it: 'Invasione di Taiwan',
                fr: 'Invasion de Taïwan',
                de: 'Invasion Taiwans',
                es: 'Invasión de Taiwán',
                nl: 'Invasie van Taiwan',
                sv: 'Invasion av Taiwan',
                pl: 'Inwazja na Tajwan',
            ),
            'summary' => $this->t(
                en: 'China–Taiwan conflict risk and the 2027 readiness window',
                it: 'Rischio di conflitto Cina–Taiwan e finestra di prontezza 2027',
                fr: 'Risque de conflit Chine–Taïwan et fenêtre de préparation 2027',
                de: 'Risiko eines China–Taiwan-Konflikts und Bereitschaftsfenster 2027',
                es: 'Riesgo de conflicto China–Taiwán y ventana de preparación de 2027',
                nl: 'Risico op conflict tussen China en Taiwan en het gereedheidsvenster van 2027',
                sv: 'Risk för konflikt mellan Kina och Taiwan och beredskapsfönstret 2027',
                pl: 'Ryzyko konfliktu Chiny–Tajwan i okno gotowości w 2027 roku',
            ),
            'description' => $this->t(
                en: 'A large-scale amphibious invasion of Taiwan is plausible but remains a lower-probability path than blockade, quarantine, cyber pressure, sabotage and other coercive grey-zone operations in the near term. The countdown uses public-source indicators to frame a 2027 readiness window, not a certain event date.',
                it: 'Una invasione anfibia su larga scala di Taiwan è plausibile, ma nel breve termine resta meno probabile di blocco, quarantena, pressione cyber, sabotaggio e altre operazioni coercitive grey-zone. Il countdown usa indicatori da fonti pubbliche per inquadrare una finestra di prontezza 2027, non una data certa.',
                fr: 'Une invasion amphibie de grande ampleur de Taïwan est plausible, mais reste à court terme moins probable qu’un blocus, une quarantaine, une pression cyber, du sabotage et d’autres opérations coercitives de zone grise. Le compte à rebours utilise des indicateurs de sources publiques pour cadrer une fenêtre de préparation en 2027, pas une date certaine d’événement.',
                de: 'Eine groß angelegte amphibische Invasion Taiwans ist plausibel, bleibt kurzfristig aber weniger wahrscheinlich als Blockade, Quarantäne, Cyberdruck, Sabotage und andere coercive Grauzonen-Operationen. Der Countdown nutzt öffentlich zugängliche Indikatoren, um ein Bereitschaftsfenster 2027 einzuordnen, nicht ein sicheres Ereignisdatum.',
                es: 'Una invasión anfibia a gran escala de Taiwán es plausible, pero a corto plazo sigue siendo menos probable que un bloqueo, una cuarentena, presión cibernética, sabotaje y otras operaciones coercitivas de zona gris. La cuenta atrás usa indicadores de fuentes públicas para enmarcar una ventana de preparación en 2027, no una fecha segura de evento.',
                nl: 'Een grootschalige amfibische invasie van Taiwan is plausibel, maar blijft op korte termijn minder waarschijnlijk dan blokkade, quarantaine, cyberdruk, sabotage en andere dwingende grey-zone-operaties. De countdown gebruikt indicatoren uit openbare bronnen om een gereedheidsvenster in 2027 te kaderen, niet een zekere gebeurtenisdatum.',
                sv: 'En storskalig amfibisk invasion av Taiwan är möjlig, men är på kort sikt fortfarande mindre sannolik än blockad, karantän, cybertryck, sabotage och andra tvingande gråzonsoperationer. Nedräkningen använder indikatorer från öppna källor för att rama in ett beredskapsfönster 2027, inte ett säkert händelsedatum.',
                pl: 'Zakrojona na dużą skalę amfibijna inwazja na Tajwan jest możliwa, ale w krótkim terminie pozostaje mniej prawdopodobna niż blokada, kwarantanna, presja cybernetyczna, sabotaż i inne przymusowe działania w szarej strefie. Licznik wykorzystuje wskaźniki ze źródeł publicznych, aby opisać okno gotowości w 2027 roku, a nie pewną datę wydarzenia.',
            ),
            'causes' => $this->tl(
                en: [
                    'PLA modernization and the 2027 readiness marker.',
                    'Grey-zone pressure, coast guard normalization and blockade rehearsal.',
                    'Domestic legitimacy and coercive bargaining risk.',
                    'Taiwan semiconductor concentration and strategic geography.',
                ],
                it: [
                    'Modernizzazione del PLA e soglia di prontezza 2027.',
                    'Pressione grey-zone, normalizzazione della guardia costiera e prove di blocco.',
                    'Legittimità interna e rischio di coercizione negoziale.',
                    'Concentrazione dei semiconduttori a Taiwan e geografia strategica.',
                ],
                fr: [
                    'Modernisation de l’APL et repère de préparation 2027.',
                    'Pression de zone grise, normalisation des garde-côtes et répétitions de blocus.',
                    'Légitimité intérieure et risque de marchandage coercitif.',
                    'Concentration des semi-conducteurs à Taïwan et géographie stratégique.',
                ],
                de: [
                    'Modernisierung der PLA und Bereitschaftsmarke 2027.',
                    'Grauzonendruck, Normalisierung der Küstenwache und Blockadeproben.',
                    'Innenpolitische Legitimität und Risiko coerciver Verhandlungen.',
                    'Konzentration der Halbleiter in Taiwan und strategische Geografie.',
                ],
                es: [
                    'Modernización del EPL y marcador de preparación de 2027.',
                    'Presión de zona gris, normalización de la guardia costera y ensayos de bloqueo.',
                    'Legitimidad interna y riesgo de negociación coercitiva.',
                    'Concentración de semiconductores en Taiwán y geografía estratégica.',
                ],
                nl: [
                    'Modernisering van de PLA en het gereedheidsijkpunt van 2027.',
                    'Grey-zone-druk, normalisering van de kustwacht en blokkaderepetities.',
                    'Binnenlandse legitimiteit en risico op dwingend onderhandelen.',
                    'Concentratie van halfgeleiders in Taiwan en strategische geografie.',
                ],
                sv: [
                    'PLA-modernisering och beredskapsmarkören 2027.',
                    'Gråzonstryck, normalisering av kustbevakning och blockadövningar.',
                    'Inhemsk legitimitet och risk för tvångsförhandlingar.',
                    'Taiwans koncentration av halvledare och strategiska geografi.',
                ],
                pl: [
                    'Modernizacja PLA i znacznik gotowości 2027.',
                    'Presja szarej strefy, normalizacja działań straży przybrzeżnej i próby blokady.',
                    'Legitymacja wewnętrzna i ryzyko przymusowych negocjacji.',
                    'Koncentracja półprzewodników na Tajwanie i geografia strategiczna.',
                ],
            ),
            'consequences' => $this->tl(
                en: [
                    'Missile, cyber and information opening phase followed by blockade pressure.',
                    'Regional escalation risk involving U.S., Japan and Philippine posture.',
                    'Multi-trillion-dollar trade and semiconductor shock.',
                    'Energy, logistics and civil-resilience stress inside Taiwan.',
                    'Severe humanitarian pressure and displacement risk.',
                ],
                it: [
                    'Fase iniziale missilistica, cyber e informativa seguita da pressione di blocco.',
                    'Rischio di escalation regionale con postura di Stati Uniti, Giappone e Filippine.',
                    'Shock da migliaia di miliardi su commercio e semiconduttori.',
                    'Stress energetico, logistico e di resilienza civile a Taiwan.',
                    'Grave pressione umanitaria e rischio di sfollamento.',
                ],
                fr: [
                    'Phase initiale missile, cyber et informationnelle suivie d’une pression de blocus.',
                    'Risque d’escalade régionale impliquant la posture des États-Unis, du Japon et des Philippines.',
                    'Choc commercial et des semi-conducteurs de plusieurs milliers de milliards de dollars.',
                    'Stress énergétique, logistique et de résilience civile à Taïwan.',
                    'Forte pression humanitaire et risque de déplacement.',
                ],
                de: [
                    'Raketen-, Cyber- und Informationsauftakt gefolgt von Blockadedruck.',
                    'Regionales Eskalationsrisiko mit Einbindung der Haltung der USA, Japans und der Philippinen.',
                    'Handels- und Halbleiterschock in Billionenhöhe.',
                    'Energie-, Logistik- und ziviler Resilienzstress innerhalb Taiwans.',
                    'Schwerer humanitärer Druck und Vertreibungsrisiko.',
                ],
                es: [
                    'Fase inicial de misiles, ciber e información seguida de presión de bloqueo.',
                    'Riesgo de escalada regional que involucra la postura de EE. UU., Japón y Filipinas.',
                    'Choque comercial y de semiconductores de varios billones de dólares.',
                    'Estrés energético, logístico y de resiliencia civil dentro de Taiwán.',
                    'Grave presión humanitaria y riesgo de desplazamiento.',
                ],
                nl: [
                    'Openingsfase met raketten, cyber en informatie gevolgd door blokkadedruk.',
                    'Risico op regionale escalatie met de houding van de VS, Japan en de Filipijnen.',
                    'Handels- en halfgeleiderschok van meerdere biljoenen dollars.',
                    'Energie-, logistieke en civiele weerbaarheidsstress binnen Taiwan.',
                    'Ernstige humanitaire druk en risico op ontheemding.',
                ],
                sv: [
                    'Inledande fas med robotar, cyber och information följd av blockadtryck.',
                    'Risk för regional eskalering som involverar USA:s, Japans och Filippinernas hållning.',
                    'Handels- och halvledarchock på flera biljoner dollar.',
                    'Energi-, logistik- och civil motståndskraftsstress inom Taiwan.',
                    'Allvarligt humanitärt tryck och risk för fördrivning.',
                ],
                pl: [
                    'Początkowa faza rakietowa, cybernetyczna i informacyjna, po której następuje presja blokady.',
                    'Ryzyko eskalacji regionalnej obejmujące postawę USA, Japonii i Filipin.',
                    'Szok handlowy i półprzewodnikowy wart wiele bilionów dolarów.',
                    'Napięcia energetyczne, logistyczne i odporności cywilnej wewnątrz Tajwanu.',
                    'Silna presja humanitarna i ryzyko przesiedleń.',
                ],
            ),
            'recommended_actions' => $this->tl(
                en: [
                    'Monitor PLA naval, coast guard and ADIZ activity separately from invasion headlines.',
                    'Track Taiwan civil-resilience drills, energy stocks and drone procurement.',
                    'Follow allied deterrence statements, sanctions coordination and evacuation planning.',
                    'Separate invasion risk from blockade, quarantine and coercive pressure scenarios.',
                ],
                it: [
                    'Monitorare attività navale, guardia costiera e ADIZ del PLA separandole dai titoli sull’invasione.',
                    'Seguire esercitazioni di resilienza civile, scorte energetiche e acquisti di droni di Taiwan.',
                    'Seguire dichiarazioni alleate di deterrenza, coordinamento sanzioni e piani di evacuazione.',
                    'Separare il rischio di invasione da blocco, quarantena e pressione coercitiva.',
                ],
                fr: [
                    'Surveiller séparément l’activité navale, des garde-côtes et ADIZ de l’APL par rapport aux titres sur l’invasion.',
                    'Suivre les exercices de résilience civile de Taïwan, les stocks d’énergie et les achats de drones.',
                    'Suivre les déclarations alliées de dissuasion, la coordination des sanctions et la planification d’évacuation.',
                    'Distinguer le risque d’invasion des scénarios de blocus, de quarantaine et de pression coercitive.',
                ],
                de: [
                    'Marine-, Küstenwachen- und ADIZ-Aktivität der PLA getrennt von Invasionsschlagzeilen beobachten.',
                    'Taiwans Übungen zur zivilen Resilienz, Energievorräte und Drohnenbeschaffung verfolgen.',
                    'Abschreckungserklärungen der Verbündeten, Sanktionskoordination und Evakuierungsplanung verfolgen.',
                    'Invasionsrisiko von Blockade-, Quarantäne- und coerciven Druckszenarien trennen.',
                ],
                es: [
                    'Monitorear la actividad naval, de guardia costera y ADIZ del EPL separada de los titulares sobre invasión.',
                    'Seguir los simulacros de resiliencia civil de Taiwán, las reservas energéticas y la compra de drones.',
                    'Seguir declaraciones aliadas de disuasión, coordinación de sanciones y planificación de evacuación.',
                    'Separar el riesgo de invasión de los escenarios de bloqueo, cuarentena y presión coercitiva.',
                ],
                nl: [
                    'Monitor PLA-marine-, kustwacht- en ADIZ-activiteit los van invasiekoppen.',
                    'Volg Taiwanese civiele weerbaarheidsoefeningen, energievoorraden en drone-aankopen.',
                    'Volg geallieerde afschrikkingsverklaringen, sanctiecoördinatie en evacuatieplanning.',
                    'Scheid invasierisico van blokkade-, quarantaine- en dwingende drukscenario’s.',
                ],
                sv: [
                    'Övervaka PLA:s marina, kustbevaknings- och ADIZ-aktivitet separat från invasionsrubriker.',
                    'Följ Taiwans övningar i civil motståndskraft, energilager och drönarinköp.',
                    'Följ allierade avskräckningsuttalanden, sanktionssamordning och evakueringsplanering.',
                    'Skilj invasionsrisk från blockad-, karantän- och tvångstrycksscenarier.',
                ],
                pl: [
                    'Monitorować aktywność morską PLA, straży przybrzeżnej i ADIZ oddzielnie od nagłówków o inwazji.',
                    'Śledzić ćwiczenia odporności cywilnej Tajwanu, zapasy energii i zakupy dronów.',
                    'Śledzić deklaracje odstraszania sojuszników, koordynację sankcji i planowanie ewakuacji.',
                    'Oddzielać ryzyko inwazji od scenariuszy blokady, kwarantanny i presji przymusowej.',
                ],
            ),
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
                    'title' => $this->t(
                        en: 'Risk window curve',
                        it: 'Curva della finestra di rischio',
                        fr: 'Courbe de la fenêtre de risque',
                        de: 'Kurve des Risikofensters',
                        es: 'Curva de la ventana de riesgo',
                        nl: 'Curve van het risicovenster',
                        sv: 'Kurva för riskfönstret',
                        pl: 'Krzywa okna ryzyka',
                    ),
                    'description' => $this->t(
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
                'title' => $this->t(
                    en: 'Deterrence holds',
                    it: 'La deterrenza regge',
                    fr: 'La dissuasion tient',
                    de: 'Die Abschreckung hält',
                    es: 'La disuasión se mantiene',
                    nl: 'Afschrikking houdt stand',
                    sv: 'Avskräckningen håller',
                    pl: 'Odstraszanie działa',
                ),
                'summary' => $this->t(
                    en: 'Coercion remains below full invasion; a major crisis is avoided while grey-zone pressure persists.',
                    it: 'La coercizione resta sotto la soglia dell’invasione; la crisi maggiore viene evitata mentre continua la pressione grey-zone.',
                    fr: 'La coercition reste sous le seuil d’une invasion complète ; une crise majeure est évitée tandis que la pression de zone grise persiste.',
                    de: 'Zwang bleibt unterhalb einer vollständigen Invasion; eine große Krise wird vermieden, während der Grauzonendruck anhält.',
                    es: 'La coerción permanece por debajo de una invasión total; se evita una crisis mayor mientras persiste la presión de zona gris.',
                    nl: 'Dwang blijft onder de drempel van een volledige invasie; een grote crisis wordt vermeden terwijl grey-zone-druk aanhoudt.',
                    sv: 'Tvångsåtgärder förblir under tröskeln för full invasion; en större kris undviks medan gråzonstrycket består.',
                    pl: 'Presja pozostaje poniżej progu pełnej inwazji; większy kryzys zostaje uniknięty, a nacisk szarej strefy trwa.',
                ),
                'confidence_score' => 54,
                'probability_score' => 12,
                'trend' => 'stable',
                'methodology' => $methodology,
                'sort_order' => 1,
            ],
            [
                'type' => ProjectionType::Neutral,
                'target_date' => CarbonImmutable::parse('2027-10-01 00:00:00', 'UTC'),
                'title' => $this->t(
                    en: 'Baseline risk window',
                    it: 'Finestra base di rischio',
                    fr: 'Fenêtre de risque de référence',
                    de: 'Basis-Risikofenster',
                    es: 'Ventana base de riesgo',
                    nl: 'Basisrisicovenster',
                    sv: 'Grundläggande riskfönster',
                    pl: 'Bazowe okno ryzyka',
                ),
                'summary' => $this->t(
                    en: 'Full amphibious invasion remains lower-probability than blockade or quarantine, but 2027 readiness and grey-zone escalation make the risk non-negligible.',
                    it: 'L’invasione anfibia completa resta meno probabile di blocco o quarantena, ma prontezza 2027 ed escalation grey-zone rendono il rischio non trascurabile.',
                    fr: 'L’invasion amphibie complète reste moins probable qu’un blocus ou une quarantaine, mais la préparation 2027 et l’escalade de zone grise rendent le risque non négligeable.',
                    de: 'Eine vollständige amphibische Invasion bleibt weniger wahrscheinlich als Blockade oder Quarantäne, doch Bereitschaft 2027 und Grauzonen-Eskalation machen das Risiko nicht vernachlässigbar.',
                    es: 'La invasión anfibia completa sigue siendo menos probable que un bloqueo o una cuarentena, pero la preparación de 2027 y la escalada de zona gris hacen que el riesgo no sea despreciable.',
                    nl: 'Een volledige amfibische invasie blijft minder waarschijnlijk dan blokkade of quarantaine, maar gereedheid in 2027 en grey-zone-escalatie maken het risico niet verwaarloosbaar.',
                    sv: 'En full amfibisk invasion är fortfarande mindre sannolik än blockad eller karantän, men beredskap 2027 och gråzonseskalering gör risken betydande.',
                    pl: 'Pełna inwazja amfibijna pozostaje mniej prawdopodobna niż blokada lub kwarantanna, ale gotowość w 2027 roku i eskalacja szarej strefy czynią ryzyko istotnym.',
                ),
                'confidence_score' => 62,
                'probability_score' => 18,
                'trend' => 'rising',
                'methodology' => $methodology,
                'sort_order' => 2,
            ],
            [
                'type' => ProjectionType::Pessimistic,
                'target_date' => CarbonImmutable::parse('2027-03-15 00:00:00', 'UTC'),
                'title' => $this->t(
                    en: 'Accelerated crisis',
                    it: 'Crisi accelerata',
                    fr: 'Crise accélérée',
                    de: 'Beschleunigte Krise',
                    es: 'Crisis acelerada',
                    nl: 'Versnelde crisis',
                    sv: 'Accelererad kris',
                    pl: 'Przyspieszony kryzys',
                ),
                'summary' => $this->t(
                    en: 'Missile and cyber opening moves, blockade pressure and coercive operations compress the crisis window; amphibious risk rises if deterrence and civil logistics fail.',
                    it: 'Apertura missilistica e cyber, pressione di blocco e operazioni coercitive comprimono la finestra di crisi; il rischio anfibio cresce se deterrenza e logistica civile falliscono.',
                    fr: 'Les ouvertures missile et cyber, la pression de blocus et les opérations coercitives compressent la fenêtre de crise ; le risque amphibie augmente si la dissuasion et la logistique civile échouent.',
                    de: 'Raketen- und Cyberauftakte, Blockadedruck und Zwangsoperationen verdichten das Krisenfenster; das amphibische Risiko steigt, wenn Abschreckung und zivile Logistik versagen.',
                    es: 'Movimientos iniciales de misiles y ciber, presión de bloqueo y operaciones coercitivas comprimen la ventana de crisis; el riesgo anfibio aumenta si fallan la disuasión y la logística civil.',
                    nl: 'Raketten- en cyberopeningen, blokkadedruk en dwingende operaties verkorten het crisisvenster; amfibisch risico stijgt als afschrikking en civiele logistiek falen.',
                    sv: 'Inledande robot- och cyberdrag, blockadtryck och tvångsoperationer komprimerar krisfönstret; amfibierisken ökar om avskräckning och civil logistik misslyckas.',
                    pl: 'Początkowe działania rakietowe i cybernetyczne, presja blokady oraz operacje przymusowe skracają okno kryzysu; ryzyko amfibijne rośnie, jeśli odstraszanie i logistyka cywilna zawiodą.',
                ),
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
                'title' => $this->t(
                    en: 'Key indicators',
                    it: 'Indicatori chiave',
                    fr: 'Indicateurs clés',
                    de: 'Schlüsselindikatoren',
                    es: 'Indicadores clave',
                    nl: 'Kernindicatoren',
                    sv: 'Nyckelindikatorer',
                    pl: 'Kluczowe wskaźniki',
                ),
                'description' => $this->t(
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
            $this->lineVisualization(
                'pla_pressure_trend',
                $this->t(
                    en: 'PLA pressure trend',
                    it: 'Trend pressione PLA',
                    fr: 'Tendance de pression de l’APL',
                    de: 'Trend des PLA-Drucks',
                    es: 'Tendencia de presión del EPL',
                    nl: 'Trend in PLA-druk',
                    sv: 'Trend för PLA-tryck',
                    pl: 'Trend presji PLA',
                ),
                $this->t(
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
                [$this->sources()['dod']],
                2,
            ),
            $this->lineVisualization(
                'naval_pressure_2026',
                $this->t(
                    en: 'Naval pressure 2026',
                    it: 'Pressione navale 2026',
                    fr: 'Pression navale 2026',
                    de: 'Maritimer Druck 2026',
                    es: 'Presión naval 2026',
                    nl: 'Marinedruk 2026',
                    sv: 'Marint tryck 2026',
                    pl: 'Presja morska 2026',
                ),
                $this->t(
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
                [$this->sources()['reuters_naval']],
                3,
            ),
            $this->lineVisualization(
                'economic_exposure',
                $this->t(
                    en: 'Economic exposure',
                    it: 'Esposizione economica',
                    fr: 'Exposition économique',
                    de: 'Wirtschaftliche Exponierung',
                    es: 'Exposición económica',
                    nl: 'Economische blootstelling',
                    sv: 'Ekonomisk exponering',
                    pl: 'Ekspozycja gospodarcza',
                ),
                $this->t(
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
                [$this->sources()['csis_trade'], $this->sources()['rhodium_disruptions']],
                4,
            ),
            $this->lineVisualization(
                'scenario_gdp_shock',
                $this->t(
                    en: 'Scenario GDP shock',
                    it: 'Shock PIL per scenario',
                    fr: 'Choc de PIB par scénario',
                    de: 'BIP-Schock nach Szenario',
                    es: 'Choque del PIB por escenario',
                    nl: 'BBP-schok per scenario',
                    sv: 'BNP-chock per scenario',
                    pl: 'Szok PKB według scenariusza',
                ),
                $this->t(
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
                [$this->sources()['rhodium_disruptions']],
                5,
            ),
            $this->lineVisualization(
                'energy_resilience',
                $this->t(
                    en: 'Energy resilience',
                    it: 'Resilienza energetica',
                    fr: 'Résilience énergétique',
                    de: 'Energieresilienz',
                    es: 'Resiliencia energética',
                    nl: 'Energieveerkracht',
                    sv: 'Energiresiliens',
                    pl: 'Odporność energetyczna',
                ),
                $this->t(
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
                [$this->sources()['energy_resilience']],
                6,
            ),
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

    /** @return array{en: string, it: string, fr: string, de: string, es: string, nl: string, sv: string, pl: string} */
    private function t(string $en, string $it, string $fr, string $de, string $es, string $nl, string $sv, string $pl): array
    {
        return compact('en', 'it', 'fr', 'de', 'es', 'nl', 'sv', 'pl');
    }

    /**
     * @param array<int, string> $en
     * @param array<int, string> $it
     * @param array<int, string> $fr
     * @param array<int, string> $de
     * @param array<int, string> $es
     * @param array<int, string> $nl
     * @param array<int, string> $sv
     * @param array<int, string> $pl
     * @return array{en: array<int, string>, it: array<int, string>, fr: array<int, string>, de: array<int, string>, es: array<int, string>, nl: array<int, string>, sv: array<int, string>, pl: array<int, string>}
     */
    private function tl(array $en, array $it, array $fr, array $de, array $es, array $nl, array $sv, array $pl): array
    {
        return compact('en', 'it', 'fr', 'de', 'es', 'nl', 'sv', 'pl');
    }
}
