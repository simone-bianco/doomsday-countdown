<?php

declare(strict_types=1);

namespace App\Services\Doomsday\Copy;

final class AboutPageCopy
{
    /** @return array<string, mixed> */
    public function forLocale(string $locale): array
    {
        $copy = $this->copy();
        $labels = $this->labels();

        return array_merge($copy[$locale] ?? $copy['en'], $labels[$locale] ?? $labels['en']);
    }

    /** @return array<string, array<string, string>> */
    private function labels(): array
    {
        return [
            'en' => ['filter_watch_label' => 'Great Filter watch', 'visual_label' => 'Planetary risk interface', 'pipeline_label' => 'Scenario pipeline', 'faq_title' => 'Questions that matter', 'faq_subtitle' => 'A countdown is only useful if its assumptions are readable. This section keeps the fear grounded and the method transparent.', 'closing_label' => 'Final signal'],
            'it' => ['filter_watch_label' => 'Osservatorio Great Filter', 'visual_label' => 'Interfaccia rischio planetario', 'pipeline_label' => 'Pipeline degli scenari', 'faq_title' => 'Domande che contano', 'faq_subtitle' => 'Un countdown serve solo se le sue assunzioni sono leggibili. Questa sezione tiene la paura ancorata e il metodo trasparente.', 'closing_label' => 'Segnale finale'],
            'fr' => ['filter_watch_label' => 'Veille Great Filter', 'visual_label' => 'Interface de risque planétaire', 'pipeline_label' => 'Chaîne des scénarios', 'faq_title' => 'Questions essentielles', 'faq_subtitle' => 'Un compte à rebours n’est utile que si ses hypothèses sont lisibles. Cette section garde la peur au sol et la méthode transparente.', 'closing_label' => 'Signal final'],
            'de' => ['filter_watch_label' => 'Great-Filter-Wache', 'visual_label' => 'Planetare Risikooberfläche', 'pipeline_label' => 'Szenario-Pipeline', 'faq_title' => 'Fragen, die zählen', 'faq_subtitle' => 'Ein Countdown ist nur nützlich, wenn seine Annahmen lesbar sind. Dieser Abschnitt hält Angst geerdet und Methode transparent.', 'closing_label' => 'Letztes Signal'],
            'es' => ['filter_watch_label' => 'Vigilancia Great Filter', 'visual_label' => 'Interfaz de riesgo planetario', 'pipeline_label' => 'Canal de escenarios', 'faq_title' => 'Preguntas que importan', 'faq_subtitle' => 'Una cuenta atrás solo sirve si sus supuestos son legibles. Esta sección mantiene el miedo en tierra y el método transparente.', 'closing_label' => 'Señal final'],
            'nl' => ['filter_watch_label' => 'Great Filter-wacht', 'visual_label' => 'Interface voor planetair risico', 'pipeline_label' => 'Scenariopijplijn', 'faq_title' => 'Vragen die ertoe doen', 'faq_subtitle' => 'Een countdown is alleen nuttig als de aannames leesbaar zijn. Deze sectie houdt de angst gegrond en de methode transparant.', 'closing_label' => 'Eindsignaal'],
            'sv' => ['filter_watch_label' => 'Great Filter-bevakning', 'visual_label' => 'Gränssnitt för planetär risk', 'pipeline_label' => 'Scenariopipeline', 'faq_title' => 'Frågor som spelar roll', 'faq_subtitle' => 'En nedräkning är bara användbar om antagandena går att läsa. Den här delen håller rädslan förankrad och metoden transparent.', 'closing_label' => 'Slutsignal'],
            'pl' => ['filter_watch_label' => 'Obserwacja Great Filter', 'visual_label' => 'Interfejs ryzyka planetarnego', 'pipeline_label' => 'Potok scenariuszy', 'faq_title' => 'Pytania, które mają znaczenie', 'faq_subtitle' => 'Licznik jest użyteczny tylko wtedy, gdy jego założenia są czytelne. Ta sekcja urealnia strach i pokazuje metodę.', 'closing_label' => 'Sygnał końcowy'],
        ];
    }

    /** @return array<string, array<string, mixed>> */
    private function copy(): array
    {
        return [
            'en' => $this->page(
                title: 'Why this clock exists',
                subtitle: 'Doomsday Clock was created to turn scattered signals of collapse into a readable public interface: not prophecy, not panic, but a brutally clear map of risk.',
                eyebrow: 'A warning system for a fragile century',
                heroBadge: 'Civilization risk desk',
                intro: [
                    'The further we move into this century, the more the world feels like it is fraying: wars become harder to contain, supply chains break in strange places, climate pressure compounds every crisis, and technology accelerates faster than our institutions can absorb.',
                    'As an advanced civilization, we may be approaching one of the Great Filter thresholds: the kind of bottleneck that decides whether a species grows up or disappears. This site exists because extinction is not a cinematic idea anymore; it is a planning problem.',
                ],
                stats: [
                    ['label' => 'Purpose', 'value' => 'Signal', 'description' => 'Make complex risks legible before they become headlines.'],
                    ['label' => 'Tone', 'value' => 'Sober', 'description' => 'High drama is useless; clear pressure indicators are useful.'],
                    ['label' => 'Goal', 'value' => 'Action', 'description' => 'Push attention toward preparation, resilience and prevention.'],
                ],
                sections: [
                    ['title' => 'Why it was created', 'body' => 'Most people meet existential risk as isolated news: one conflict, one heat record, one market shock, one cyber incident. The site was built to connect those fragments into scenarios that can be monitored over time.'],
                    ['title' => 'What it is for', 'body' => 'Each countdown is a structured risk lens. It gathers curated public-source context, estimated windows, indicators, consequences and practical actions so a reader can understand what is moving and why it matters.'],
                    ['title' => 'What it is not', 'body' => 'The dates are not fate. They are pressure windows. A countdown reaching zero should never be read as a supernatural prediction; it means the scenario deserves attention, stress testing and better decisions.'],
                ],
                timeline: [
                    ['label' => '01', 'title' => 'Detect weak signals', 'body' => 'Collect public indicators that are easy to miss when they appear alone.'],
                    ['label' => '02', 'title' => 'Frame scenarios', 'body' => 'Turn fragmented events into understandable risk pathways with assumptions exposed.'],
                    ['label' => '03', 'title' => 'Keep the clock visible', 'body' => 'Make the risk hard to ignore without pretending that the future is already written.'],
                ],
                faq: [
                    ['question' => 'Is this site predicting the end of the world?', 'answer' => 'No. It presents scenario windows and risk pressure, not certainties. The point is to make danger visible early enough to respond.'],
                    ['question' => 'Why use countdowns for serious topics?', 'answer' => 'A clock creates urgency and structure. It turns abstract risk into something people can revisit, compare and discuss without reading a full research dossier every time.'],
                    ['question' => 'What does the Great Filter mean here?', 'answer' => 'It is a way to describe a possible civilizational bottleneck: a threshold where technology, conflict, ecology or governance failures could outpace our ability to adapt.'],
                    ['question' => 'Can these scenarios be avoided?', 'answer' => 'That is the reason to publish them. A good warning is not a death sentence; it is a chance to change behavior, build resilience and reduce harm.'],
                    ['question' => 'Where does the data come from?', 'answer' => 'The current public version uses curated public-source scenario data seeded in the local database. Live ingestion is not part of this phase.'],
                ],
                closingTitle: 'The clock is not the enemy.',
                closingBody: 'The enemy is sleepwalking. If a scenario is frightening, the answer is not denial; it is literacy, preparation and pressure on the systems that still have time to change.',
            ),
            'it' => $this->page(
                title: 'Perché questo orologio esiste',
                subtitle: 'Doomsday Clock nasce per trasformare segnali sparsi di collasso in un’interfaccia pubblica leggibile: non profezia, non panico, ma una mappa brutale e chiara del rischio.',
                eyebrow: 'Un sistema di allerta per un secolo fragile',
                heroBadge: 'Osservatorio rischio civiltà',
                intro: [
                    'Più andiamo avanti in questo secolo, più il mondo sembra andare allo sfacelo: le guerre diventano più difficili da contenere, le catene di fornitura si rompono in punti strani, la pressione climatica amplifica ogni crisi e la tecnologia accelera più in fretta delle nostre istituzioni.',
                    'Come civiltà avanzata potremmo avvicinarci a una delle soglie del Great Filter: il tipo di collo di bottiglia che decide se una specie matura o scompare. Questo sito esiste perché l’estinzione non è più un’idea cinematografica; è un problema di pianificazione.',
                ],
                stats: [
                    ['label' => 'Scopo', 'value' => 'Segnale', 'description' => 'Rendere leggibili i rischi complessi prima che diventino titoli.'],
                    ['label' => 'Tono', 'value' => 'Lucido', 'description' => 'Il dramma non serve; servono indicatori di pressione chiari.'],
                    ['label' => 'Obiettivo', 'value' => 'Azione', 'description' => 'Portare attenzione su preparazione, resilienza e prevenzione.'],
                ],
                sections: [
                    ['title' => 'Perché è stato creato', 'body' => 'La maggior parte delle persone incontra il rischio esistenziale come notizie isolate: un conflitto, un record di caldo, uno shock di mercato, un incidente cyber. Il sito collega quei frammenti in scenari osservabili nel tempo.'],
                    ['title' => 'A cosa serve', 'body' => 'Ogni countdown è una lente di rischio strutturata. Riunisce contesto da fonti pubbliche curate, finestre stimate, indicatori, conseguenze e azioni pratiche per capire cosa si muove e perché conta.'],
                    ['title' => 'Cosa non è', 'body' => 'Le date non sono destino. Sono finestre di pressione. Un countdown a zero non va letto come previsione soprannaturale; significa che lo scenario merita attenzione, stress test e decisioni migliori.'],
                ],
                timeline: [
                    ['label' => '01', 'title' => 'Intercettare segnali deboli', 'body' => 'Raccogliere indicatori pubblici facili da ignorare quando appaiono da soli.'],
                    ['label' => '02', 'title' => 'Inquadrare scenari', 'body' => 'Trasformare eventi frammentati in traiettorie di rischio comprensibili, con assunzioni esplicite.'],
                    ['label' => '03', 'title' => 'Tenere visibile l’orologio', 'body' => 'Rendere il rischio difficile da ignorare senza fingere che il futuro sia già scritto.'],
                ],
                faq: [
                    ['question' => 'Questo sito predice la fine del mondo?', 'answer' => 'No. Presenta finestre di scenario e pressione di rischio, non certezze. Il punto è rendere il pericolo visibile abbastanza presto da poter reagire.'],
                    ['question' => 'Perché usare countdown per temi così seri?', 'answer' => 'Un orologio crea urgenza e struttura. Trasforma un rischio astratto in qualcosa da rivedere, confrontare e discutere senza leggere ogni volta un dossier completo.'],
                    ['question' => 'Cosa significa Great Filter qui?', 'answer' => 'È un modo per descrivere un possibile collo di bottiglia della civiltà: una soglia in cui tecnologia, conflitto, ecologia o fallimenti di governance possono superare la nostra capacità di adattamento.'],
                    ['question' => 'Questi scenari possono essere evitati?', 'answer' => 'È proprio il motivo per cui vengono pubblicati. Un buon allarme non è una condanna; è una possibilità di cambiare comportamento, costruire resilienza e ridurre il danno.'],
                    ['question' => 'Da dove arrivano i dati?', 'answer' => 'La versione pubblica attuale usa dati scenario da fonti pubbliche curate e presenti nel database locale. L’ingestione live non fa parte di questa fase.'],
                ],
                closingTitle: 'L’orologio non è il nemico.',
                closingBody: 'Il nemico è procedere sonnambuli. Se uno scenario fa paura, la risposta non è negarlo: è alfabetizzazione, preparazione e pressione sui sistemi che hanno ancora tempo per cambiare.',
            ),
            'fr' => $this->page(
                title: 'Pourquoi cette horloge existe',
                subtitle: 'Doomsday Clock transforme des signaux dispersés d’effondrement en interface publique lisible : pas une prophétie, pas une panique, mais une carte brutalement claire du risque.',
                eyebrow: 'Un système d’alerte pour un siècle fragile',
                heroBadge: 'Observatoire du risque civilisationnel',
                intro: [
                    'Plus nous avançons dans ce siècle, plus le monde semble se défaire : les guerres deviennent plus difficiles à contenir, les chaînes d’approvisionnement cèdent à des endroits inattendus, la pression climatique amplifie chaque crise et la technologie accélère plus vite que nos institutions.',
                    'En tant que civilisation avancée, nous pourrions approcher l’un des seuils du Great Filter : le type de goulot d’étranglement qui décide si une espèce mûrit ou disparaît. Ce site existe parce que l’extinction n’est plus une idée de cinéma ; c’est un problème de planification.',
                ],
                stats: [
                    ['label' => 'But', 'value' => 'Signal', 'description' => 'Rendre les risques complexes lisibles avant qu’ils deviennent des titres.'],
                    ['label' => 'Ton', 'value' => 'Sobre', 'description' => 'Le drame ne sert à rien ; les indicateurs clairs, si.'],
                    ['label' => 'Objectif', 'value' => 'Action', 'description' => 'Orienter l’attention vers préparation, résilience et prévention.'],
                ],
                sections: [
                    ['title' => 'Pourquoi il a été créé', 'body' => 'La plupart des gens rencontrent le risque existentiel sous forme de nouvelles isolées : un conflit, un record de chaleur, un choc de marché, un incident cyber. Le site relie ces fragments en scénarios suivis dans le temps.'],
                    ['title' => 'À quoi il sert', 'body' => 'Chaque compte à rebours est une lentille de risque structurée. Il rassemble contexte public, fenêtres estimées, indicateurs, conséquences et actions pratiques pour comprendre ce qui bouge et pourquoi cela compte.'],
                    ['title' => 'Ce qu’il n’est pas', 'body' => 'Les dates ne sont pas le destin. Ce sont des fenêtres de pression. Un compte à rebours à zéro n’est pas une prédiction surnaturelle ; cela signifie que le scénario exige attention, tests de résistance et meilleures décisions.'],
                ],
                timeline: [
                    ['label' => '01', 'title' => 'Détecter les signaux faibles', 'body' => 'Collecter les indicateurs publics faciles à manquer lorsqu’ils apparaissent seuls.'],
                    ['label' => '02', 'title' => 'Cadrer les scénarios', 'body' => 'Transformer des événements fragmentés en trajectoires de risque compréhensibles avec hypothèses visibles.'],
                    ['label' => '03', 'title' => 'Garder l’horloge visible', 'body' => 'Rendre le risque difficile à ignorer sans prétendre que l’avenir est déjà écrit.'],
                ],
                faq: [
                    ['question' => 'Ce site prédit-il la fin du monde ?', 'answer' => 'Non. Il présente des fenêtres de scénario et une pression de risque, pas des certitudes. Le but est de rendre le danger visible assez tôt pour agir.'],
                    ['question' => 'Pourquoi utiliser des comptes à rebours ?', 'answer' => 'Une horloge crée urgence et structure. Elle transforme un risque abstrait en objet que l’on peut revoir, comparer et discuter.'],
                    ['question' => 'Que signifie Great Filter ici ?', 'answer' => 'C’est une façon de décrire un possible goulot d’étranglement civilisationnel où technologie, conflit, écologie ou gouvernance dépassent notre capacité d’adaptation.'],
                    ['question' => 'Ces scénarios peuvent-ils être évités ?', 'answer' => 'C’est la raison de leur publication. Une bonne alerte n’est pas une condamnation ; c’est une chance de changer le comportement et de réduire les dégâts.'],
                    ['question' => 'D’où viennent les données ?', 'answer' => 'La version publique actuelle utilise des données de scénario issues de sources publiques organisées dans la base locale. Pas d’ingestion live à ce stade.'],
                ],
                closingTitle: 'L’horloge n’est pas l’ennemi.',
                closingBody: 'L’ennemi, c’est l’endormissement collectif. Si un scénario fait peur, la réponse n’est pas le déni : c’est la compréhension, la préparation et la pression sur les systèmes encore modifiables.',
            ),
            'de' => $this->page(
                title: 'Warum diese Uhr existiert',
                subtitle: 'Doomsday Clock macht verstreute Kollapssignale als öffentliche Oberfläche lesbar: keine Prophezeiung, keine Panik, sondern eine brutal klare Risikokarte.',
                eyebrow: 'Ein Warnsystem für ein fragiles Jahrhundert',
                heroBadge: 'Zivilisationsrisiko-Desk',
                intro: [
                    'Je weiter dieses Jahrhundert voranschreitet, desto mehr wirkt die Welt, als würde sie ausfransen: Kriege lassen sich schwerer eindämmen, Lieferketten brechen an unerwarteten Stellen, Klimadruck verstärkt jede Krise und Technologie beschleunigt schneller als unsere Institutionen.',
                    'Als fortgeschrittene Zivilisation könnten wir uns einer Schwelle des Great Filter nähern: einem Engpass, der entscheidet, ob eine Spezies erwachsen wird oder verschwindet. Diese Seite existiert, weil Aussterben keine Kinoidee mehr ist, sondern ein Planungsproblem.',
                ],
                stats: [
                    ['label' => 'Zweck', 'value' => 'Signal', 'description' => 'Komplexe Risiken lesbar machen, bevor sie Schlagzeilen werden.'],
                    ['label' => 'Ton', 'value' => 'Nüchtern', 'description' => 'Drama hilft nicht; klare Druckindikatoren helfen.'],
                    ['label' => 'Ziel', 'value' => 'Handeln', 'description' => 'Auf Vorbereitung, Resilienz und Prävention ausrichten.'],
                ],
                sections: [
                    ['title' => 'Warum es gebaut wurde', 'body' => 'Die meisten Menschen begegnen existenziellem Risiko als Einzelmeldungen: ein Konflikt, ein Hitzerekord, ein Marktschock, ein Cybervorfall. Die Seite verbindet diese Fragmente zu beobachtbaren Szenarien.'],
                    ['title' => 'Wofür es dient', 'body' => 'Jeder Countdown ist eine strukturierte Risikolinse. Er bündelt öffentlichen Kontext, geschätzte Fenster, Indikatoren, Folgen und praktische Handlungen, damit klar wird, was sich bewegt und warum es zählt.'],
                    ['title' => 'Was es nicht ist', 'body' => 'Die Daten sind kein Schicksal. Sie sind Druckfenster. Null bedeutet keine übernatürliche Vorhersage, sondern dass ein Szenario Aufmerksamkeit, Stresstests und bessere Entscheidungen braucht.'],
                ],
                timeline: [
                    ['label' => '01', 'title' => 'Schwache Signale erkennen', 'body' => 'Öffentliche Indikatoren sammeln, die einzeln leicht übersehen werden.'],
                    ['label' => '02', 'title' => 'Szenarien rahmen', 'body' => 'Fragmentierte Ereignisse in verständliche Risikopfade mit sichtbaren Annahmen übersetzen.'],
                    ['label' => '03', 'title' => 'Die Uhr sichtbar halten', 'body' => 'Das Risiko schwer ignorierbar machen, ohne so zu tun, als sei die Zukunft schon geschrieben.'],
                ],
                faq: [
                    ['question' => 'Sagt diese Seite das Ende der Welt voraus?', 'answer' => 'Nein. Sie zeigt Szenariofenster und Risikodruck, keine Gewissheiten. Ziel ist, Gefahr früh genug sichtbar zu machen.'],
                    ['question' => 'Warum Countdowns für ernste Themen?', 'answer' => 'Eine Uhr schafft Dringlichkeit und Struktur. Sie macht abstraktes Risiko vergleichbar und wiederbesuchbar.'],
                    ['question' => 'Was bedeutet Great Filter hier?', 'answer' => 'Es beschreibt einen möglichen zivilisatorischen Engpass, bei dem Technologie, Konflikt, Ökologie oder Governance unsere Anpassungsfähigkeit überholen.'],
                    ['question' => 'Können diese Szenarien verhindert werden?', 'answer' => 'Genau deshalb werden sie veröffentlicht. Eine gute Warnung ist kein Urteil, sondern eine Chance zur Verhaltensänderung und Schadensminderung.'],
                    ['question' => 'Woher stammen die Daten?', 'answer' => 'Die aktuelle öffentliche Version nutzt kuratierte öffentliche Szenariodaten aus der lokalen Datenbank. Live-Ingestion gehört nicht zu dieser Phase.'],
                ],
                closingTitle: 'Die Uhr ist nicht der Feind.',
                closingBody: 'Der Feind ist Schlafwandeln. Wenn ein Szenario Angst macht, ist die Antwort nicht Verdrängung, sondern Verständnis, Vorbereitung und Druck auf veränderbare Systeme.',
            ),
            'es' => $this->page(
                title: 'Por qué existe este reloj',
                subtitle: 'Doomsday Clock convierte señales dispersas de colapso en una interfaz pública legible: no profecía, no pánico, sino un mapa brutalmente claro del riesgo.',
                eyebrow: 'Un sistema de alerta para un siglo frágil',
                heroBadge: 'Mesa de riesgo civilizatorio',
                intro: [
                    'Cuanto más avanzamos en este siglo, más parece que el mundo se deshilacha: las guerras son más difíciles de contener, las cadenas de suministro fallan en lugares extraños, la presión climática agrava cada crisis y la tecnología acelera más rápido que nuestras instituciones.',
                    'Como civilización avanzada, podríamos estar acercándonos a uno de los umbrales del Great Filter: el cuello de botella que decide si una especie madura o desaparece. Este sitio existe porque la extinción ya no es una idea cinematográfica; es un problema de planificación.',
                ],
                stats: [
                    ['label' => 'Propósito', 'value' => 'Señal', 'description' => 'Hacer legibles los riesgos complejos antes de que sean titulares.'],
                    ['label' => 'Tono', 'value' => 'Sobrio', 'description' => 'El drama no sirve; los indicadores claros sí.'],
                    ['label' => 'Objetivo', 'value' => 'Acción', 'description' => 'Dirigir atención a preparación, resiliencia y prevención.'],
                ],
                sections: [
                    ['title' => 'Por qué fue creado', 'body' => 'La mayoría encuentra el riesgo existencial como noticias aisladas: un conflicto, un récord de calor, un choque de mercado, un incidente cibernético. El sitio conecta esos fragmentos en escenarios monitorizables.'],
                    ['title' => 'Para qué sirve', 'body' => 'Cada cuenta atrás es una lente de riesgo estructurada. Reúne contexto público, ventanas estimadas, indicadores, consecuencias y acciones prácticas para entender qué se mueve y por qué importa.'],
                    ['title' => 'Lo que no es', 'body' => 'Las fechas no son destino. Son ventanas de presión. Llegar a cero no es una predicción sobrenatural; significa que el escenario merece atención, pruebas de estrés y mejores decisiones.'],
                ],
                timeline: [
                    ['label' => '01', 'title' => 'Detectar señales débiles', 'body' => 'Recoger indicadores públicos fáciles de ignorar cuando aparecen solos.'],
                    ['label' => '02', 'title' => 'Enmarcar escenarios', 'body' => 'Convertir eventos fragmentados en trayectorias de riesgo comprensibles con supuestos visibles.'],
                    ['label' => '03', 'title' => 'Mantener visible el reloj', 'body' => 'Hacer que el riesgo sea difícil de ignorar sin fingir que el futuro ya está escrito.'],
                ],
                faq: [
                    ['question' => '¿Este sitio predice el fin del mundo?', 'answer' => 'No. Presenta ventanas de escenario y presión de riesgo, no certezas. El objetivo es hacer visible el peligro a tiempo.'],
                    ['question' => '¿Por qué usar cuentas atrás?', 'answer' => 'Un reloj crea urgencia y estructura. Convierte un riesgo abstracto en algo que se puede revisar, comparar y discutir.'],
                    ['question' => '¿Qué significa Great Filter aquí?', 'answer' => 'Describe un posible cuello de botella civilizatorio donde tecnología, conflicto, ecología o gobernanza pueden superar nuestra capacidad de adaptación.'],
                    ['question' => '¿Se pueden evitar estos escenarios?', 'answer' => 'Por eso se publican. Una buena alerta no es una condena; es una oportunidad para cambiar conductas y reducir daños.'],
                    ['question' => '¿De dónde salen los datos?', 'answer' => 'La versión pública actual usa datos de escenarios de fuentes públicas curadas en la base local. No hay ingestión en vivo en esta fase.'],
                ],
                closingTitle: 'El reloj no es el enemigo.',
                closingBody: 'El enemigo es caminar dormidos. Si un escenario asusta, la respuesta no es negarlo: es entender, prepararse y presionar a los sistemas que aún pueden cambiar.',
            ),
            'nl' => $this->page(
                title: 'Waarom deze klok bestaat',
                subtitle: 'Doomsday Clock vertaalt losse signalen van instorting naar een leesbare publieke interface: geen profetie, geen paniek, maar een genadeloos heldere risicokaart.',
                eyebrow: 'Een waarschuwingssysteem voor een fragiele eeuw',
                heroBadge: 'Civilisatierisico-desk',
                intro: [
                    'Hoe verder deze eeuw vordert, hoe meer de wereld lijkt te rafelen: oorlogen zijn moeilijker te beheersen, toeleveringsketens breken op vreemde plekken, klimaatdruk vergroot elke crisis en technologie versnelt sneller dan onze instellingen.',
                    'Als geavanceerde beschaving naderen we mogelijk een drempel van de Great Filter: een knelpunt dat bepaalt of een soort volwassen wordt of verdwijnt. Deze site bestaat omdat uitsterven geen filmidee meer is; het is een planningsprobleem.',
                ],
                stats: [
                    ['label' => 'Doel', 'value' => 'Signaal', 'description' => 'Complexe risico’s leesbaar maken voordat ze koppen worden.'],
                    ['label' => 'Toon', 'value' => 'Nuchter', 'description' => 'Drama helpt niet; heldere drukindicatoren wel.'],
                    ['label' => 'Resultaat', 'value' => 'Actie', 'description' => 'Aandacht richten op voorbereiding, veerkracht en preventie.'],
                ],
                sections: [
                    ['title' => 'Waarom het is gemaakt', 'body' => 'De meeste mensen ontmoeten existentieel risico als losse nieuwsfeiten: een conflict, hitterecord, marktschok of cyberincident. De site verbindt die fragmenten tot scenario’s die over tijd te volgen zijn.'],
                    ['title' => 'Waarvoor het dient', 'body' => 'Elke countdown is een gestructureerde risicolens. Hij bundelt publieke context, geschatte vensters, indicatoren, gevolgen en praktische acties om te begrijpen wat beweegt en waarom dat telt.'],
                    ['title' => 'Wat het niet is', 'body' => 'De datums zijn geen lot. Het zijn drukvensters. Nul betekent geen bovennatuurlijke voorspelling; het betekent dat een scenario aandacht, stresstests en betere beslissingen verdient.'],
                ],
                timeline: [
                    ['label' => '01', 'title' => 'Zwakke signalen vinden', 'body' => 'Publieke indicatoren verzamelen die op zichzelf makkelijk gemist worden.'],
                    ['label' => '02', 'title' => 'Scenario’s kaderen', 'body' => 'Gefragmenteerde gebeurtenissen omzetten in begrijpelijke risicopaden met zichtbare aannames.'],
                    ['label' => '03', 'title' => 'De klok zichtbaar houden', 'body' => 'Risico moeilijk te negeren maken zonder te doen alsof de toekomst al vastligt.'],
                ],
                faq: [
                    ['question' => 'Voorspelt deze site het einde van de wereld?', 'answer' => 'Nee. Hij toont scenariovensters en risicodruk, geen zekerheden. Het doel is gevaar vroeg genoeg zichtbaar maken.'],
                    ['question' => 'Waarom countdowns gebruiken?', 'answer' => 'Een klok geeft urgentie en structuur. Ze maakt abstract risico bespreekbaar, vergelijkbaar en herhaalbaar.'],
                    ['question' => 'Wat betekent Great Filter hier?', 'answer' => 'Het beschrijft een mogelijk beschavingsknelpunt waarin technologie, conflict, ecologie of bestuur sneller gaan dan onze aanpassing.'],
                    ['question' => 'Kunnen deze scenario’s worden vermeden?', 'answer' => 'Daarom worden ze gepubliceerd. Een goede waarschuwing is geen vonnis, maar een kans om gedrag te veranderen en schade te beperken.'],
                    ['question' => 'Waar komen de gegevens vandaan?', 'answer' => 'De huidige publieke versie gebruikt gecureerde scenario-data uit publieke bronnen in de lokale database. Live-inname hoort niet bij deze fase.'],
                ],
                closingTitle: 'De klok is niet de vijand.',
                closingBody: 'De vijand is slaapwandelen. Als een scenario beangstigt, is ontkenning geen antwoord; begrip, voorbereiding en druk op veranderbare systemen wel.',
            ),
            'sv' => $this->page(
                title: 'Varför denna klocka finns',
                subtitle: 'Doomsday Clock gör spridda kollapssignaler till ett läsbart offentligt gränssnitt: inte profetia, inte panik, utan en brutalt tydlig riskkarta.',
                eyebrow: 'Ett varningssystem för ett skört århundrade',
                heroBadge: 'Desk för civilisationsrisk',
                intro: [
                    'Ju längre in i detta århundrade vi går, desto mer känns världen som om den fransar upp: krig blir svårare att begränsa, leveranskedjor brister på oväntade platser, klimattryck förstärker varje kris och tekniken accelererar snabbare än våra institutioner.',
                    'Som avancerad civilisation kan vi närma oss en tröskel i Great Filter: den typ av flaskhals som avgör om en art mognar eller försvinner. Den här webbplatsen finns eftersom utrotning inte längre är en filmidé; det är ett planeringsproblem.',
                ],
                stats: [
                    ['label' => 'Syfte', 'value' => 'Signal', 'description' => 'Göra komplexa risker läsbara innan de blir rubriker.'],
                    ['label' => 'Ton', 'value' => 'Nykter', 'description' => 'Drama hjälper inte; tydliga tryckindikatorer hjälper.'],
                    ['label' => 'Mål', 'value' => 'Handling', 'description' => 'Rikta uppmärksamhet mot förberedelse, resiliens och prevention.'],
                ],
                sections: [
                    ['title' => 'Varför den skapades', 'body' => 'De flesta möter existentiell risk som isolerade nyheter: en konflikt, ett värmerekord, en marknadschock, en cyberincident. Webbplatsen kopplar fragmenten till scenarier som kan följas över tid.'],
                    ['title' => 'Vad den är till för', 'body' => 'Varje nedräkning är en strukturerad risklins. Den samlar offentlig kontext, uppskattade fönster, indikatorer, konsekvenser och praktiska åtgärder så att läsaren förstår vad som rör sig och varför det spelar roll.'],
                    ['title' => 'Vad den inte är', 'body' => 'Datumen är inte öde. De är tryckfönster. Noll är ingen övernaturlig förutsägelse; det betyder att scenariot kräver uppmärksamhet, stresstest och bättre beslut.'],
                ],
                timeline: [
                    ['label' => '01', 'title' => 'Upptäcka svaga signaler', 'body' => 'Samla offentliga indikatorer som är lätta att missa när de syns var för sig.'],
                    ['label' => '02', 'title' => 'Rama in scenarier', 'body' => 'Göra fragmenterade händelser till begripliga riskvägar med synliga antaganden.'],
                    ['label' => '03', 'title' => 'Hålla klockan synlig', 'body' => 'Göra risken svår att ignorera utan att låtsas att framtiden redan är skriven.'],
                ],
                faq: [
                    ['question' => 'Förutspår sidan världens slut?', 'answer' => 'Nej. Den visar scenariofönster och risktryck, inte säkerheter. Målet är att göra fara synlig i tid.'],
                    ['question' => 'Varför använda nedräkningar?', 'answer' => 'En klocka skapar brådska och struktur. Den gör abstrakt risk möjlig att återbesöka, jämföra och diskutera.'],
                    ['question' => 'Vad betyder Great Filter här?', 'answer' => 'Det beskriver en möjlig civilisatorisk flaskhals där teknik, konflikt, ekologi eller styrning kan gå snabbare än vår anpassningsförmåga.'],
                    ['question' => 'Kan scenarierna undvikas?', 'answer' => 'Det är därför de publiceras. En bra varning är ingen dom; den är en chans att ändra beteende och minska skada.'],
                    ['question' => 'Var kommer datan från?', 'answer' => 'Den nuvarande publika versionen använder kuraterade scenariodata från offentliga källor i den lokala databasen. Liveintag ingår inte i fasen.'],
                ],
                closingTitle: 'Klockan är inte fienden.',
                closingBody: 'Fienden är sömngång. Om ett scenario skrämmer är svaret inte förnekelse, utan förståelse, förberedelse och tryck på system som fortfarande kan förändras.',
            ),
            'pl' => $this->page(
                title: 'Dlaczego ten zegar istnieje',
                subtitle: 'Doomsday Clock zamienia rozproszone sygnały rozpadu w czytelny publiczny interfejs: nie proroctwo, nie panikę, lecz brutalnie jasną mapę ryzyka.',
                eyebrow: 'System ostrzegania dla kruchego stulecia',
                heroBadge: 'Pulpit ryzyka cywilizacyjnego',
                intro: [
                    'Im dalej wchodzimy w to stulecie, tym bardziej świat wygląda, jakby się strzępił: wojny trudniej powstrzymać, łańcuchy dostaw pękają w dziwnych miejscach, presja klimatyczna wzmacnia każdy kryzys, a technologia przyspiesza szybciej niż instytucje.',
                    'Jako zaawansowana cywilizacja możemy zbliżać się do jednego z progów Great Filter: wąskiego gardła, które decyduje, czy gatunek dojrzewa, czy znika. Ta strona istnieje, bo wymarcie nie jest już pomysłem filmowym; jest problemem planowania.',
                ],
                stats: [
                    ['label' => 'Cel', 'value' => 'Sygnał', 'description' => 'Uczytelnić złożone ryzyka, zanim staną się nagłówkami.'],
                    ['label' => 'Ton', 'value' => 'Trzeźwy', 'description' => 'Dramat nie pomaga; jasne wskaźniki presji pomagają.'],
                    ['label' => 'Wynik', 'value' => 'Działanie', 'description' => 'Skierować uwagę na przygotowanie, odporność i prewencję.'],
                ],
                sections: [
                    ['title' => 'Dlaczego powstał', 'body' => 'Większość osób spotyka ryzyko egzystencjalne jako pojedyncze wiadomości: konflikt, rekord upału, szok rynkowy, incydent cybernetyczny. Strona łączy te fragmenty w scenariusze obserwowane w czasie.'],
                    ['title' => 'Do czego służy', 'body' => 'Każdy licznik jest uporządkowaną soczewką ryzyka. Łączy publiczny kontekst, szacowane okna, wskaźniki, konsekwencje i praktyczne działania, aby pokazać, co się zmienia i dlaczego ma znaczenie.'],
                    ['title' => 'Czym nie jest', 'body' => 'Daty nie są przeznaczeniem. To okna presji. Zero nie jest nadprzyrodzoną przepowiednią; oznacza, że scenariusz wymaga uwagi, testów odporności i lepszych decyzji.'],
                ],
                timeline: [
                    ['label' => '01', 'title' => 'Wykrywać słabe sygnały', 'body' => 'Zbierać publiczne wskaźniki, które łatwo przeoczyć, gdy pojawiają się osobno.'],
                    ['label' => '02', 'title' => 'Ramować scenariusze', 'body' => 'Zamieniać rozproszone zdarzenia w zrozumiałe ścieżki ryzyka z widocznymi założeniami.'],
                    ['label' => '03', 'title' => 'Utrzymać zegar widoczny', 'body' => 'Sprawić, by ryzyko trudno było ignorować, bez udawania, że przyszłość jest już napisana.'],
                ],
                faq: [
                    ['question' => 'Czy strona przewiduje koniec świata?', 'answer' => 'Nie. Pokazuje okna scenariuszy i presję ryzyka, nie pewniki. Celem jest widoczność zagrożenia odpowiednio wcześnie.'],
                    ['question' => 'Dlaczego używać liczników?', 'answer' => 'Zegar tworzy pilność i strukturę. Zamienia abstrakcyjne ryzyko w coś, do czego można wracać, co można porównywać i omawiać.'],
                    ['question' => 'Co oznacza tu Great Filter?', 'answer' => 'To opis możliwego wąskiego gardła cywilizacyjnego, w którym technologia, konflikt, ekologia lub zarządzanie wyprzedzają naszą zdolność adaptacji.'],
                    ['question' => 'Czy tych scenariuszy można uniknąć?', 'answer' => 'Właśnie dlatego są publikowane. Dobre ostrzeżenie nie jest wyrokiem; jest szansą na zmianę zachowań i ograniczenie szkód.'],
                    ['question' => 'Skąd pochodzą dane?', 'answer' => 'Obecna wersja publiczna używa kuratorowanych danych scenariuszy ze źródeł publicznych w lokalnej bazie. Pobieranie live nie należy do tej fazy.'],
                ],
                closingTitle: 'Zegar nie jest wrogiem.',
                closingBody: 'Wrogiem jest lunatykowanie. Jeśli scenariusz przeraża, odpowiedzią nie jest zaprzeczanie, lecz zrozumienie, przygotowanie i nacisk na systemy, które wciąż można zmienić.',
            ),
        ];
    }

    /**
     * @param array<int, string> $intro
     * @param array<int, array{label: string, value: string, description: string}> $stats
     * @param array<int, array{title: string, body: string}> $sections
     * @param array<int, array{label: string, title: string, body: string}> $timeline
     * @param array<int, array{question: string, answer: string}> $faq
     * @return array<string, mixed>
     */
    private function page(
        string $title,
        string $subtitle,
        string $eyebrow,
        string $heroBadge,
        array $intro,
        array $stats,
        array $sections,
        array $timeline,
        array $faq,
        string $closingTitle,
        string $closingBody,
    ): array {
        return [
            'title' => $title,
            'subtitle' => $subtitle,
            'eyebrow' => $eyebrow,
            'hero_badge' => $heroBadge,
            'intro' => $intro,
            'stats' => $stats,
            'sections' => $sections,
            'timeline' => $timeline,
            'faq' => $faq,
            'closing_title' => $closingTitle,
            'closing_body' => $closingBody,
        ];
    }
}
