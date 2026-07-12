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
            'slug' => $this->shared::SLUG,
            'title' => $this->shared->t(
                en: 'Europe on the Brink: The War Countdown',
                it: 'Europa sull’orlo: il conto alla rovescia della guerra',
                fr: 'L’Europe au bord du gouffre : le compte à rebours de la guerre',
                de: 'Europa am Abgrund: Der Kriegs-Countdown',
                es: 'Europa al límite: la cuenta atrás de la guerra',
                nl: 'Europa op de rand: de oorlogsaftelling',
                sv: 'Europa på randen: nedräkningen till krig',
                pl: 'Europa na krawędzi: odliczanie do wojny',
            ),
            'summary' => $this->shared->t(
                en: 'A conditional post-winter 2027–2035 security window, with the main timer set to the neutral end-2030 readiness threshold—not a prediction of war.',
                it: 'Una finestra di sicurezza condizionale dal dopo-inverno 2027 al 2035, con il timer principale sulla soglia neutrale di prontezza a fine 2030, non una previsione di guerra.',
                fr: 'Une fenêtre de sécurité conditionnelle de l’après-hiver 2027 à 2035, avec le minuteur principal fixé au seuil neutre de préparation fin 2030, et non à une prédiction de guerre.',
                de: 'Ein bedingtes Sicherheitsfenster vom Zeitraum nach dem Winter 2027 bis 2035; der Haupttimer liegt auf der neutralen Bereitschaftsschwelle Ende 2030, nicht auf einer Kriegsprognose.',
                es: 'Una ventana de seguridad condicional desde después del invierno de 2027 hasta 2035, con el temporizador principal en el umbral neutral de preparación de finales de 2030, no una predicción de guerra.',
                nl: 'Een voorwaardelijk veiligheidsvenster van na de winter van 2027 tot 2035, met de hoofdtimer op de neutrale paraatheidsdrempel eind 2030, niet op een oorlogsvoorspelling.',
                sv: 'Ett villkorat säkerhetsfönster från efter vintern 2027 till 2035, med huvudtimern vid den neutrala beredskapströskeln i slutet av 2030, inte en krigsprognos.',
                pl: 'Warunkowe okno bezpieczeństwa od okresu po zimie 2027 do 2035 roku, z głównym licznikiem ustawionym na neutralny próg gotowości pod koniec 2030 roku, a nie na prognozę wojny.',
            ),
            'description' => $this->shared->t(
                en: 'The timer uses end-2030 because current NATO assessments place a possible Russian readiness window around 2029 or the end of the decade, while the EU roadmap requires critical contracts by end-2028 and SAFE deliveries by end-2030. The pessimistic scenario uses 31 March 2027 as a user-selected post-winter editorial checkpoint; no cited source identifies that day. The optimistic scenario moves to end-2035 if EU readiness and NATO investment mature. All dates are conditional editorial deadlines, not estimates of intent or certain conflict dates.',
                it: 'Il timer usa fine 2030 perché le valutazioni NATO attuali collocano una possibile finestra di prontezza russa intorno al 2029 o alla fine del decennio, mentre la roadmap UE richiede contratti critici entro fine 2028 e consegne SAFE entro fine 2030. Lo scenario pessimistico usa il 31 marzo 2027 come checkpoint editoriale post-inverno scelto dall’utente; nessuna fonte citata identifica quel giorno. Lo scenario ottimistico arriva a fine 2035 se maturano prontezza UE e investimenti NATO. Tutte le date sono scadenze editoriali condizionali, non stime di intenzione o date certe di conflitto.',
                fr: 'Le minuteur retient fin 2030 car les évaluations actuelles de l’OTAN situent une possible fenêtre de préparation russe autour de 2029 ou de la fin de la décennie, tandis que la feuille de route de l’UE exige les contrats critiques fin 2028 et les livraisons SAFE fin 2030. Le scénario pessimiste utilise le 31 mars 2027 comme jalon éditorial d’après-hiver choisi par l’utilisateur; aucune source citée n’identifie ce jour. Le scénario optimiste va jusqu’à fin 2035 si la préparation de l’UE et l’investissement de l’OTAN arrivent à maturité. Toutes les dates sont des échéances éditoriales conditionnelles, pas des estimations d’intention ni des dates certaines de conflit.',
                de: 'Der Timer nutzt Ende 2030, weil aktuelle NATO-Bewertungen ein mögliches russisches Bereitschaftsfenster um 2029 beziehungsweise bis zum Ende des Jahrzehnts sehen, während der EU-Fahrplan kritische Verträge bis Ende 2028 und SAFE-Lieferungen bis Ende 2030 verlangt. Das pessimistische Szenario nutzt den 31. März 2027 als vom Nutzer gewählten redaktionellen Zeitpunkt nach dem Winter; keine zitierte Quelle nennt diesen Tag. Das optimistische Szenario reicht bis Ende 2035, wenn EU-Bereitschaft und NATO-Investitionen ausreifen. Alle Daten sind bedingte redaktionelle Fristen, keine Absichtsprognosen oder sicheren Kriegsdaten.',
                es: 'El temporizador usa finales de 2030 porque las evaluaciones actuales de la OTAN sitúan una posible ventana de preparación rusa alrededor de 2029 o del final de la década, mientras que la hoja de ruta de la UE exige contratos críticos a finales de 2028 y entregas SAFE a finales de 2030. El escenario pesimista usa el 31 de marzo de 2027 como hito editorial posterior al invierno elegido por el usuario; ninguna fuente citada identifica ese día. El escenario optimista llega a finales de 2035 si maduran la preparación de la UE y la inversión de la OTAN. Todas las fechas son plazos editoriales condicionales, no estimaciones de intención ni fechas ciertas de conflicto.',
                nl: 'De timer gebruikt eind 2030 omdat actuele NAVO-beoordelingen een mogelijk Russisch paraatheidsvenster rond 2029 of het einde van het decennium plaatsen, terwijl de EU-routekaart kritieke contracten tegen eind 2028 en SAFE-leveringen tegen eind 2030 vereist. Het pessimistische scenario gebruikt 31 maart 2027 als een door de gebruiker gekozen redactioneel ijkpunt na de winter; geen aangehaalde bron noemt die dag. Het optimistische scenario loopt tot eind 2035 als EU-paraatheid en NAVO-investeringen rijpen. Alle data zijn voorwaardelijke redactionele termijnen, geen inschattingen van intentie of zekere oorlogsdata.',
                sv: 'Timern använder slutet av 2030 eftersom aktuella Nato-bedömningar placerar ett möjligt ryskt beredskapsfönster kring 2029 eller årtiondets slut, medan EU:s färdplan kräver kritiska kontrakt senast 2028 och SAFE-leveranser senast 2030. Det pessimistiska scenariot använder den 31 mars 2027 som en användarvald redaktionell kontrollpunkt efter vintern; ingen citerad källa anger den dagen. Det optimistiska scenariot sträcker sig till slutet av 2035 om EU:s beredskap och Natos investeringar mognar. Alla datum är villkorade redaktionella tidsgränser, inte bedömningar av avsikt eller säkra krigsdatum.',
                pl: 'Licznik wskazuje koniec 2030 roku, ponieważ obecne oceny NATO umieszczają możliwe rosyjskie okno gotowości około 2029 roku lub końca dekady, podczas gdy mapa drogowa UE wymaga kluczowych kontraktów do końca 2028 roku i dostaw SAFE do końca 2030 roku. Scenariusz pesymistyczny używa 31 marca 2027 roku jako wybranego przez użytkownika redakcyjnego punktu po zimie; żadna cytowana źródłowa ocena nie wskazuje tego dnia. Scenariusz optymistyczny sięga końca 2035 roku, jeśli dojrzeją gotowość UE i inwestycje NATO. Wszystkie daty są warunkowymi terminami redakcyjnymi, a nie ocenami intencji ani pewnymi datami konfliktu.'
            ),
            'causes' => $this->shared->tl(
                en: ['Russia’s war against Ukraine and persistent coercive pressure.', 'Long-standing capability, stockpile and industrial-production gaps.', 'Fragmented procurement and slow cross-border military mobility.', 'Hybrid, cyber and infrastructure threats below the threshold of open war.'],
                it: ['La guerra russa contro l’Ucraina e la persistente pressione coercitiva.', 'Divari di lunga durata in capacità, scorte e produzione industriale.', 'Acquisti frammentati e mobilità militare transfrontaliera lenta.', 'Minacce ibride, cyber e alle infrastrutture sotto la soglia della guerra aperta.'],
                fr: ['La guerre russe contre l’Ukraine et la pression coercitive persistante.', 'Des lacunes durables en capacités, stocks et production industrielle.', 'Des achats fragmentés et une mobilité militaire transfrontalière lente.', 'Des menaces hybrides, cyber et infrastructurelles sous le seuil de la guerre ouverte.'],
                de: ['Russlands Krieg gegen die Ukraine und anhaltender Zwangsdruck.', 'Langjährige Lücken bei Fähigkeiten, Beständen und Industrieproduktion.', 'Fragmentierte Beschaffung und langsame grenzüberschreitende Militärmobilität.', 'Hybride, Cyber- und Infrastrukturbedrohungen unterhalb der offenen Kriegsschwelle.'],
                es: ['La guerra de Rusia contra Ucrania y la presión coercitiva persistente.', 'Brechas duraderas de capacidades, reservas y producción industrial.', 'Contratación fragmentada y movilidad militar transfronteriza lenta.', 'Amenazas híbridas, cibernéticas y de infraestructura por debajo de la guerra abierta.'],
                nl: ['Ruslands oorlog tegen Oekraïne en aanhoudende dwangdruk.', 'Langdurige tekorten aan capaciteiten, voorraden en industriële productie.', 'Versnipperde aanbesteding en trage grensoverschrijdende militaire mobiliteit.', 'Hybride, cyber- en infrastructuurdreigingen onder de drempel van open oorlog.'],
                sv: ['Rysslands krig mot Ukraina och ihållande tvångstryck.', 'Långvariga luckor i förmåga, lager och industriproduktion.', 'Splittrad upphandling och långsam gränsöverskridande militär rörlighet.', 'Hybrid-, cyber- och infrastrukturhot under tröskeln för öppet krig.'],
                pl: ['Wojna Rosji przeciw Ukrainie i trwała presja przymusu.', 'Długotrwałe luki w zdolnościach, zapasach i produkcji przemysłowej.', 'Rozdrobnione zamówienia i powolna transgraniczna mobilność wojskowa.', 'Zagrożenia hybrydowe, cybernetyczne i infrastrukturalne poniżej progu otwartej wojny.'],
            ),
            'consequences' => $this->shared->tl(
                en: ['A deterrence gap can increase the risk of coercion or miscalculation.', 'Insufficient air defence, ammunition and logistics can slow collective response.', 'Emergency procurement can raise costs and reduce interoperability.', 'Civil infrastructure and democratic institutions remain exposed to hybrid pressure.'],
                it: ['Un divario di deterrenza può aumentare il rischio di coercizione o errore di calcolo.', 'Difesa aerea, munizioni e logistica insufficienti possono rallentare la risposta collettiva.', 'Gli acquisti d’emergenza possono aumentare i costi e ridurre l’interoperabilità.', 'Infrastrutture civili e istituzioni democratiche restano esposte alla pressione ibrida.'],
                fr: ['Un déficit de dissuasion peut accroître le risque de coercition ou d’erreur de calcul.', 'Une défense aérienne, des munitions et une logistique insuffisantes peuvent ralentir la réponse collective.', 'Les achats d’urgence peuvent augmenter les coûts et réduire l’interopérabilité.', 'Les infrastructures civiles et les institutions démocratiques restent exposées à la pression hybride.'],
                de: ['Eine Abschreckungslücke kann Zwang oder Fehlkalkulationen wahrscheinlicher machen.', 'Unzureichende Luftverteidigung, Munition und Logistik können die gemeinsame Reaktion bremsen.', 'Notbeschaffung kann Kosten erhöhen und Interoperabilität verringern.', 'Zivile Infrastruktur und demokratische Institutionen bleiben hybridem Druck ausgesetzt.'],
                es: ['Una brecha de disuasión puede aumentar el riesgo de coerción o error de cálculo.', 'Una defensa aérea, munición y logística insuficientes pueden ralentizar la respuesta colectiva.', 'La contratación de emergencia puede elevar costes y reducir interoperabilidad.', 'Las infraestructuras civiles y las instituciones democráticas siguen expuestas a presión híbrida.'],
                nl: ['Een afschrikkingskloof kan het risico op dwang of misrekening vergroten.', 'Onvoldoende luchtverdediging, munitie en logistiek kunnen de gezamenlijke reactie vertragen.', 'Noodaankopen kunnen kosten verhogen en interoperabiliteit verlagen.', 'Civiele infrastructuur en democratische instellingen blijven blootstaan aan hybride druk.'],
                sv: ['Ett avskräckningsgap kan öka risken för tvång eller felbedömning.', 'Otillräckligt luftförsvar, ammunition och logistik kan bromsa det gemensamma svaret.', 'Akut upphandling kan höja kostnader och minska interoperabilitet.', 'Civil infrastruktur och demokratiska institutioner förblir utsatta för hybridtryck.'],
                pl: ['Luka odstraszania może zwiększyć ryzyko przymusu lub błędnej kalkulacji.', 'Niewystarczająca obrona powietrzna, amunicja i logistyka mogą spowolnić wspólną reakcję.', 'Zakupy awaryjne mogą podnosić koszty i ograniczać interoperacyjność.', 'Infrastruktura cywilna i instytucje demokratyczne pozostają narażone na presję hybrydową.'],
            ),
            'recommended_actions' => $this->shared->tl(
                en: ['Track official spending and delivery data rather than political announcements alone.', 'Prioritise joint procurement, ammunition, air defence and military mobility.', 'Measure industrial output and delivery milestones against Readiness 2030 objectives.', 'Strengthen civil resilience, infrastructure protection and democratic safeguards.'],
                it: ['Seguire dati ufficiali su spesa e consegne, non solo annunci politici.', 'Dare priorità ad acquisti comuni, munizioni, difesa aerea e mobilità militare.', 'Misurare produzione e consegne rispetto agli obiettivi Readiness 2030.', 'Rafforzare resilienza civile, protezione delle infrastrutture e garanzie democratiche.'],
                fr: ['Suivre les données officielles de dépenses et de livraisons plutôt que les seules annonces politiques.', 'Prioriser les achats communs, les munitions, la défense aérienne et la mobilité militaire.', 'Mesurer production et livraisons par rapport aux objectifs Readiness 2030.', 'Renforcer la résilience civile, la protection des infrastructures et les garanties démocratiques.'],
                de: ['Offizielle Ausgaben- und Lieferdaten statt nur politischer Ankündigungen verfolgen.', 'Gemeinsame Beschaffung, Munition, Luftverteidigung und Militärmobilität priorisieren.', 'Industrieproduktion und Liefermeilensteine an Readiness-2030-Zielen messen.', 'Zivile Resilienz, Infrastrukturschutz und demokratische Sicherungen stärken.'],
                es: ['Seguir datos oficiales de gasto y entregas, no solo anuncios políticos.', 'Priorizar contratación conjunta, munición, defensa aérea y movilidad militar.', 'Medir producción y entregas frente a los objetivos Readiness 2030.', 'Reforzar resiliencia civil, protección de infraestructuras y salvaguardias democráticas.'],
                nl: ['Volg officiële uitgaven- en leveringsgegevens, niet alleen politieke aankondigingen.', 'Geef prioriteit aan gezamenlijke inkoop, munitie, luchtverdediging en militaire mobiliteit.', 'Meet productie en leveringen aan de doelstellingen van Readiness 2030.', 'Versterk civiele weerbaarheid, infrastructuurbescherming en democratische waarborgen.'],
                sv: ['Följ officiella utgifts- och leveransdata, inte bara politiska tillkännagivanden.', 'Prioritera gemensam upphandling, ammunition, luftförsvar och militär rörlighet.', 'Mät industriproduktion och leveranser mot Readiness 2030-målen.', 'Stärk civil motståndskraft, infrastrukturskydd och demokratiska skydd.'],
                pl: ['Śledzić oficjalne dane o wydatkach i dostawach, a nie tylko deklaracje polityczne.', 'Priorytetowo traktować wspólne zamówienia, amunicję, obronę powietrzną i mobilność wojskową.', 'Mierzyć produkcję i dostawy względem celów Readiness 2030.', 'Wzmacniać odporność cywilną, ochronę infrastruktury i zabezpieczenia demokratyczne.'],
            ),
            'severity' => CountdownSeverity::Severe,
            'status' => CountdownStatus::Active,
            'target_date' => CarbonImmutable::parse('2030-12-31 23:59:59', 'UTC'),
            'image_path' => $this->shared::IMAGE_PATH,
            'accent_color' => '#2563eb',
            'sort_order' => 2,
            'is_published' => true,
        ];
    }
};
