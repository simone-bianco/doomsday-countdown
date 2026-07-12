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
            'slug' => 'sixth-mass-extinction',
            'title' => $this->shared->t(
                en: 'The Sixth Mass Extinction',
                it: 'La sesta estinzione di massa',
                fr: 'La sixième extinction de masse',
                de: 'Das sechste Massenaussterben',
                es: 'La sexta extinción masiva',
                nl: 'De zesde massa-extinctie',
                sv: 'Det sjätte massutdöendet',
                pl: 'Szóste masowe wymieranie',
            ),
            'summary' => $this->shared->t(
                en: 'A staged evidence horizon: 2029 reporting, 2030 policy review and 2050 ecological recovery',
                it: 'Un orizzonte di evidenze a tappe: reporting 2029, verifica politica 2030 e recupero ecologico 2050',
                fr: 'Un horizon probant par étapes: rapports 2029, revue politique 2030 et rétablissement écologique 2050',
                de: 'Ein gestufter Evidenzhorizont: Berichte 2029, politische Prüfung 2030 und ökologische Erholung 2050',
                es: 'Un horizonte de evidencia por etapas: informes 2029, revisión política 2030 y recuperación ecológica 2050',
                nl: 'Een gefaseerde bewijshorizon: rapportage 2029, beleidsreview 2030 en ecologisch herstel 2050',
                sv: 'En etappindelad evidenshorisont: rapportering 2029, policygranskning 2030 och ekologisk återhämtning 2050',
                pl: 'Etapowy horyzont dowodowy: raportowanie 2029, przegląd polityki 2030 i odbudowa ekologiczna 2050',
            ),
            'description' => $this->shared->t(
                en: 'The active chain starts with the exact Pessimistic reporting checkpoint on 30 June 2029, advances to the Neutral policy and accountability checkpoint on 31 December 2030, and reaches the Optimistic ecological-recovery horizon on 31 December 2050. Habitat condition, monitored populations and assessed extinction risk must be read together; none of these dates identifies a mass-extinction event.',
                it: 'La catena attiva parte dal checkpoint Pessimistic esatto di reporting del 30 giugno 2029, prosegue con il checkpoint Neutral di politica e responsabilità del 31 dicembre 2030 e raggiunge l’orizzonte Optimistic di recupero ecologico del 31 dicembre 2050. Condizione degli habitat, popolazioni monitorate e rischio di estinzione valutato vanno letti insieme; nessuna di queste date identifica un evento di estinzione di massa.',
                fr: 'La chaîne active commence par le point Pessimistic exact de rapport du 30 juin 2029, passe par le point Neutral de politique et de responsabilité du 31 décembre 2030, puis atteint l’horizon Optimistic de rétablissement écologique du 31 décembre 2050. L’état des habitats, les populations suivies et le risque d’extinction évalué doivent être lus ensemble; aucune de ces dates n’identifie un événement d’extinction de masse.',
                de: 'Die aktive Kette beginnt mit dem exakten Pessimistic-Berichtspunkt am 30. Juni 2029, führt zum Neutral-Politik- und Rechenschaftspunkt am 31. Dezember 2030 und erreicht den Optimistic-Horizont der ökologischen Erholung am 31. Dezember 2050. Habitatqualität, beobachtete Populationen und bewertetes Aussterberisiko müssen gemeinsam gelesen werden; keines dieser Daten bezeichnet ein Massenaussterbeereignis.',
                es: 'La cadena activa comienza con el punto Pessimistic exacto de informes del 30 de junio de 2029, continúa con el punto Neutral de política y rendición de cuentas del 31 de diciembre de 2030 y llega al horizonte Optimistic de recuperación ecológica del 31 de diciembre de 2050. La condición de los hábitats, las poblaciones monitorizadas y el riesgo de extinción evaluado deben leerse conjuntamente; ninguna de estas fechas identifica un evento de extinción masiva.',
                nl: 'De actieve keten begint met het exacte Pessimistic-rapportagepunt op 30 juni 2029, gaat door naar het Neutral-beleids- en verantwoordingspunt op 31 december 2030 en bereikt de Optimistic-horizon voor ecologisch herstel op 31 december 2050. Habitatconditie, gemonitorde populaties en beoordeeld extinctierisico moeten samen worden gelezen; geen van deze data duidt een massa-extinctiegebeurtenis aan.',
                sv: 'Den aktiva kedjan börjar med den exakta Pessimistic-rapporteringspunkten den 30 juni 2029, fortsätter till Neutral-punkten för policy och ansvar den 31 december 2030 och når den Optimistic-horisonten för ekologisk återhämtning den 31 december 2050. Livsmiljöernas tillstånd, övervakade populationer och bedömd utdöenderisk måste läsas tillsammans; inget av dessa datum anger en massutdöendehändelse.',
                pl: 'Aktywny łańcuch zaczyna się od dokładnego punktu raportowego Pessimistic 30 czerwca 2029 r., przechodzi do punktu Neutral dotyczącego polityki i odpowiedzialności 31 grudnia 2030 r., a następnie osiąga horyzont Optimistic odbudowy ekologicznej 31 grudnia 2050 r. Stan siedlisk, monitorowane populacje i ocenione ryzyko wymarcia należy czytać łącznie; żadna z tych dat nie oznacza zdarzenia masowego wymierania.',
            ),
            'causes' => $this->shared->tl(
                en: ['Land- and sea-use change that removes or fragments habitat.', 'Direct exploitation of wild organisms and unsustainable harvest.', 'Climate change, pollution and invasive alien species acting together.', 'Insufficient finance, governance and implementation of agreed biodiversity targets.'],
                it: ['Cambiamenti nell’uso di terre e mari che eliminano o frammentano gli habitat.', 'Sfruttamento diretto degli organismi selvatici e prelievo non sostenibile.', 'Cambiamento climatico, inquinamento e specie aliene invasive che agiscono insieme.', 'Finanza, governance e attuazione insufficienti degli obiettivi concordati.'],
                fr: ['Changements d’usage des terres et des mers qui suppriment ou fragmentent les habitats.', 'Exploitation directe des organismes sauvages et prélèvements non durables.', 'Changement climatique, pollution et espèces exotiques envahissantes agissant ensemble.', 'Financement, gouvernance et mise en œuvre insuffisants des objectifs convenus.'],
                de: ['Land- und Meeresnutzungsänderungen, die Lebensräume beseitigen oder zerschneiden.', 'Direkte Nutzung wildlebender Organismen und nicht nachhaltige Entnahme.', 'Klimawandel, Verschmutzung und invasive gebietsfremde Arten in Kombination.', 'Unzureichende Finanzierung, Governance und Umsetzung vereinbarter Ziele.'],
                es: ['Cambios en el uso de la tierra y el mar que eliminan o fragmentan hábitats.', 'Explotación directa de organismos silvestres y extracción no sostenible.', 'Cambio climático, contaminación y especies exóticas invasoras actuando conjuntamente.', 'Financiación, gobernanza y aplicación insuficientes de los objetivos acordados.'],
                nl: ['Veranderingen in land- en zeegebruik die leefgebied verwijderen of versnipperen.', 'Directe exploitatie van wilde organismen en niet-duurzame oogst.', 'Klimaatverandering, vervuiling en invasieve uitheemse soorten die samen optreden.', 'Onvoldoende financiering, bestuur en uitvoering van afgesproken doelen.'],
                sv: ['Förändrad mark- och havsanvändning som tar bort eller splittrar livsmiljöer.', 'Direkt exploatering av vilda organismer och ohållbart uttag.', 'Klimatförändring, föroreningar och invasiva främmande arter som samverkar.', 'Otillräcklig finansiering, styrning och genomförande av överenskomna mål.'],
                pl: ['Zmiany użytkowania lądów i mórz usuwające lub fragmentujące siedliska.', 'Bezpośrednia eksploatacja dzikich organizmów i niezrównoważone pozyskiwanie.', 'Łączne działanie zmiany klimatu, zanieczyszczeń i inwazyjnych gatunków obcych.', 'Niewystarczające finansowanie, zarządzanie i wdrażanie uzgodnionych celów.'],
            ),
            'consequences' => $this->shared->tl(
                en: ['Declining monitored wildlife populations and rising extinction risk in assessed groups.', 'Loss of ecosystem functions that support food, water, health and climate resilience.', 'Greater vulnerability for Indigenous peoples and local communities dependent on intact ecosystems.', 'A widening implementation gap against the 2030 conservation and restoration framework.'],
                it: ['Declino delle popolazioni selvatiche monitorate e aumento del rischio nei gruppi valutati.', 'Perdita di funzioni ecosistemiche che sostengono cibo, acqua, salute e resilienza climatica.', 'Maggiore vulnerabilità per popoli indigeni e comunità locali dipendenti da ecosistemi integri.', 'Ampliamento del divario di attuazione rispetto al quadro 2030.'],
                fr: ['Déclin des populations sauvages suivies et hausse du risque dans les groupes évalués.', 'Perte de fonctions écosystémiques soutenant alimentation, eau, santé et résilience climatique.', 'Vulnérabilité accrue des peuples autochtones et communautés locales dépendant d’écosystèmes intacts.', 'Élargissement de l’écart de mise en œuvre par rapport au cadre 2030.'],
                de: ['Rückgang beobachteter Wildtierpopulationen und steigendes Risiko in bewerteten Gruppen.', 'Verlust von Ökosystemfunktionen für Nahrung, Wasser, Gesundheit und Klimaresilienz.', 'Höhere Verwundbarkeit indigener Völker und lokaler Gemeinschaften, die von intakten Ökosystemen abhängen.', 'Wachsende Umsetzungslücke gegenüber dem Rahmen für 2030.'],
                es: ['Descenso de poblaciones silvestres monitoreadas y mayor riesgo en grupos evaluados.', 'Pérdida de funciones ecosistémicas que sostienen alimentos, agua, salud y resiliencia climática.', 'Mayor vulnerabilidad de pueblos indígenas y comunidades locales dependientes de ecosistemas intactos.', 'Aumento de la brecha de aplicación respecto del marco de 2030.'],
                nl: ['Afnemende gemonitorde populaties en stijgend risico in beoordeelde groepen.', 'Verlies van ecosysteemfuncties voor voedsel, water, gezondheid en klimaatbestendigheid.', 'Grotere kwetsbaarheid voor inheemse volken en lokale gemeenschappen die van intacte ecosystemen afhangen.', 'Een groeiende uitvoeringskloof ten opzichte van het kader voor 2030.'],
                sv: ['Minskande övervakade populationer och ökande risk i bedömda grupper.', 'Förlust av ekosystemfunktioner som stödjer mat, vatten, hälsa och klimatresiliens.', 'Ökad sårbarhet för urfolk och lokalsamhällen som är beroende av intakta ekosystem.', 'Ett växande genomförandegap mot ramverket för 2030.'],
                pl: ['Spadek monitorowanych populacji i wzrost ryzyka w ocenionych grupach.', 'Utrata funkcji ekosystemów wspierających żywność, wodę, zdrowie i odporność klimatyczną.', 'Większa podatność ludów rdzennych i społeczności lokalnych zależnych od nienaruszonych ekosystemów.', 'Rosnąca luka wdrożeniowa względem ram na 2030 r.'],
            ),
            'recommended_actions' => $this->shared->tl(
                en: ['Track coverage and ecological quality, not area totals alone.', 'Accelerate effective restoration of degraded terrestrial, inland-water, coastal and marine ecosystems.', 'Use taxonomically explicit indicators and fund repeated assessments.', 'Protect Indigenous and community rights while expanding connected conservation systems.'],
                it: ['Seguire copertura e qualità ecologica, non soltanto le superfici totali.', 'Accelerare il ripristino efficace degli ecosistemi degradati terrestri, d’acqua interna, costieri e marini.', 'Usare indicatori tassonomicamente espliciti e finanziare valutazioni ripetute.', 'Proteggere i diritti indigeni e comunitari ampliando sistemi di conservazione connessi.'],
                fr: ['Suivre la couverture et la qualité écologique, pas seulement les superficies totales.', 'Accélérer la restauration effective des écosystèmes terrestres, d’eaux intérieures, côtiers et marins dégradés.', 'Utiliser des indicateurs taxonomiquement explicites et financer des évaluations répétées.', 'Protéger les droits autochtones et communautaires en étendant des systèmes de conservation connectés.'],
                de: ['Abdeckung und ökologische Qualität verfolgen, nicht nur Flächensummen.', 'Wirksame Wiederherstellung degradierter Land-, Binnengewässer-, Küsten- und Meeresökosysteme beschleunigen.', 'Taxonomisch eindeutige Indikatoren verwenden und wiederholte Bewertungen finanzieren.', 'Indigene und gemeinschaftliche Rechte beim Ausbau vernetzter Schutzsysteme sichern.'],
                es: ['Seguir la cobertura y la calidad ecológica, no solo la superficie total.', 'Acelerar la restauración efectiva de ecosistemas terrestres, de aguas continentales, costeros y marinos degradados.', 'Usar indicadores taxonómicamente explícitos y financiar evaluaciones repetidas.', 'Proteger derechos indígenas y comunitarios al ampliar sistemas de conservación conectados.'],
                nl: ['Volg dekking én ecologische kwaliteit, niet alleen totale oppervlakte.', 'Versnel effectief herstel van aangetaste land-, binnenwater-, kust- en mariene ecosystemen.', 'Gebruik taxonomisch expliciete indicatoren en financier herhaalde beoordelingen.', 'Bescherm inheemse en gemeenschapsrechten bij uitbreiding van verbonden beschermingssystemen.'],
                sv: ['Följ både täckning och ekologisk kvalitet, inte enbart total areal.', 'Påskynda effektiv restaurering av skadade land-, inlandsvatten-, kust- och havsekosystem.', 'Använd taxonomiskt tydliga indikatorer och finansiera återkommande bedömningar.', 'Skydda urfolks och lokalsamhällens rättigheter när sammanhängande skyddssystem byggs ut.'],
                pl: ['Śledzić pokrycie i jakość ekologiczną, a nie tylko łączną powierzchnię.', 'Przyspieszyć skuteczne odtwarzanie zdegradowanych ekosystemów lądowych, śródlądowych, przybrzeżnych i morskich.', 'Stosować wskaźniki o jawnej bazie taksonomicznej i finansować powtarzane oceny.', 'Chronić prawa ludów rdzennych i społeczności przy rozszerzaniu połączonych systemów ochrony.'],
            ),
            'severity' => CountdownSeverity::Critical,
            'status' => CountdownStatus::Active,
            'target_date' => CarbonImmutable::parse('2030-12-31 23:59:59', 'UTC'),
            'image_path' => 'images/doomsday/uninhabitable_earth_separate.png',
            'sort_order' => 4,
            'is_published' => true,
        ];
    }
};
