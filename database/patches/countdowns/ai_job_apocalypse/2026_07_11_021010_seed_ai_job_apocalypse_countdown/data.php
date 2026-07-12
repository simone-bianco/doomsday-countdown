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
            'slug' => 'ai-job-apocalypse',
            'title' => $this->shared->t(
                en: 'AI Job Apocalypse',
                it: 'Apocalisse dei lavori nell’era dell’IA',
                fr: 'Apocalypse de l’emploi à l’ère de l’IA',
                de: 'KI-Job-Apokalypse',
                es: 'Apocalipsis laboral por la IA',
                nl: 'AI-banenapocalyps',
                sv: 'AI-jobbapokalypsen',
                pl: 'Apokalipsa miejsc pracy przez AI',
            ),
            'summary' => $this->shared->t(
                en: 'The active chain selects the first applicable future checkpoint: Pessimistic on 2 December 2027, Neutral on 31 December 2030 for institutional adoption and skills, then Optimistic on 31 December 2035 for workforce adjustment.',
                it: 'La catena attiva seleziona il primo checkpoint futuro applicabile: Pessimistic il 2 dicembre 2027, Neutral il 31 dicembre 2030 per adozione istituzionale e competenze, poi Optimistic il 31 dicembre 2035 per l’adattamento della forza lavoro.',
                fr: 'La chaîne active sélectionne le premier jalon futur applicable: Pessimistic le 2 décembre 2027, Neutral le 31 décembre 2030 pour l’adoption institutionnelle et les compétences, puis Optimistic le 31 décembre 2035 pour l’ajustement de la main-d’œuvre.',
                de: 'Die aktive Kette wählt den ersten anwendbaren zukünftigen Prüfpunkt: Pessimistic am 2. Dezember 2027, Neutral am 31. Dezember 2030 für institutionelle Adoption und Qualifikationen, danach Optimistic am 31. Dezember 2035 für die Anpassung der Arbeitskräfte.',
                es: 'La cadena activa selecciona el primer punto futuro aplicable: Pessimistic el 2 de diciembre de 2027, Neutral el 31 de diciembre de 2030 para adopción institucional y competencias, y después Optimistic el 31 de diciembre de 2035 para el ajuste de la fuerza laboral.',
                nl: 'De actieve keten kiest het eerste toepasselijke toekomstige ijkpunt: Pessimistic op 2 december 2027, Neutral op 31 december 2030 voor institutionele adoptie en vaardigheden, daarna Optimistic op 31 december 2035 voor aanpassing van de beroepsbevolking.',
                sv: 'Den aktiva kedjan väljer den första tillämpliga framtida kontrollpunkten: Pessimistic den 2 december 2027, Neutral den 31 december 2030 för institutionell adoption och kompetens, därefter Optimistic den 31 december 2035 för arbetskraftens anpassning.',
                pl: 'Aktywny łańcuch wybiera pierwszy właściwy przyszły punkt: Pessimistic 2 grudnia 2027 r., Neutral 31 grudnia 2030 r. dla instytucjonalnych wdrożeń i umiejętności, a następnie Optimistic 31 grudnia 2035 r. dla dostosowania siły roboczej.',
            ),
            'description' => $this->shared->t(
                en: 'The title is deliberately dramatic, but the timer does not predict a date of mass unemployment. It follows the first applicable future checkpoint in the Pessimistic → Neutral → Optimistic chain: at the current assessment, 2 December 2027 is the active Pessimistic regulatory checkpoint; 31 December 2030 is the Neutral institutional checkpoint for EU Digital Decade adoption and skills targets and OECD AI scenarios; 31 December 2035 is the Optimistic skills-forecast and workforce-adjustment horizon. Every scenario distinguishes occupational exposure, observed automation, augmentation, displacement and job creation.',
                it: 'Il titolo è volutamente drammatico, ma il timer non predice una data di disoccupazione di massa. Segue il primo checkpoint futuro applicabile nella catena Pessimistic → Neutral → Optimistic: alla valutazione corrente, il 2 dicembre 2027 è il checkpoint regolatorio Pessimistic attivo; il 31 dicembre 2030 è il checkpoint istituzionale Neutral per i target UE del Decennio digitale su adozione e competenze e per gli scenari IA OECD; il 31 dicembre 2035 è l’orizzonte Optimistic di previsione delle competenze e adattamento della forza lavoro. Ogni scenario distingue esposizione professionale, automazione osservata, potenziamento, spiazzamento e creazione di lavoro.',
                fr: 'Le titre est volontairement dramatique, mais le minuteur ne prédit pas une date de chômage massif. Il suit le premier jalon futur applicable de la chaîne Pessimistic → Neutral → Optimistic: dans l’évaluation actuelle, le 2 décembre 2027 est le jalon réglementaire Pessimistic actif; le 31 décembre 2030 est le jalon institutionnel Neutral pour les objectifs d’adoption et de compétences de la Décennie numérique de l’UE et les scénarios d’IA de l’OCDE; le 31 décembre 2035 est l’horizon Optimistic de prévision des compétences et d’ajustement de la main-d’œuvre. Chaque scénario distingue exposition professionnelle, automatisation observée, augmentation, déplacement et création d’emplois.',
                de: 'Der Titel ist bewusst dramatisch, doch der Timer prognostiziert kein Datum für Massenarbeitslosigkeit. Er folgt dem ersten anwendbaren zukünftigen Prüfpunkt der Kette Pessimistic → Neutral → Optimistic: In der aktuellen Bewertung ist der 2. Dezember 2027 der aktive regulatorische Pessimistic-Prüfpunkt; der 31. Dezember 2030 ist der institutionelle Neutral-Prüfpunkt für die Adoptions- und Qualifikationsziele der Digitalen Dekade der EU und die KI-Szenarien der OECD; der 31. Dezember 2035 ist der Optimistic-Horizont für Qualifikationsprognosen und Arbeitskräfteanpassung. Jedes Szenario unterscheidet berufliche Exposition, beobachtete Automatisierung, Unterstützung, Verdrängung und Jobschaffung.',
                es: 'El título es deliberadamente dramático, pero el temporizador no predice una fecha de desempleo masivo. Sigue el primer punto futuro aplicable de la cadena Pessimistic → Neutral → Optimistic: en la evaluación actual, el 2 de diciembre de 2027 es el punto regulatorio Pessimistic activo; el 31 de diciembre de 2030 es el punto institucional Neutral para los objetivos de adopción y competencias de la Década Digital de la UE y los escenarios de IA de la OCDE; el 31 de diciembre de 2035 es el horizonte Optimistic de previsión de competencias y ajuste de la fuerza laboral. Cada escenario distingue exposición ocupacional, automatización observada, aumento, desplazamiento y creación de empleo.',
                nl: 'De titel is bewust dramatisch, maar de timer voorspelt geen datum van massawerkloosheid. Hij volgt het eerste toepasselijke toekomstige ijkpunt in de keten Pessimistic → Neutral → Optimistic: in de huidige beoordeling is 2 december 2027 het actieve regelgevende Pessimistic-ijkpunt; 31 december 2030 is het institutionele Neutral-ijkpunt voor de EU-doelen van de Digitale Decade rond adoptie en vaardigheden en de AI-scenario’s van de OECD; 31 december 2035 is de Optimistic-horizon voor vaardighedenprognoses en aanpassing van de beroepsbevolking. Elk scenario onderscheidt beroepsblootstelling, waargenomen automatisering, ondersteuning, verdringing en baancreatie.',
                sv: 'Titeln är medvetet dramatisk, men timern förutsäger inget datum för massarbetslöshet. Den följer den första tillämpliga framtida kontrollpunkten i kedjan Pessimistic → Neutral → Optimistic: i den aktuella bedömningen är den 2 december 2027 den aktiva regulatoriska Pessimistic-punkten; den 31 december 2030 är den institutionella Neutral-punkten för EU:s mål om adoption och kompetens i det digitala decenniet och OECD:s AI-scenarier; den 31 december 2035 är Optimistic-horisonten för kompetensprognoser och arbetskraftens anpassning. Varje scenario skiljer mellan yrkesexponering, observerad automation, förstärkning, undanträngning och jobbskapande.',
                pl: 'Tytuł jest celowo dramatyczny, lecz licznik nie przewiduje daty masowego bezrobocia. Podąża za pierwszym właściwym przyszłym punktem w łańcuchu Pessimistic → Neutral → Optimistic: w obecnej ocenie 2 grudnia 2027 r. jest aktywnym regulacyjnym punktem Pessimistic; 31 grudnia 2030 r. jest instytucjonalnym punktem Neutral dla celów unijnej Cyfrowej Dekady dotyczących wdrożeń i umiejętności oraz scenariuszy AI OECD; 31 grudnia 2035 r. jest horyzontem Optimistic dla prognoz umiejętności i dostosowania siły roboczej. Każdy scenariusz rozróżnia ekspozycję zawodową, obserwowaną automatyzację, wspomaganie, wypieranie i tworzenie pracy.',
            ),
            'causes' => $this->shared->tl(
                en: [
                    'Rapid improvement and diffusion of generative-AI systems across cognitive tasks.',
                    'Enterprise adoption growing faster than many training and bargaining systems adapt.',
                    'Uneven exposure by occupation, income group, gender and country.',
                    'Business incentives to redesign tasks, workflows and staffing around automation and augmentation.',
                ],
                it: [
                    'Rapido miglioramento e diffusione dell’IA generativa nei compiti cognitivi.',
                    'Adozione nelle imprese più rapida dell’adattamento di formazione e contrattazione.',
                    'Esposizione diseguale per professione, reddito, genere e paese.',
                    'Incentivi aziendali a ridisegnare compiti, flussi e organici attorno ad automazione e potenziamento.',
                ],
                fr: [
                    'Amélioration et diffusion rapides de l’IA générative dans les tâches cognitives.',
                    'Adoption par les entreprises plus rapide que l’adaptation de la formation et du dialogue social.',
                    'Exposition inégale selon profession, revenu, genre et pays.',
                    'Incitations à redessiner tâches, processus et effectifs autour de l’automatisation et de l’augmentation.',
                ],
                de: [
                    'Schnelle Verbesserung und Verbreitung generativer KI bei kognitiven Aufgaben.',
                    'Unternehmensadoption wächst schneller als Ausbildung und Mitbestimmung reagieren.',
                    'Ungleiche Exposition nach Beruf, Einkommen, Geschlecht und Land.',
                    'Anreize, Aufgaben, Abläufe und Personal um Automatisierung und Unterstützung neu zu gestalten.',
                ],
                es: [
                    'Mejora y difusión rápidas de la IA generativa en tareas cognitivas.',
                    'Adopción empresarial más rápida que la adaptación de formación y negociación.',
                    'Exposición desigual por ocupación, ingresos, género y país.',
                    'Incentivos para rediseñar tareas, procesos y plantillas alrededor de automatización y aumento.',
                ],
                nl: [
                    'Snelle verbetering en verspreiding van generatieve AI in cognitieve taken.',
                    'Bedrijfsadoptie groeit sneller dan opleiding en overleg zich aanpassen.',
                    'Ongelijke blootstelling naar beroep, inkomen, gender en land.',
                    'Prikkels om taken, processen en personeelsinzet rond automatisering en ondersteuning te herontwerpen.',
                ],
                sv: [
                    'Snabb förbättring och spridning av generativ AI i kognitiva arbetsuppgifter.',
                    'Företagsadoption växer snabbare än utbildning och partsdialog hinner anpassas.',
                    'Ojämn exponering mellan yrken, inkomster, kön och länder.',
                    'Incitament att omforma uppgifter, arbetsflöden och bemanning kring automatisering och förstärkning.',
                ],
                pl: [
                    'Szybki rozwój i rozpowszechnianie generatywnej AI w zadaniach poznawczych.',
                    'Wdrożenia w firmach rosną szybciej niż adaptacja szkoleń i dialogu społecznego.',
                    'Nierówna ekspozycja według zawodu, dochodu, płci i kraju.',
                    'Bodźce do przeprojektowania zadań, procesów i zatrudnienia wokół automatyzacji i wspomagania.',
                ],
            ),
            'consequences' => $this->shared->tl(
                en: [
                    'Many occupations may be transformed without being eliminated.',
                    'Displacement can concentrate in clerical and routine-intensive roles even while aggregate employment grows.',
                    'Productivity gains may create tasks and jobs, but benefits can be distributed unevenly.',
                    'Training gaps and weak worker voice can turn exposure into insecurity or wage pressure.',
                    'Poorly governed systems can amplify discrimination, surveillance and occupational safety risks.',
                ],
                it: [
                    'Molte professioni possono trasformarsi senza scomparire.',
                    'Lo spiazzamento può concentrarsi nei ruoli impiegatizi e routinari anche con occupazione aggregata in crescita.',
                    'La produttività può creare compiti e posti, ma i benefici possono distribuirsi in modo diseguale.',
                    'Gap formativi e debole voce dei lavoratori possono trasformare l’esposizione in insicurezza o pressione salariale.',
                    'Sistemi mal governati possono amplificare discriminazione, sorveglianza e rischi per la sicurezza.',
                ],
                fr: [
                    'De nombreuses professions peuvent être transformées sans disparaître.',
                    'Les déplacements peuvent se concentrer dans les emplois administratifs et routiniers malgré une hausse globale de l’emploi.',
                    'Les gains de productivité peuvent créer tâches et emplois, avec des bénéfices inégalement répartis.',
                    'Les écarts de formation et une faible voix des travailleurs peuvent convertir l’exposition en insécurité ou pression salariale.',
                    'Une mauvaise gouvernance peut amplifier discrimination, surveillance et risques professionnels.',
                ],
                de: [
                    'Viele Berufe können verändert werden, ohne zu verschwinden.',
                    'Verdrängung kann sich auf Büro- und Routinetätigkeiten konzentrieren, selbst wenn die Gesamtbeschäftigung wächst.',
                    'Produktivitätsgewinne können Aufgaben und Jobs schaffen, aber ungleich verteilt sein.',
                    'Qualifikationslücken und geringe Arbeitnehmerbeteiligung können Exposition in Unsicherheit oder Lohndruck verwandeln.',
                    'Schlecht gesteuerte Systeme können Diskriminierung, Überwachung und Arbeitsschutzrisiken verstärken.',
                ],
                es: [
                    'Muchas ocupaciones pueden transformarse sin desaparecer.',
                    'El desplazamiento puede concentrarse en tareas administrativas y rutinarias aunque crezca el empleo total.',
                    'La productividad puede crear tareas y puestos, pero repartir beneficios de forma desigual.',
                    'Brechas formativas y poca voz laboral pueden convertir exposición en inseguridad o presión salarial.',
                    'Una gobernanza deficiente puede ampliar discriminación, vigilancia y riesgos laborales.',
                ],
                nl: [
                    'Veel beroepen kunnen veranderen zonder te verdwijnen.',
                    'Verdringing kan zich concentreren in administratieve en routinematige functies terwijl totale werkgelegenheid groeit.',
                    'Productiviteitswinst kan taken en banen creëren, maar voordelen ongelijk verdelen.',
                    'Opleidingsachterstand en weinig werknemersstem kunnen blootstelling omzetten in onzekerheid of loondruk.',
                    'Slecht bestuurde systemen kunnen discriminatie, toezicht en veiligheidsrisico’s vergroten.',
                ],
                sv: [
                    'Många yrken kan förändras utan att försvinna.',
                    'Undanträngning kan koncentreras till administrativa och rutinintensiva roller även när total sysselsättning växer.',
                    'Produktivitetsvinster kan skapa uppgifter och jobb men fördela nyttan ojämnt.',
                    'Kompetensgap och svagt arbetstagarinflytande kan göra exponering till otrygghet eller lönetryck.',
                    'Dåligt styrda system kan förstärka diskriminering, övervakning och arbetsmiljörisker.',
                ],
                pl: [
                    'Wiele zawodów może się zmienić bez całkowitego zniknięcia.',
                    'Wypieranie może koncentrować się w pracy biurowej i rutynowej mimo wzrostu zatrudnienia ogółem.',
                    'Wzrost produktywności może tworzyć zadania i miejsca pracy, lecz nierówno rozdzielać korzyści.',
                    'Luki szkoleniowe i słaby głos pracowników mogą zamienić ekspozycję w niepewność lub presję płacową.',
                    'Źle zarządzane systemy mogą wzmacniać dyskryminację, nadzór i ryzyka BHP.',
                ],
            ),
            'recommended_actions' => $this->shared->tl(
                en: [
                    'Measure task exposure separately from observed automation and job losses.',
                    'Fund accessible lifelong learning before deployment gaps widen.',
                    'Give workers and social partners a role in system design, monitoring and redress.',
                    'Track job creation, job quality and distributional effects alongside productivity.',
                    'Apply risk, safety, transparency and anti-discrimination safeguards at work.',
                ],
                it: [
                    'Misurare l’esposizione dei compiti separatamente da automazione osservata e perdite di lavoro.',
                    'Finanziare apprendimento permanente accessibile prima che crescano i gap di adozione.',
                    'Coinvolgere lavoratori e parti sociali in progettazione, monitoraggio e ricorso.',
                    'Seguire creazione e qualità del lavoro ed effetti distributivi insieme alla produttività.',
                    'Applicare garanzie su rischio, sicurezza, trasparenza e non discriminazione.',
                ],
                fr: [
                    'Mesurer l’exposition des tâches séparément de l’automatisation et des pertes observées.',
                    'Financer l’apprentissage tout au long de la vie avant l’élargissement des écarts.',
                    'Associer travailleurs et partenaires sociaux à la conception, au suivi et aux recours.',
                    'Suivre création, qualité et répartition des emplois avec la productivité.',
                    'Appliquer des garanties de risque, sécurité, transparence et non-discrimination.',
                ],
                de: [
                    'Aufgabenexposition getrennt von beobachteter Automatisierung und Jobverlust messen.',
                    'Zugängliches lebenslanges Lernen finanzieren, bevor Umsetzungslücken wachsen.',
                    'Beschäftigte und Sozialpartner an Gestaltung, Kontrolle und Abhilfe beteiligen.',
                    'Jobschaffung, Qualität und Verteilung neben Produktivität verfolgen.',
                    'Risiko-, Sicherheits-, Transparenz- und Antidiskriminierungsregeln anwenden.',
                ],
                es: [
                    'Medir exposición de tareas por separado de automatización y pérdidas observadas.',
                    'Financiar aprendizaje permanente accesible antes de que aumenten las brechas.',
                    'Dar a trabajadores y agentes sociales un papel en diseño, seguimiento y reparación.',
                    'Seguir creación, calidad y distribución del empleo junto con productividad.',
                    'Aplicar salvaguardas de riesgo, seguridad, transparencia y no discriminación.',
                ],
                nl: [
                    'Meet taakblootstelling los van waargenomen automatisering en baanverlies.',
                    'Financier toegankelijk levenslang leren voordat implementatiekloven groeien.',
                    'Geef werknemers en sociale partners een rol in ontwerp, toezicht en herstel.',
                    'Volg baancreatie, kwaliteit en verdeling naast productiviteit.',
                    'Pas waarborgen toe voor risico, veiligheid, transparantie en non-discriminatie.',
                ],
                sv: [
                    'Mät uppgiftsexponering separat från observerad automatisering och jobbförluster.',
                    'Finansiera tillgängligt livslångt lärande innan genomförandegap växer.',
                    'Ge arbetstagare och parter en roll i utformning, uppföljning och rättelse.',
                    'Följ jobbskapande, kvalitet och fördelning tillsammans med produktivitet.',
                    'Tillämpa skydd för risk, säkerhet, transparens och icke-diskriminering.',
                ],
                pl: [
                    'Mierzyć ekspozycję zadań oddzielnie od obserwowanej automatyzacji i utraty pracy.',
                    'Finansować dostępne uczenie się przez całe życie, zanim wzrosną luki wdrożeniowe.',
                    'Zapewnić pracownikom i partnerom społecznym udział w projektowaniu, monitoringu i odwołaniach.',
                    'Śledzić tworzenie i jakość pracy oraz skutki dystrybucyjne obok produktywności.',
                    'Stosować zabezpieczenia ryzyka, bezpieczeństwa, przejrzystości i niedyskryminacji.',
                ],
            ),
            'severity' => CountdownSeverity::High,
            'status' => CountdownStatus::Active,
            'target_date' => CarbonImmutable::parse('2030-12-31 23:59:59', 'UTC'),
            'image_path' => 'images/doomsday/ai_job_apocalypse.png',
            'sort_order' => 3,
            'is_published' => true,
        ];
    }
};
