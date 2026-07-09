<?php

declare(strict_types=1);

use App\Enums\CountdownSeverity;
use App\Enums\CountdownStatus;
use Carbon\CarbonImmutable;

$shared = require __DIR__.'/../_shared.php';

return new class($shared)
{
    public function __construct(private object $shared) {}

    /** @return array<string, mixed> */
    public function countdown(): array
    {
        return [
            'slug' => 'taiwan-invasion',
            'title' => $this->shared->t(
                en: 'Taiwan Invasion',
                it: 'Invasione di Taiwan',
                fr: 'Invasion de Taïwan',
                de: 'Invasion Taiwans',
                es: 'Invasión de Taiwán',
                nl: 'Invasie van Taiwan',
                sv: 'Invasion av Taiwan',
                pl: 'Inwazja na Tajwan',
            ),
            'summary' => $this->shared->t(
                en: 'China–Taiwan conflict risk and the 2027 readiness window',
                it: 'Rischio di conflitto Cina–Taiwan e finestra di prontezza 2027',
                fr: 'Risque de conflit Chine–Taïwan et fenêtre de préparation 2027',
                de: 'Risiko eines China–Taiwan-Konflikts und Bereitschaftsfenster 2027',
                es: 'Riesgo de conflicto China–Taiwán y ventana de preparación de 2027',
                nl: 'Risico op conflict tussen China en Taiwan en het gereedheidsvenster van 2027',
                sv: 'Risk för konflikt mellan Kina och Taiwan och beredskapsfönstret 2027',
                pl: 'Ryzyko konfliktu Chiny–Tajwan i okno gotowości w 2027 roku',
            ),
            'description' => $this->shared->t(
                en: 'A large-scale amphibious invasion of Taiwan is plausible but remains a lower-probability path than blockade, quarantine, cyber pressure, sabotage and other coercive grey-zone operations in the near term. The countdown uses public-source indicators to frame a 2027 readiness window, not a certain event date.',
                it: 'Una invasione anfibia su larga scala di Taiwan è plausibile, ma nel breve termine resta meno probabile di blocco, quarantena, pressione cyber, sabotaggio e altre operazioni coercitive grey-zone. Il countdown usa indicatori da fonti pubbliche per inquadrare una finestra di prontezza 2027, non una data certa.',
                fr: 'Une invasion amphibie de grande ampleur de Taïwan est plausible, mais reste à court terme moins probable qu’un blocus, une quarantaine, une pression cyber, du sabotage et d’autres opérations coercitives de zone grise. Le compte à rebours utilise des indicateurs de sources publiques pour cadrer une fenêtre de préparation en 2027, pas une date certaine d’événement.',
                de: 'Eine groß angelegte amphibische Invasion Taiwans ist plausibel, bleibt kurzfristig aber weniger wahrscheinlich als Blockade, Quarantäne, Cyberdruck, Sabotage und andere coercive Grauzonen-Operationen. Der Countdown nutzt öffentlich zugängliche Indikatoren, um ein Bereitschaftsfenster 2027 einzuordnen, nicht ein sicheres Ereignisdatum.',
                es: 'Una invasión anfibia a gran escala de Taiwán es plausible, pero a corto plazo sigue siendo menos probable que un bloqueo, una cuarentena, presión cibernética, sabotaje y otras operaciones coercitivas de zona gris. La cuenta atrás usa indicadores de fuentes públicas para enmarcar una ventana de preparación en 2027, no una fecha segura de evento.',
                nl: 'Een grootschalige amfibische invasie van Taiwan is plausibel, maar blijft op korte termijn minder waarschijnlijk dan blokkade, quarantaine, cyberdruk, sabotage en andere dwingende grey-zone-operaties. De countdown gebruikt indicatoren uit openbare bronnen om een gereedheidsvenster in 2027 te kaderen, niet een zekere gebeurtenisdatum.',
                sv: 'En storskalig amfibisk invasion av Taiwan är möjlig, men är på kort sikt fortfarande mindre sannolik än blockad, karantän, cybertryck, sabotage och andra tvingande gråzonsoperationer. Nedräkningen använder indikatorer från öppna källor för att rama in ett beredskapsfönster 2027, inte ett säkert händelsedatum.',
                pl: 'Zakrojona na dużą skalę amfibijna inwazja na Tajwan jest możliwa, ale w krótkim terminie pozostaje mniej prawdopodobna niż blokada, kwarantanna, presja cybernetyczna, sabotaż i inne przymusowe działania w szarej strefie. Licznik wykorzystuje wskaźniki ze źródeł publicznych, aby opisać okno gotowości w 2027 roku, a nie pewną datę wydarzenia.',
            ),
            'causes' => $this->shared->tl(
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
            'consequences' => $this->shared->tl(
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
            'recommended_actions' => $this->shared->tl(
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
            'severity' => CountdownSeverity::Critical,
            'status' => CountdownStatus::Active,
            'target_date' => CarbonImmutable::parse('2027-10-01 00:00:00', 'UTC'),
            'image_path' => 'images/doomsday/taiwan_invasion.png',
            'sort_order' => 1,
            'is_published' => true,
        ];
    }
};
