<?php

declare(strict_types=1);

use App\Enums\ProjectionType;
use Carbon\CarbonImmutable;

$shared = require __DIR__.'/../_shared.php';

return new class($shared)
{
    public function __construct(private object $shared) {}

    /** @return array<int, array<string, mixed>> */
    public function projections(): array
    {
        $sources = $this->shared->sources();
        $commonMethodology = [
            'classification' => 'Editorial evidence checkpoints, not official forecasts, tipping-point dates or extinction dates.',
            'assessment_date' => '2026-07-11',
            'drivers' => ['habitat extent and condition', 'monitored population abundance', 'assessed extinction risk', 'protected and restored area quality', 'implementation and reporting capacity'],
            'policy_deadline' => '31 December 2030 is the editorial year-end representation of the Kunming–Montreal Global Biodiversity Framework’s by-2030 action deadline and the neutral timer horizon.',
            'measured_trajectory' => 'Protected Planet reported 17.6% terrestrial and inland-water coverage in 2024; the Living Planet Index reported a 73% average decline in monitored vertebrate populations from 1970 to 2020; Ramsar reported 22% wetland extent loss since 1970 and ongoing annual decline; FAO FRA 2025 still reports 10.9 million hectares of annual deforestation in 2015–2025; IUCN reported 61% of assessed bird species with declining populations in 2025.',
            'implementation_delay' => 'CBD implementation analysis recorded only 51 revised or updated national biodiversity strategies and action plans as of 31 May 2025, so reporting and policy adoption lag behind the 2030 timetable.',
            'ecological_threshold' => 'There is no single global ecological threshold that dates a mass-extinction event. The 2050 milestone uses the framework’s long-term vision for ecosystem integrity, resilience, connectivity, species survival and population recovery.',
            'baseline' => '17.6% global terrestrial and inland-water protected and conserved area coverage in the Protected Planet Report 2024.',
            'coverage_formula' => 'coverage_at_checkpoint = 17.6 + closure_fraction × (30.0 − 17.6).',
            'probability_note' => 'The required probability_score remains a secondary editorial weight of 25/50/25 and is not an empirical probability.',
        ];

        return [
            [
                'type' => ProjectionType::Optimistic,
                'target_date' => CarbonImmutable::parse('2050-12-31 23:59:59', 'UTC'),
                'title' => $this->shared->t(
                    en: '2050 ecological recovery horizon',
                    it: 'Orizzonte 2050 di recupero ecologico',
                    fr: 'Horizon 2050 de rétablissement écologique',
                    de: 'Ökologischer Erholungshorizont 2050',
                    es: 'Horizonte 2050 de recuperación ecológica',
                    nl: 'Ecologische herstelhorizon 2050',
                    sv: 'Ekologisk återhämtningshorisont 2050',
                    pl: 'Horyzont odbudowy ekologicznej 2050',
                ),
                'summary' => $this->shared->t(
                    en: 'The 2030 action deadline triggers sustained implementation and the 2050 outcome checkpoint can assess restored ecosystem integrity, lower extinction risk and healthier wild populations.',
                    it: 'La scadenza operativa 2030 attiva un’attuazione duratura e il checkpoint degli esiti 2050 può valutare integrità ecosistemica ripristinata, minore rischio di estinzione e popolazioni selvatiche più sane.',
                    fr: 'L’échéance d’action 2030 déclenche une mise en œuvre durable et le point d’évaluation 2050 peut mesurer une intégrité écosystémique restaurée, un risque d’extinction réduit et des populations sauvages plus saines.',
                    de: 'Die Handlungsfrist 2030 löst dauerhafte Umsetzung aus; der Ergebniszeitpunkt 2050 kann wiederhergestellte Ökosystemintegrität, geringeres Aussterberisiko und gesündere Wildpopulationen bewerten.',
                    es: 'La fecha de acción de 2030 activa una aplicación sostenida y el punto de resultados de 2050 puede evaluar la integridad restaurada de los ecosistemas, menor riesgo de extinción y poblaciones silvestres más sanas.',
                    nl: 'De actiedeadline van 2030 leidt tot blijvende uitvoering; het resultatencheckpoint in 2050 kan herstelde ecosysteemintegriteit, lager extinctierisico en gezondere wilde populaties beoordelen.',
                    sv: 'Handlingsfristen 2030 leder till varaktigt genomförande och resultatkontrollen 2050 kan bedöma återställd ekosystemintegritet, lägre utdöenderisk och friskare vilda populationer.',
                    pl: 'Termin działań 2030 uruchamia trwałe wdrażanie, a punkt oceny wyników 2050 może ocenić odbudowę integralności ekosystemów, niższe ryzyko wymarcia i zdrowsze dzikie populacje.',
                ),
                'confidence_score' => 45,
                'probability_score' => 25,
                'trend' => 'recovery-window',
                'methodology' => array_merge($commonMethodology, [
                    'milestone' => 'Kunming–Montreal Global Biodiversity Framework 2050 vision and Goal A ecological outcome horizon.',
                    'date_semantics' => 'The end-of-2050 anchor is the point for assessing whether long-term ecological outcomes are visible; crossing it without recovery invalidates this optimistic scenario but does not date a mass-extinction event.',
                    'reasoning' => 'The framework places ecosystem integrity, species survival and population recovery in its 2050 goals after the 2030 action mission. Because ecological response can lag policy implementation, the later scenario is tied to the official outcome horizon rather than a linear extension of the 2030 timer.',
                    'date_basis' => 'The official framework sets four long-term goals for 2050 after the 2030 mission; 31 December 2050 is an editorial year-end representation of that horizon, not a treaty-specified day.',
                    'closure_fraction' => 1.0,
                    'coverage_checkpoint' => '30.0% terrestrial and inland-water coverage is the charted area milestone; ecological success additionally requires effectiveness, connectivity, equitable governance, species survival and population recovery.',
                    'limits' => '2050 is a governance and recovery horizon, not a guarantee that all ecosystems recover by then. Recovery times vary by ecosystem and pressure.',
                    'stop_condition' => 'Rebase this scenario if the post-2030 framework changes Goal A, if comparable global indicators show continued deterioration through the 2030 review, or if the 30% area metric is not accompanied by quality and species outcomes.',
                    'sources' => [
                        $sources['cbd_gbf'],
                        $sources['cbd_nbsap_implementation_2025'],
                        $sources['protected_planet_report'],
                        $sources['living_planet_index'],
                        $sources['ramsar_gwo'],
                        $sources['fao_fra_2025'],
                        $sources['iucn_2025_birds'],
                    ],
                ]),
                'sort_order' => 1,
            ],
            [
                'type' => ProjectionType::Neutral,
                'target_date' => CarbonImmutable::parse('2030-12-31 23:59:59', 'UTC'),
                'title' => $this->shared->t(
                    en: '2030 policy deadline and final review',
                    it: 'Scadenza politica e verifica finale 2030',
                    fr: 'Échéance politique et revue finale 2030',
                    de: 'Politische Frist und Abschlussprüfung 2030',
                    es: 'Fecha política y revisión final 2030',
                    nl: 'Beleidsdeadline en eindbeoordeling 2030',
                    sv: 'Policyfrist och slutgranskning 2030',
                    pl: 'Termin polityczny i przegląd końcowy 2030',
                ),
                'summary' => $this->shared->t(
                    en: 'The Neutral policy and accountability checkpoint falls on 31 December 2030, after the active Pessimistic reporting checkpoint on 30 June 2029 and before the Optimistic ecological-recovery horizon on 31 December 2050. Progress must be judged against protection, restoration, habitat, population and extinction-risk evidence; the date does not identify a mass-extinction event.',
                    it: 'Il checkpoint Neutral di politica e responsabilità cade il 31 dicembre 2030, dopo il checkpoint Pessimistic attivo di reporting del 30 giugno 2029 e prima dell’orizzonte Optimistic di recupero ecologico del 31 dicembre 2050. Il progresso va valutato su protezione, ripristino, habitat, popolazioni e rischio di estinzione; la data non identifica un evento di estinzione di massa.',
                    fr: 'Le point Neutral de politique et de responsabilité tombe le 31 décembre 2030, après le point Pessimistic actif de rapport du 30 juin 2029 et avant l’horizon Optimistic de rétablissement écologique du 31 décembre 2050. Les progrès doivent être évalués à partir de la protection, de la restauration, des habitats, des populations et du risque d’extinction; la date n’identifie pas un événement d’extinction de masse.',
                    de: 'Der Neutral-Politik- und Rechenschaftspunkt liegt am 31. Dezember 2030, nach dem aktiven Pessimistic-Berichtspunkt am 30. Juni 2029 und vor dem Optimistic-Horizont der ökologischen Erholung am 31. Dezember 2050. Fortschritt muss anhand von Schutz, Wiederherstellung, Habitaten, Populationen und Aussterberisiko bewertet werden; das Datum bezeichnet kein Massenaussterbeereignis.',
                    es: 'El punto Neutral de política y rendición de cuentas cae el 31 de diciembre de 2030, después del punto Pessimistic activo de informes del 30 de junio de 2029 y antes del horizonte Optimistic de recuperación ecológica del 31 de diciembre de 2050. El progreso debe evaluarse con evidencia de protección, restauración, hábitats, poblaciones y riesgo de extinción; la fecha no identifica un evento de extinción masiva.',
                    nl: 'Het Neutral-beleids- en verantwoordingspunt valt op 31 december 2030, na het actieve Pessimistic-rapportagepunt op 30 juni 2029 en vóór de Optimistic-horizon voor ecologisch herstel op 31 december 2050. Vooruitgang moet worden beoordeeld met gegevens over bescherming, herstel, habitat, populaties en extinctierisico; de datum duidt geen massa-extinctiegebeurtenis aan.',
                    sv: 'Neutral-punkten för policy och ansvar infaller den 31 december 2030, efter den aktiva Pessimistic-rapporteringspunkten den 30 juni 2029 och före den Optimistic-horisonten för ekologisk återhämtning den 31 december 2050. Framsteg måste bedömas med evidens om skydd, restaurering, livsmiljöer, populationer och utdöenderisk; datumet anger ingen massutdöendehändelse.',
                    pl: 'Punkt Neutral dotyczący polityki i odpowiedzialności przypada 31 grudnia 2030 r., po aktywnym punkcie raportowym Pessimistic 30 czerwca 2029 r. i przed horyzontem Optimistic odbudowy ekologicznej 31 grudnia 2050 r. Postęp należy oceniać na podstawie ochrony, odtwarzania, siedlisk, populacji i ryzyka wymarcia; data nie oznacza zdarzenia masowego wymierania.',
                ),
                'confidence_score' => 80,
                'probability_score' => 50,
                'trend' => 'policy-review',
                'methodology' => array_merge($commonMethodology, [
                    'milestone' => 'Kunming–Montreal Global Biodiversity Framework 2030 action deadline and final implementation review horizon.',
                    'date_semantics' => 'The end-of-2030 anchor is the central accountability cutoff shown by the public timer; crossing it triggers assessment against the framework targets and does not signify ecological collapse.',
                    'reasoning' => 'This is the central scenario because the framework’s 23 action targets are due by 2030, the eighth national reports feed the final review, and recent habitat, population, extinction-risk and implementation indicators show that achievement cannot be inferred from area commitments alone.',
                    'date_basis' => 'The framework defines 23 action targets for completion by 2030 and a final global review based on the eighth national reports; 31 December 2030 is an editorial year-end representation of the by-2030 deadline.',
                    'closure_fraction' => 0.5,
                    'coverage_checkpoint' => '23.8% terrestrial and inland-water coverage if half of the 2024 gap to 30% closes.',
                    'limits' => 'The date measures policy accountability, not an ecological tipping point. National reports and global indicators have reporting lags and unequal taxonomic and geographic coverage.',
                    'stop_condition' => 'If the 2030 review confirms that targets were missed and habitat, population or extinction-risk indicators are still worsening, do not silently extend this timer; require an approved successor framework and new baseline.',
                    'sources' => [
                        $sources['cbd_gbf'],
                        $sources['cbd_national_reports'],
                        $sources['cbd_global_review_2026'],
                        $sources['cbd_nbsap_implementation_2025'],
                        $sources['protected_planet_report'],
                        $sources['living_planet_index'],
                        $sources['ramsar_gwo'],
                        $sources['fao_fra_2025'],
                        $sources['iucn_2025_birds'],
                    ],
                ]),
                'sort_order' => 2,
            ],
            [
                'type' => ProjectionType::Pessimistic,
                'target_date' => CarbonImmutable::parse('2029-06-30 23:59:59', 'UTC'),
                'title' => $this->shared->t(
                    en: '2029 last pre-deadline evidence checkpoint',
                    it: 'Ultimo checkpoint di evidenza pre-scadenza 2029',
                    fr: 'Dernier point probant avant échéance en 2029',
                    de: 'Letzter Evidenzpunkt vor der Frist 2029',
                    es: 'Último punto de evidencia antes de la fecha límite en 2029',
                    nl: 'Laatste bewijscheckpoint vóór de deadline in 2029',
                    sv: 'Sista evidenskontroll före tidsfristen 2029',
                    pl: 'Ostatni punkt dowodowy przed terminem w 2029 r.',
                ),
                'summary' => $this->shared->t(
                    en: 'The eighth national-report deadline is the last formal evidence submission before the 2030 review; continued decline at that point leaves little time for credible course correction.',
                    it: 'La scadenza dell’ottavo rapporto nazionale è l’ultimo invio formale di evidenze prima della verifica 2030; un declino ancora in corso lascia poco tempo per una correzione credibile.',
                    fr: 'La date limite du huitième rapport national est le dernier dépôt formel de preuves avant la revue 2030; un déclin persistant laisse peu de temps pour une correction crédible.',
                    de: 'Die Frist für den achten nationalen Bericht ist die letzte formale Evidenzeinreichung vor der Prüfung 2030; anhaltender Rückgang lässt dann kaum Zeit für glaubwürdige Kurskorrekturen.',
                    es: 'La fecha límite del octavo informe nacional es la última entrega formal de evidencia antes de la revisión de 2030; un deterioro persistente deja poco tiempo para corregir el rumbo.',
                    nl: 'De deadline van het achtste nationale rapport is de laatste formele bewijslevering vóór de beoordeling van 2030; aanhoudende achteruitgang laat dan weinig tijd voor geloofwaardige bijsturing.',
                    sv: 'Tidsfristen för den åttonde nationella rapporten är den sista formella evidensinlämningen före granskningen 2030; fortsatt försämring lämnar då liten tid för trovärdig kursändring.',
                    pl: 'Termin ósmego raportu krajowego jest ostatnim formalnym przekazaniem dowodów przed przeglądem 2030; dalsze pogorszenie pozostawia niewiele czasu na wiarygodną korektę kursu.',
                ),
                'confidence_score' => 85,
                'probability_score' => 25,
                'trend' => 'early-warning',
                'methodology' => array_merge($commonMethodology, [
                    'milestone' => 'Eighth national reports due before the final 2030 global review.',
                    'date_semantics' => '30 June 2029 is the exact formal reporting deadline and the earliest defensible evidence cutoff; crossing it with continued deterioration leaves little time for credible correction before 2030 but is not an ecological threshold.',
                    'reasoning' => 'This earlier scenario uses an exact CBD reporting date rather than an invented ecological day. It becomes pessimistic when the last comprehensive pre-deadline evidence still shows weak implementation and worsening habitat, population or extinction-risk indicators.',
                    'date_basis' => 'CBD decision 15/6 requests eighth national reports by 30 June 2029.',
                    'closure_fraction' => 0.2,
                    'coverage_checkpoint' => '20.08% terrestrial and inland-water coverage if only one fifth of the 2024 gap to 30% closes.',
                    'limits' => 'Reporting completion does not prove ecological recovery, and late or incomplete reports can understate implementation gaps.',
                    'stop_condition' => 'Escalate to the pessimistic interpretation if the eighth reports do not show sustained improvement in habitat extent or condition, monitored populations, extinction risk and implementation capacity.',
                    'sources' => [
                        $sources['cbd_national_reports'],
                        $sources['cbd_global_review_2026'],
                        $sources['cbd_cop17_review'],
                        $sources['cbd_nbsap_implementation_2025'],
                        $sources['protected_planet_report'],
                        $sources['living_planet_index'],
                        $sources['ramsar_gwo'],
                        $sources['fao_fra_2025'],
                        $sources['iucn_2025_birds'],
                    ],
                ]),
                'sort_order' => 3,
            ],
        ];
    }
};
