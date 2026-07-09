<?php

declare(strict_types=1);

use App\Enums\NewsLocale;
use Carbon\CarbonImmutable;

$shared = require __DIR__.'/../_shared.php';

return new class($shared)
{
    public function __construct(private object $shared) {}

    /** @return array<int, array<string, mixed>> */
    public function news(): array
    {
        return [
            ['locale' => NewsLocale::All, 'title' => 'Taiwan says China risks creating a new status quo', 'excerpt' => 'Taipei warned that repeated pressure around Taiwan can normalize coercive activity even below the threshold of war.', 'source_name' => 'Reuters', 'source_url' => $this->shared->sources()['reuters_status_quo'], 'published_at' => CarbonImmutable::parse('2026-07-08 12:00:00', 'UTC')],
            ['locale' => NewsLocale::All, 'title' => 'Taiwan says attack preparations are not provocation', 'excerpt' => 'Senior officials framed civil and military preparedness as deterrence and resilience rather than escalation.', 'source_name' => 'Reuters', 'source_url' => $this->shared->sources()['reuters_preparedness'], 'published_at' => CarbonImmutable::parse('2026-07-07 12:00:00', 'UTC')],
            ['locale' => NewsLocale::All, 'title' => 'Taiwan tracks upward trend in Chinese naval movements', 'excerpt' => 'Taiwan reported an upward trend in Chinese naval and coast guard activity, with more than 110 ships in the region.', 'source_name' => 'Reuters', 'source_url' => $this->shared->sources()['reuters_naval'], 'published_at' => CarbonImmutable::parse('2026-07-06 12:00:00', 'UTC')],
            ['locale' => NewsLocale::All, 'title' => 'China launches coast guard patrol east of Taiwan', 'excerpt' => 'The patrol highlighted a pressure pattern beyond the median line and around Taiwan’s eastern approaches.', 'source_name' => 'Reuters', 'source_url' => $this->shared->sources()['reuters_coast_guard'], 'published_at' => CarbonImmutable::parse('2026-07-04 12:00:00', 'UTC')],
            ['locale' => NewsLocale::All, 'title' => 'Taiwan drills a worst-case blockade and invasion chain', 'excerpt' => 'Exercises combined blockade, earthquake, sabotage and invasion stress to test whole-of-society response.', 'source_name' => 'Reuters', 'source_url' => $this->shared->sources()['reuters_drill'], 'published_at' => CarbonImmutable::parse('2026-07-03 12:00:00', 'UTC')],
            ['locale' => NewsLocale::All, 'title' => 'Drone deterrence plan calls for a hornet’s nest', 'excerpt' => 'A proposed NT$210B drone package underlined Taiwan’s move toward distributed asymmetric deterrence.', 'source_name' => 'Reuters', 'source_url' => $this->shared->sources()['reuters_drone'], 'published_at' => CarbonImmutable::parse('2026-07-02 12:00:00', 'UTC')],
            ['locale' => NewsLocale::It, 'title' => 'Il commercio nello Stretto è una vulnerabilità globale', 'excerpt' => 'Le analisi CSIS indicano che un’interruzione nello Stretto avrebbe effetti severi su Cina, Taiwan e catene globali.', 'source_name' => 'CSIS', 'source_url' => $this->shared->sources()['csis_trade'], 'published_at' => CarbonImmutable::parse('2026-06-20 12:00:00', 'UTC')],
            ['locale' => NewsLocale::All, 'title' => 'China Military Power Report details Taiwan pressure indicators', 'excerpt' => 'The report provides the baseline for PLA modernization, ADIZ activity and regional military balance assumptions.', 'source_name' => 'U.S. Department of Defense', 'source_url' => $this->shared->sources()['dod'], 'published_at' => CarbonImmutable::parse('2025-12-23 12:00:00', 'UTC')],
        ];
    }
};
