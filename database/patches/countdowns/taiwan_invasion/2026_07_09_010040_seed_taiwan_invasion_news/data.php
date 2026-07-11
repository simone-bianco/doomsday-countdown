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
            [
                'locale' => NewsLocale::All,
                'title' => 'What To Do When China Attacks',
                'excerpt' => 'China Uncensored examines Taiwan’s civil-defense preparations and the practical steps households can take before a crisis.',
                'content_type' => 'youtube_video',
                'source_name' => 'China Uncensored',
                'source_url' => 'https://www.youtube.com/watch?v=K_D41C19l-8',
                'external_provider' => 'youtube',
                'external_id' => 'K_D41C19l-8',
                'embed_url' => 'https://www.youtube.com/embed/K_D41C19l-8',
                'preview_image_url' => 'https://i.ytimg.com/vi/K_D41C19l-8/hqdefault.jpg',
            ],
            [
                'locale' => NewsLocale::All,
                'title' => 'China’s Secret Weapon to Take Taiwan',
                'excerpt' => 'China Uncensored reviews coercive options Beijing could use against Taiwan before or alongside a conventional assault.',
                'content_type' => 'youtube_video',
                'source_name' => 'China Uncensored',
                'source_url' => 'https://www.youtube.com/watch?v=0kc5brcpIw0',
                'external_provider' => 'youtube',
                'external_id' => '0kc5brcpIw0',
                'embed_url' => 'https://www.youtube.com/embed/0kc5brcpIw0',
                'preview_image_url' => 'https://i.ytimg.com/vi/0kc5brcpIw0/hqdefault.jpg',
            ],
            [
                'locale' => NewsLocale::All,
                'title' => 'Taiwan says China risks creating a new status quo',
                'excerpt' => 'Taipei warned that repeated pressure around Taiwan can normalize coercive activity even below the threshold of war.',
                'source_name' => 'Reuters',
                'source_url' => $this->shared->sources()['reuters_status_quo'],
                'preview_image_url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/b/b0/The_Taiwan_Strait_separates_the_eastern_coast_of_China%E2%80%99s_Fujian_Province_%28iss073e0983013%29.jpg/1280px-The_Taiwan_Strait_separates_the_eastern_coast_of_China%E2%80%99s_Fujian_Province_%28iss073e0983013%29.jpg',
                'published_at' => CarbonImmutable::parse('2026-07-08 12:00:00', 'UTC'),
            ],
            [
                'locale' => NewsLocale::All,
                'title' => 'Taiwan says attack preparations are not provocation',
                'excerpt' => 'Senior officials framed civil and military preparedness as deterrence and resilience rather than escalation.',
                'source_name' => 'Reuters',
                'source_url' => $this->shared->sources()['reuters_preparedness'],
                'preview_image_url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/2/2c/Taipei_Taiwan_Presidential-Office-Building-01a.jpg/1280px-Taipei_Taiwan_Presidential-Office-Building-01a.jpg',
                'published_at' => CarbonImmutable::parse('2026-07-07 12:00:00', 'UTC'),
            ],
            [
                'locale' => NewsLocale::All,
                'title' => 'Taiwan tracks upward trend in Chinese naval movements',
                'excerpt' => 'Taiwan reported an upward trend in Chinese naval and coast guard activity, with more than 110 ships in the region.',
                'source_name' => 'Reuters',
                'source_url' => $this->shared->sources()['reuters_naval'],
                'preview_image_url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/f/ff/Taiwan_Coast_Guard_patrol_boat_PP-3567_in_2024.jpg/1280px-Taiwan_Coast_Guard_patrol_boat_PP-3567_in_2024.jpg',
                'published_at' => CarbonImmutable::parse('2026-07-06 12:00:00', 'UTC'),
            ],
            [
                'locale' => NewsLocale::All,
                'title' => 'China launches coast guard patrol east of Taiwan',
                'excerpt' => 'The patrol highlighted a pressure pattern beyond the median line and around Taiwan’s eastern approaches.',
                'source_name' => 'Reuters',
                'source_url' => $this->shared->sources()['reuters_coast_guard'],
                'preview_image_url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/7/73/Patrol_vessel_PP-3582_of_Coast_Guard_Administration_20191206.jpg/1280px-Patrol_vessel_PP-3582_of_Coast_Guard_Administration_20191206.jpg',
                'published_at' => CarbonImmutable::parse('2026-07-04 12:00:00', 'UTC'),
            ],
            [
                'locale' => NewsLocale::All,
                'title' => 'Taiwan drills a worst-case blockade and invasion chain',
                'excerpt' => 'Exercises combined blockade, earthquake, sabotage and invasion stress to test whole-of-society response.',
                'source_name' => 'Reuters',
                'source_url' => $this->shared->sources()['reuters_drill'],
                'preview_image_url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/3/37/ROC-MND_Dazhi_Boai_Camp_20230529.jpg/1280px-ROC-MND_Dazhi_Boai_Camp_20230529.jpg',
                'published_at' => CarbonImmutable::parse('2026-07-03 12:00:00', 'UTC'),
            ],
            [
                'locale' => NewsLocale::All,
                'title' => 'Drone deterrence plan calls for a hornet’s nest',
                'excerpt' => 'A proposed NT$210B drone package underlined Taiwan’s move toward distributed asymmetric deterrence.',
                'source_name' => 'Reuters',
                'source_url' => $this->shared->sources()['reuters_drone'],
                'preview_image_url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/4/4c/Chung_Shyang_II_UAV.jpg/1280px-Chung_Shyang_II_UAV.jpg',
                'published_at' => CarbonImmutable::parse('2026-07-02 12:00:00', 'UTC'),
            ],
            [
                'locale' => NewsLocale::It,
                'title' => 'Il commercio nello Stretto è una vulnerabilità globale',
                'excerpt' => 'Le analisi CSIS indicano che un’interruzione nello Stretto avrebbe effetti severi su Cina, Taiwan e catene globali.',
                'source_name' => 'CSIS',
                'source_url' => $this->shared->sources()['csis_trade'],
                'preview_image_url' => 'https://csis-website-prod.s3.amazonaws.com/s3fs-public/2025-06/Disruptions%20in%20the%20Taiwan%20Strait.png?VersionId=7odMEN9k32oNzvLheKeUxvJ59.Mh6BM9',
                'published_at' => CarbonImmutable::parse('2026-06-20 12:00:00', 'UTC'),
            ],
            [
                'locale' => NewsLocale::All,
                'title' => 'China Military Power Report details Taiwan pressure indicators',
                'excerpt' => 'The report provides the baseline for PLA modernization, ADIZ activity and regional military balance assumptions.',
                'source_name' => 'U.S. Department of Defense',
                'source_url' => $this->shared->sources()['dod'],
                'preview_image_url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/b/b8/The_Pentagon%2C_Headquarters_of_the_US_Department_of_Defense_%28cropped%29.jpg/1280px-The_Pentagon%2C_Headquarters_of_the_US_Department_of_Defense_%28cropped%29.jpg',
                'published_at' => CarbonImmutable::parse('2025-12-23 12:00:00', 'UTC'),
            ],
        ];
    }
};
