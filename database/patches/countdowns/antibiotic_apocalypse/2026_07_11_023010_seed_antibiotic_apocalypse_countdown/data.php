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
            'slug' => 'antibiotic-apocalypse',
            'title' => $this->shared->t(
                'The Antibiotic Apocalypse',
                'L’apocalisse antibiotica',
                'L’apocalypse antibiotique',
                'Die Antibiotika-Apokalypse',
                'El apocalipsis antibiótico',
                'De antibiotica-apocalyps',
                'Antibiotikaapokalypsen',
                'Apokalipsa antybiotykowa',
            ),
            'summary' => $this->shared->t(
                'A 2032 post-target evidence window for antimicrobial resistance, treatment effectiveness and global action',
                'Una finestra post-target al 2032 per resistenza antimicrobica, efficacia delle cure e azione globale',
                'Une fenêtre d’évaluation post-cible en 2032 pour la résistance aux antimicrobiens, l’efficacité des traitements et l’action mondiale',
                'Ein Bewertungsfenster nach den Zieljahren bis 2032 für antimikrobielle Resistenzen, Therapiewirksamkeit und globales Handeln',
                'Una ventana de evaluación posterior a los objetivos en 2032 para la resistencia a los antimicrobianos, la eficacia terapéutica y la acción mundial',
                'Een evaluatievenster na de doelstellingen in 2032 voor antimicrobiële resistentie, behandelwerking en mondiale actie',
                'Ett utvärderingsfönster efter målen år 2032 för antimikrobiell resistens, behandlingseffekt och globala åtgärder',
                'Okno oceny po terminie celów w 2032 r. dla oporności na środki przeciwdrobnoustrojowe, skuteczności leczenia i działań globalnych',
            ),
            'description' => $this->shared->t(
                'The title is editorial. The active chain is Pessimistic 2029 → Neutral 2032 → Optimistic 2036. In the current state, 31 December 2029 is the active checkpoint; 31 December 2032 is the Neutral post-target evidence checkpoint, when 2030 AMR commitments should be assessable after surveillance and publication lag. AMR is broader than antibiotic resistance, and none of these dates predicts a certain treatment collapse.',
                'Il titolo è editoriale. La catena attiva è Pessimistic 2029 → Neutral 2032 → Optimistic 2036. Nello stato corrente, il 31 dicembre 2029 è il checkpoint attivo; il 31 dicembre 2032 è il checkpoint Neutral post-target delle evidenze, quando gli impegni AMR del 2030 dovrebbero essere valutabili dopo i ritardi di sorveglianza e pubblicazione. L’AMR è più ampia della resistenza agli antibiotici e nessuna di queste date predice un certo collasso terapeutico.',
                'Le titre est éditorial. La chaîne active est Pessimistic 2029 → Neutral 2032 → Optimistic 2036. Dans l’état actuel, le 31 décembre 2029 est le point de contrôle actif; le 31 décembre 2032 est le point de contrôle Neutral post-cible des données probantes, lorsque les engagements RAM de 2030 devraient pouvoir être évalués après les délais de surveillance et de publication. La RAM dépasse la seule résistance aux antibiotiques et aucune de ces dates ne prédit un effondrement certain des traitements.',
                'Der Titel ist redaktionell. Die aktive Kette lautet Pessimistic 2029 → Neutral 2032 → Optimistic 2036. Im aktuellen Stand ist der 31. Dezember 2029 der aktive Kontrollpunkt; der 31. Dezember 2032 ist der Neutral-Evidenzkontrollpunkt nach dem Zieljahr, an dem die AMR-Verpflichtungen für 2030 nach Überwachungs- und Veröffentlichungsverzug bewertbar sein sollten. AMR umfasst mehr als Antibiotikaresistenz, und keiner dieser Termine sagt einen sicheren Therapiezusammenbruch voraus.',
                'El título es editorial. La cadena activa es Pessimistic 2029 → Neutral 2032 → Optimistic 2036. En el estado actual, el 31 de diciembre de 2029 es el punto de control activo; el 31 de diciembre de 2032 es el punto de control Neutral de evidencia posterior al objetivo, cuando los compromisos de RAM de 2030 deberían poder evaluarse tras los retrasos de vigilancia y publicación. La RAM es más amplia que la resistencia a los antibióticos y ninguna de estas fechas predice un colapso terapéutico cierto.',
                'De titel is redactioneel. De actieve keten is Pessimistic 2029 → Neutral 2032 → Optimistic 2036. In de huidige toestand is 31 december 2029 het actieve controlepunt; 31 december 2032 is het Neutral-bewijscontrolepunt na de doelperiode, wanneer de AMR-toezeggingen voor 2030 beoordeelbaar moeten zijn na vertraging in surveillance en publicatie. AMR is breder dan antibioticaresistentie en geen van deze data voorspelt een zekere instorting van behandelingen.',
                'Titeln är redaktionell. Den aktiva kedjan är Pessimistic 2029 → Neutral 2032 → Optimistic 2036. I nuläget är den 31 december 2029 den aktiva kontrollpunkten; den 31 december 2032 är Neutral-evidenskontrollpunkten efter målåret, då AMR-åtagandena för 2030 bör kunna bedömas efter fördröjning i övervakning och publicering. AMR är bredare än antibiotikaresistens och inget av dessa datum förutsäger en säker behandlingskollaps.',
                'Tytuł ma charakter redakcyjny. Aktywny łańcuch to Pessimistic 2029 → Neutral 2032 → Optimistic 2036. W obecnym stanie 31 grudnia 2029 r. jest aktywnym punktem kontrolnym; 31 grudnia 2032 r. jest Neutralnym punktem oceny dowodów po terminie celu, gdy zobowiązania AMR na 2030 r. powinny być możliwe do oceny po opóźnieniach nadzoru i publikacji. AMR jest szersza niż oporność na antybiotyki i żadna z tych dat nie przewiduje pewnego załamania leczenia.',
            ),
            'causes' => $this->shared->tl(
                ['Excessive and inappropriate antimicrobial use in people and animals.', 'Unequal access to diagnostics, quality treatment and infection prevention.', 'Transmission through health care, communities, food systems and the environment.', 'A weak antibiotic, diagnostic and vaccine innovation pipeline.'],
                ['Uso eccessivo o inappropriato di antimicrobici in persone e animali.', 'Accesso diseguale a diagnosi, cure di qualità e prevenzione delle infezioni.', 'Trasmissione in sanità, comunità, sistemi alimentari e ambiente.', 'Pipeline debole per antibiotici, diagnostica e vaccini.'],
                ['Usage excessif ou inapproprié des antimicrobiens chez l’humain et l’animal.', 'Accès inégal au diagnostic, aux traitements de qualité et à la prévention.', 'Transmission dans les soins, les communautés, les systèmes alimentaires et l’environnement.', 'Pipeline faible pour antibiotiques, diagnostics et vaccins.'],
                ['Übermäßiger oder falscher Einsatz antimikrobieller Mittel bei Mensch und Tier.', 'Ungleicher Zugang zu Diagnostik, guter Behandlung und Infektionsprävention.', 'Übertragung in Versorgung, Gemeinschaften, Ernährungssystemen und Umwelt.', 'Schwache Pipeline für Antibiotika, Diagnostika und Impfstoffe.'],
                ['Uso excesivo o inadecuado de antimicrobianos en personas y animales.', 'Acceso desigual a diagnóstico, tratamiento de calidad y prevención.', 'Transmisión en sanidad, comunidades, sistemas alimentarios y medio ambiente.', 'Débil cartera de antibióticos, diagnósticos y vacunas.'],
                ['Overmatig of onjuist gebruik van antimicrobiële middelen bij mens en dier.', 'Ongelijke toegang tot diagnostiek, goede behandeling en infectiepreventie.', 'Verspreiding via zorg, gemeenschappen, voedselsystemen en milieu.', 'Zwakke pijplijn voor antibiotica, diagnostiek en vaccins.'],
                ['Överdriven eller felaktig användning av antimikrobiella medel hos människor och djur.', 'Ojämlik tillgång till diagnostik, kvalitetsbehandling och infektionsprevention.', 'Spridning i vård, samhällen, livsmedelssystem och miljö.', 'Svag pipeline för antibiotika, diagnostik och vaccin.'],
                ['Nadmierne lub niewłaściwe stosowanie środków przeciwdrobnoustrojowych u ludzi i zwierząt.', 'Nierówny dostęp do diagnostyki, dobrej terapii i zapobiegania zakażeniom.', 'Transmisja w ochronie zdrowia, społecznościach, żywności i środowisku.', 'Słaby rozwój antybiotyków, diagnostyki i szczepionek.'],
            ),
            'consequences' => $this->shared->tl(
                ['More infections become difficult or impossible to treat.', 'Routine surgery, cancer therapy and intensive care carry higher infection risk.', 'Deaths attributable to resistance and deaths associated with resistant infection rise through different causal pathways.', 'Health systems and households face longer illness, higher costs and wider inequality.'],
                ['Più infezioni diventano difficili o impossibili da curare.', 'Chirurgia, terapie oncologiche e terapia intensiva comportano maggior rischio infettivo.', 'I decessi attribuibili alla resistenza e quelli associati a infezioni resistenti aumentano attraverso percorsi causali distinti.', 'Sistemi sanitari e famiglie affrontano malattie più lunghe, costi maggiori e disuguaglianze.'],
                ['Davantage d’infections deviennent difficiles ou impossibles à traiter.', 'Chirurgie, cancérologie et soins intensifs comportent un risque infectieux accru.', 'Les décès attribuables à la résistance et ceux associés aux infections résistantes suivent des voies causales distinctes.', 'Systèmes de santé et ménages subissent maladies plus longues, coûts et inégalités.'],
                ['Mehr Infektionen werden schwer oder nicht behandelbar.', 'Operationen, Krebstherapie und Intensivmedizin tragen höhere Infektionsrisiken.', 'Auf Resistenz zurückführbare und mit resistenten Infektionen verbundene Todesfälle folgen unterschiedlichen Kausalwegen.', 'Gesundheitssysteme und Haushalte tragen längere Krankheit, höhere Kosten und Ungleichheit.'],
                ['Más infecciones se vuelven difíciles o imposibles de tratar.', 'Cirugía, cáncer y cuidados intensivos afrontan mayor riesgo de infección.', 'Las muertes atribuibles a la resistencia y las asociadas a infección resistente siguen vías causales distintas.', 'Sistemas sanitarios y hogares soportan enfermedad más larga, costes y desigualdad.'],
                ['Meer infecties worden moeilijk of niet behandelbaar.', 'Chirurgie, kankerzorg en intensive care krijgen een groter infectierisico.', 'Aan resistentie toe te schrijven en met resistente infectie samenhangende sterfte volgen verschillende causale paden.', 'Zorgstelsels en huishoudens dragen langere ziekte, hogere kosten en ongelijkheid.'],
                ['Fler infektioner blir svåra eller omöjliga att behandla.', 'Kirurgi, cancerbehandling och intensivvård får högre infektionsrisk.', 'Dödsfall hänförliga till resistens och dödsfall associerade med resistenta infektioner följer olika orsaksvägar.', 'Vårdsystem och hushåll möter längre sjukdom, högre kostnader och ojämlikhet.'],
                ['Więcej zakażeń staje się trudnych lub niemożliwych do leczenia.', 'Chirurgia, terapia nowotworów i intensywna opieka niosą większe ryzyko zakażeń.', 'Zgony przypisywane oporności i związane z zakażeniem opornym wynikają z odmiennych ścieżek przyczynowych.', 'Systemy zdrowia i rodziny ponoszą dłuższą chorobę, koszty i nierówności.'],
            ),
            'recommended_actions' => $this->shared->tl(
                ['Use antibiotics only with appropriate clinical guidance.', 'Expand diagnostics, vaccination, sanitation and infection prevention.', 'Track pathogen–drug combinations with consistent specimens, periods and denominators.', 'Fund stewardship, surveillance and sustainable access to effective antimicrobials.'],
                ['Usare antibiotici solo con appropriata guida clinica.', 'Ampliare diagnostica, vaccinazione, igiene e prevenzione delle infezioni.', 'Seguire combinazioni patogeno–farmaco con specimen, periodi e denominatori coerenti.', 'Finanziare stewardship, sorveglianza e accesso sostenibile ad antimicrobici efficaci.'],
                ['Utiliser les antibiotiques avec un encadrement clinique approprié.', 'Développer diagnostic, vaccination, assainissement et prévention.', 'Suivre les couples pathogène–médicament avec prélèvements, périodes et dénominateurs cohérents.', 'Financer bon usage, surveillance et accès durable aux antimicrobiens efficaces.'],
                ['Antibiotika nur mit geeigneter klinischer Anleitung einsetzen.', 'Diagnostik, Impfung, Hygiene und Infektionsprävention ausbauen.', 'Erreger–Wirkstoff-Kombinationen mit konsistenten Proben, Zeiträumen und Nennern verfolgen.', 'Stewardship, Überwachung und nachhaltigen Zugang zu wirksamen Mitteln finanzieren.'],
                ['Usar antibióticos con orientación clínica adecuada.', 'Ampliar diagnóstico, vacunación, saneamiento y prevención.', 'Seguir combinaciones patógeno–fármaco con muestras, periodos y denominadores coherentes.', 'Financiar optimización, vigilancia y acceso sostenible a antimicrobianos eficaces.'],
                ['Gebruik antibiotica alleen met passende klinische begeleiding.', 'Breid diagnostiek, vaccinatie, hygiëne en infectiepreventie uit.', 'Volg pathogeen–middelcombinaties met consistente monsters, perioden en noemers.', 'Financier stewardship, surveillance en duurzame toegang tot effectieve middelen.'],
                ['Använd antibiotika med lämplig klinisk vägledning.', 'Bygg ut diagnostik, vaccination, sanitet och infektionsprevention.', 'Följ patogen–läkemedelskombinationer med jämförbara prov, perioder och nämnare.', 'Finansiera stewardship, övervakning och hållbar tillgång till effektiva medel.'],
                ['Stosować antybiotyki wyłącznie z właściwym wskazaniem klinicznym.', 'Rozwijać diagnostykę, szczepienia, sanitację i zapobieganie zakażeniom.', 'Śledzić pary patogen–lek przy spójnych próbkach, okresach i mianownikach.', 'Finansować racjonalne użycie, nadzór i trwały dostęp do skutecznych leków.'],
            ),
            'severity' => CountdownSeverity::Critical,
            'status' => CountdownStatus::Active,
            'target_date' => CarbonImmutable::parse('2032-12-31 23:59:59', 'UTC'),
            'image_path' => 'images/doomsday/antibiotic_apocalypse.png',
            'sort_order' => 5,
            'is_published' => true,
        ];
    }
};
