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
        $methodology = [
            'assumption' => 'Editorial risk estimate as of 2026-07-11; exact dates are scenario anchors, not predictions of an invasion order.',
            'date_semantics' => 'Pessimistic uses the end-2027 PLA capability milestone; neutral uses Taiwan’s 2028 presidential transition as the next political inflection point; optimistic assumes deterrence holds through the following full presidential cycle.',
            'default_selection' => 'At a UTC request time, the public producer selects the nearest future Pessimistic projection first, then Neutral, Optimistic and Other. The exact target second remains active; rollover occurs only after it passes. If none is future, the producer uses the deterministic expired fallback, and Countdown.target_date is used only when no projection exists.',
            'probability_score_note' => 'Scores are editorial scenario weights and are not official or empirical cumulative probabilities.',
            'drivers' => ['PLA end-2027 capability milestone', 'no fixed invasion timeline in the 2026 U.S. intelligence assessment', '2028 Taiwan presidential transition', 'quarantine and blockade alternatives', 'PLA command disruption and readiness', 'Taiwan civil resilience', 'allied deterrence'],
            'sources' => [
                $this->shared->sources()['dod'],
                $this->shared->sources()['dni_ata_2026'],
                $this->shared->sources()['csis_expert_survey'],
                $this->shared->sources()['csis_pla_purges'],
                $this->shared->sources()['brookings_2028_inflection'],
                $this->shared->sources()['taiwan_constitution_term'],
                $this->shared->sources()['taiwan_inauguration_2024'],
                $this->shared->sources()['reuters_status_quo'],
                $this->shared->sources()['reuters_naval'],
                $this->shared->sources()['reuters_drone'],
            ],
        ];

        return [
            [
                'type' => ProjectionType::Optimistic,
                'target_date' => CarbonImmutable::parse('2032-05-20 00:00:00', 'UTC'),
                'title' => $this->shared->t(
                    en: 'Deterrence holds',
                    it: 'La deterrenza regge',
                    fr: 'La dissuasion tient',
                    de: 'Die Abschreckung hält',
                    es: 'La disuasión se mantiene',
                    nl: 'Afschrikking houdt stand',
                    sv: 'Avskräckningen håller',
                    pl: 'Odstraszanie działa',
                ),
                'summary' => $this->shared->t(
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
                'target_date' => CarbonImmutable::parse('2028-05-20 00:00:00', 'UTC'),
                'title' => $this->shared->t(
                    en: 'Baseline risk window',
                    it: 'Finestra base di rischio',
                    fr: 'Fenêtre de risque de référence',
                    de: 'Basis-Risikofenster',
                    es: 'Ventana base de riesgo',
                    nl: 'Basisrisicovenster',
                    sv: 'Grundläggande riskfönster',
                    pl: 'Bazowe okno ryzyka',
                ),
                'summary' => $this->shared->t(
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
                'target_date' => CarbonImmutable::parse('2027-12-31 23:59:59', 'UTC'),
                'title' => $this->shared->t(
                    en: 'Accelerated crisis',
                    it: 'Crisi accelerata',
                    fr: 'Crise accélérée',
                    de: 'Beschleunigte Krise',
                    es: 'Crisis acelerada',
                    nl: 'Versnelde crisis',
                    sv: 'Accelererad kris',
                    pl: 'Przyspieszony kryzys',
                ),
                'summary' => $this->shared->t(
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
};
