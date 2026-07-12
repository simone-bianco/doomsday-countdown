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
            'classification' => 'editorial temporal scenario',
            'assessed_at' => '2026-07-11',
            'assumption' => 'The dates are operational assessment windows, not epidemiological forecasts or dates of sudden treatment collapse.',
            'probability_note' => 'Probability scores are secondary editorial UI weights; they do not determine the target dates.',
            'baseline' => 'Evidence available by mid-2026, principally surveillance years 2023 and 2024 plus 2025 pipeline reports.',
            'drivers' => [
                'comparable pathogen–drug resistance trends',
                'total antibiotic consumption and Access share',
                'infection prevention and funded national action plans',
                'diagnostic availability and access',
                'innovative antibacterial approvals and equitable deployment',
            ],
            'source_facts' => [
                'WHO reported resistance rising in more than 40% of monitored pathogen–antibiotic combinations between 2018 and 2023, with average annual increases of 5–15%.',
                'ECDC reported 2024 EU total antibiotic consumption 2% above the 2019 baseline, Access share 4.7 percentage points below its 2030 target, and carbapenem-resistant K. pneumoniae bloodstream-infection incidence 61% above 2019.',
                'WHO counted 90 clinical antibacterial candidates in 2025; only 15 qualified as innovative and only five were active against at least one critical-priority bacterium.',
                'WHO identified diagnostic priorities that could become available across settings in the next three to five years from 2025, but availability is not the same as equitable deployment.',
                'The 2024 global burden study forecast 1.91 million attributable and 8.22 million associated bacterial-AMR deaths in 2050 under its reference scenario; the definitions remain separate.',
            ],
        ];

        return [
            [
                'type' => ProjectionType::Optimistic,
                'target_date' => CarbonImmutable::parse('2036-12-31 23:59:59', 'UTC'),
                'title' => $this->shared->t('2036 — coordinated recovery window', '2036 — finestra di recupero coordinato', '2036 — fenêtre de redressement coordonné', '2036 — Zeitfenster koordinierter Erholung', '2036 — ventana de recuperación coordinada', '2036 — venster voor gecoördineerd herstel', '2036 — fönster för samordnad återhämtning', '2036 — okno skoordynowanej poprawy'),
                'summary' => $this->shared->t('The extended window matches the WHO 2026–2036 action plan and allows a decade for funded prevention, diagnostics, access and innovation to change outcomes.', 'La finestra estesa coincide con il piano WHO 2026–2036 e concede un decennio a prevenzione finanziata, diagnostica, accesso e innovazione per cambiare gli esiti.', 'La fenêtre étendue correspond au plan OMS 2026–2036 et laisse une décennie à la prévention financée, au diagnostic, à l’accès et à l’innovation pour modifier les résultats.', 'Das erweiterte Zeitfenster entspricht dem WHO-Plan 2026–2036 und gibt finanzierter Prävention, Diagnostik, Zugang und Innovation ein Jahrzehnt, um Ergebnisse zu verändern.', 'La ventana ampliada coincide con el plan OMS 2026–2036 y concede una década a prevención financiada, diagnóstico, acceso e innovación para cambiar los resultados.', 'Het verlengde venster volgt het WHO-plan 2026–2036 en geeft gefinancierde preventie, diagnostiek, toegang en innovatie een decennium om uitkomsten te veranderen.', 'Det förlängda fönstret följer WHO-planen 2026–2036 och ger finansierad prevention, diagnostik, tillgång och innovation ett årtionde att förändra utfallet.', 'Wydłużone okno odpowiada planowi WHO 2026–2036 i daje dekadę finansowanej profilaktyce, diagnostyce, dostępowi i innowacjom na zmianę wyników.'),
                'confidence_score' => 58,
                'probability_score' => 30,
                'trend' => 'improving',
                'methodology' => array_merge($commonMethodology, [
                    'scenario' => 'optimistic',
                    'target_evaluation_date' => '2036-12-31T23:59:59Z',
                    'semantics' => 'Passing this anchor means the WHO 2026–2036 implementation window is complete and the recovery scenario must be reassessed. It does not mean AMR is solved or that treatment has collapsed.',
                    'milestone' => 'End of the WHO Global Action Plan on AMR 2026–2036 implementation window.',
                    'date_reasoning' => 'The World Health Assembly adopted a coordinated One Health framework through 2036. This later date allows time for funded national plans, prevention, surveillance, 2025 diagnostic priorities and genuinely innovative agents to reach routine use with stewardship and equitable access.',
                    'limits' => [
                        'The 2036 plan horizon is a governance window, not evidence that resistance will be controlled by that date.',
                        'Pipeline candidates can fail, arrive late or remain inaccessible; candidate counts are not expected approvals.',
                        'Regional gains may coexist with worsening pathogen–drug combinations elsewhere.',
                    ],
                    'stop_conditions' => [
                        'Move the date earlier only after sustained multi-region declines in comparable resistance and burden indicators plus improved access.',
                        'Move the date later if financing, diagnostic deployment or innovative treatment access materially misses the 2026–2036 implementation milestones.',
                        'Replace the horizon if WHO formally revises or supersedes the 2026–2036 action plan.',
                    ],
                    'sources' => [$sources['who_gap_2026'], $sources['un_amr_declaration_2024'], $sources['who_pipeline_2025'], $sources['who_diagnostics_2025'], $sources['oecd_one_health'], $sources['gram_burden_2021']],
                ]),
                'sort_order' => 1,
            ],
            [
                'type' => ProjectionType::Neutral,
                'target_date' => CarbonImmutable::parse('2032-12-31 23:59:59', 'UTC'),
                'title' => $this->shared->t('2032 — post-target evidence window', '2032 — finestra di evidenza post-target', '2032 — fenêtre de preuve post-cible', '2032 — Evidenzfenster nach den Zieljahren', '2032 — ventana de evidencia posterior a los objetivos', '2032 — bewijsvenster na de doelstellingen', '2032 — evidensfönster efter målen', '2032 — okno danych po terminie celów'),
                'summary' => $this->shared->t('The main date allows 2030 commitments to appear in comparable surveillance after publication lag while current resistance, consumption and pipeline gaps remain unresolved.', 'La data principale consente agli impegni 2030 di emergere nella sorveglianza comparabile dopo i ritardi di pubblicazione, mentre restano aperti i gap di resistenza, consumo e pipeline.', 'La date principale permet aux engagements de 2030 d’apparaître dans une surveillance comparable après les délais de publication, alors que persistent les lacunes de résistance, consommation et pipeline.', 'Das Hauptdatum lässt Ergebnisse der Ziele 2030 nach dem Veröffentlichungsverzug in vergleichbarer Überwachung sichtbar werden, während Resistenz-, Verbrauchs- und Pipeline-Lücken offen bleiben.', 'La fecha principal permite que los compromisos de 2030 aparezcan en vigilancia comparable tras el retraso de publicación, mientras persisten brechas de resistencia, consumo y cartera.', 'De hoofddatum laat resultaten van de doelen voor 2030 na publicatievertraging zichtbaar worden in vergelijkbare surveillance, terwijl lacunes in resistentie, gebruik en pijplijn blijven bestaan.', 'Huvuddatumet låter 2030-åtaganden synas i jämförbar övervakning efter publiceringsfördröjning, medan luckor i resistens, användning och pipeline kvarstår.', 'Główna data pozwala zobaczyć zobowiązania 2030 w porównywalnym nadzorze po opóźnieniu publikacji, gdy nadal istnieją luki w oporności, zużyciu i rozwoju leków.'),
                'confidence_score' => 66,
                'probability_score' => 45,
                'trend' => 'rising',
                'methodology' => array_merge($commonMethodology, [
                    'scenario' => 'neutral',
                    'target_evaluation_date' => '2032-12-31T23:59:59Z',
                    'semantics' => 'Passing this anchor means the first planned post-2030 evidence review is due and the timer must be reassessed against published outcomes. It does not declare a treatment collapse.',
                    'milestone' => 'First robust post-2030 assessment window using comparable surveillance and implementation evidence.',
                    'date_reasoning' => 'WHO published its 2025 global report primarily from 2023 surveillance and ECDC published 2024 EU data in late 2025. Using that observed one-to-two-year reporting lag, end-2032 is an editorial inference for when 2030 mortality, Access, consumption, resistance, diagnostic-capacity and funded-plan commitments should be assessable with more than a partial year.',
                    'limits' => [
                        'Reporting lag differs by country, pathogen, specimen and surveillance system; complete global 2030 data are not guaranteed by 2032.',
                        'The date evaluates whether the curve bent; it does not convert a composite index into a mortality threshold.',
                        'The 2050 burden forecast provides long-range direction, not a validated event date in 2032.',
                    ],
                    'stop_conditions' => [
                        'Move earlier if sufficiently complete and comparable 2030 outcome datasets are published before 2032.',
                        'Move later if coverage, denominator changes or publication delays prevent a defensible post-target assessment.',
                        'Recalculate if UN or WHO replaces the 2030 commitments with materially different definitions.',
                    ],
                    'sources' => [$sources['who_report_2025'], $sources['ecdc_ears_2024'], $sources['ecdc_esac_2024'], $sources['un_amr_declaration_2024'], $sources['who_diagnostics_2025'], $sources['gram_burden_2021'], $sources['oecd_one_health']],
                ]),
                'sort_order' => 2,
            ],
            [
                'type' => ProjectionType::Pessimistic,
                'target_date' => CarbonImmutable::parse('2029-12-31 23:59:59', 'UTC'),
                'title' => $this->shared->t('2029 — early treatment-buffer warning', '2029 — allerta anticipata sul margine terapeutico', '2029 — alerte précoce sur la marge thérapeutique', '2029 — frühe Warnung zum Therapiepuffer', '2029 — alerta temprana sobre el margen terapéutico', '2029 — vroege waarschuwing voor behandelbuffer', '2029 — tidig varning för behandlingsmarginal', '2029 — wczesne ostrzeżenie o rezerwie leczenia'),
                'summary' => $this->shared->t('If resistance, consumption gaps and the thin innovation pipeline persist, the operational warning window arrives before the 2030 targets can be rescued.', 'Se persistono resistenza, gap nei consumi e pipeline innovativa debole, la finestra di allerta operativa arriva prima che i target 2030 possano essere recuperati.', 'Si la résistance, les écarts de consommation et la faible pipeline d’innovation persistent, la fenêtre d’alerte opérationnelle arrive avant que les objectifs 2030 puissent être rattrapés.', 'Wenn Resistenz, Verbrauchslücken und die dünne Innovationspipeline fortbestehen, beginnt das operative Warnfenster, bevor die Ziele 2030 noch aufgeholt werden können.', 'Si persisten la resistencia, las brechas de consumo y la escasa innovación, la ventana de alerta operativa llega antes de poder recuperar los objetivos de 2030.', 'Als resistentie, gebruikslacunes en de dunne innovatiepijplijn aanhouden, begint het operationele waarschuwingsvenster voordat de doelen voor 2030 nog kunnen worden ingehaald.', 'Om resistens, användningsgap och den tunna innovationspipelinen består kommer varningsfönstret innan 2030-målen kan återhämtas.', 'Jeśli utrzymają się oporność, luki w zużyciu i słaby rozwój innowacji, okno ostrzegawcze nadejdzie, zanim cele 2030 będzie można nadrobić.'),
                'confidence_score' => 52,
                'probability_score' => 25,
                'trend' => 'rising',
                'methodology' => array_merge($commonMethodology, [
                    'scenario' => 'pessimistic',
                    'target_evaluation_date' => '2029-12-31T23:59:59Z',
                    'semantics' => 'Passing this anchor means the early-warning window has closed and the feasibility of rescuing 2030 outcomes must be reassessed. It does not mean every antibiotic has failed.',
                    'milestone' => 'End-2029 operational lock-in check, one year before the principal 2030 commitments.',
                    'date_reasoning' => 'WHO observed 5–15% average annual resistance increases in more than 40% of monitored combinations from 2018 to 2023. ECDC 2024 data show consumption, Access share and two resistant bloodstream-infection targets off track, while WHO found only 15 innovative clinical candidates and five active against a critical-priority bacterium. By late 2029, the 2025 diagnostic three-to-five-year window has elapsed and late corrective action cannot retroactively deliver 2030 outcomes.',
                    'limits' => [
                        'Global WHO trends and EU target indicators use different populations and are evidence strands, not one combined rate.',
                        'The warning date does not mean antibiotics cease working suddenly or everywhere.',
                        'A pipeline snapshot cannot establish which products will be approved, accessible or clinically useful.',
                    ],
                    'stop_conditions' => [
                        'Move later if comparable surveillance shows sustained reversal and consumption plus Access targets return credibly on track before 2029.',
                        'Move earlier only if repeated comparable cycles document accelerated resistance together with loss of effective treatment options.',
                        'Suspend the date comparison if pathogen, drug, specimen, denominator or coverage definitions change materially.',
                    ],
                    'sources' => [$sources['who_report_2025'], $sources['ecdc_ears_2024'], $sources['ecdc_esac_2024'], $sources['who_pipeline_2025'], $sources['who_diagnostics_2025'], $sources['un_amr_declaration_2024']],
                ]),
                'sort_order' => 3,
            ],
        ];
    }
};
