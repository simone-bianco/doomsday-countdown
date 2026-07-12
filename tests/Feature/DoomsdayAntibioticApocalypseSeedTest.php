<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\CountdownSeverity;
use App\Enums\CountdownStatus;
use App\Enums\ProjectionType;
use App\Enums\VisualizationType;
use App\Models\ContentSource;
use App\Models\Countdown;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use SimoneBianco\Patches\Facades\Patches;
use Tests\TestCase;

final class DoomsdayAntibioticApocalypseSeedTest extends TestCase
{
    use RefreshDatabase;

    /** @var array<int, string> */
    private const PATCHES = [
        'countdowns/antibiotic_apocalypse/2026_07_11_023000_prepare_antibiotic_apocalypse',
        'countdowns/antibiotic_apocalypse/2026_07_11_023010_seed_antibiotic_apocalypse_countdown',
        'countdowns/antibiotic_apocalypse/2026_07_11_023020_seed_antibiotic_apocalypse_projections',
        'countdowns/antibiotic_apocalypse/2026_07_11_023030_seed_antibiotic_apocalypse_visualizations',
        'countdowns/antibiotic_apocalypse/2026_07_11_023040_seed_antibiotic_apocalypse_news',
        'countdowns/antibiotic_apocalypse/2026_07_11_023050_seed_antibiotic_apocalypse_content_sources',
        'countdowns/antibiotic_apocalypse/2026_07_11_023060_seed_antibiotic_apocalypse_initiatives',
    ];

    /** @var array<int, string> */
    private const LOCALES = ['en', 'it', 'fr', 'de', 'es', 'nl', 'sv', 'pl'];

    public function test_owned_patches_seed_evidence_rich_antibiotic_apocalypse_countdown(): void
    {
        $this->runOwnedPatches();

        $countdown = Countdown::query()
            ->where('slug', 'antibiotic-apocalypse')
            ->with(['projections.visualizations', 'visualizations', 'news', 'initiatives', 'contentSources'])
            ->firstOrFail();

        $this->assertSame('The Antibiotic Apocalypse', $countdown->title['en']);
        $this->assertSame('L’apocalisse antibiotica', $countdown->title['it']);
        $this->assertSame('images/doomsday/antibiotic_apocalypse.png', $countdown->image_path);
        $this->assertFileExists(public_path($countdown->image_path));
        $this->assertSame(5, $countdown->sort_order);
        $this->assertTrue($countdown->is_published);
        $this->assertSame(CountdownSeverity::Critical, $countdown->severity);
        $this->assertSame(CountdownStatus::Active, $countdown->status);
        $this->assertSame('2032-12-31 23:59:59', $countdown->target_date?->format('Y-m-d H:i:s'));

        foreach (['title', 'summary', 'description', 'causes', 'consequences', 'recommended_actions'] as $field) {
            $value = $countdown->{$field};
            $this->assertIsArray($value, $field);
            $this->assertSame(self::LOCALES, array_keys($value), $field);
            foreach (self::LOCALES as $locale) {
                $this->assertNotEmpty($value[$locale], $field.'.'.$locale);
            }
        }
        $descriptionContract = [
            'en' => [['title is editorial', 'active checkpoint', 'post-target evidence checkpoint', 'surveillance and publication lag', 'broader than antibiotic resistance', 'none of these dates predicts a certain treatment collapse'], ['main timer', 'current timer', 'default timer', 'permanent timer']],
            'it' => [['titolo è editoriale', 'checkpoint attivo', 'checkpoint neutral post-target delle evidenze', 'ritardi di sorveglianza e pubblicazione', 'più ampia della resistenza agli antibiotici', 'nessuna di queste date predice un certo collasso terapeutico'], ['timer principale', 'timer corrente', 'timer predefinito', 'timer permanente']],
            'fr' => [['titre est éditorial', 'point de contrôle actif', 'point de contrôle neutral post-cible des données probantes', 'délais de surveillance et de publication', 'dépasse la seule résistance aux antibiotiques', 'aucune de ces dates ne prédit un effondrement certain des traitements'], ['minuteur principal', 'minuteur actuel', 'minuteur par défaut', 'minuteur permanent']],
            'de' => [['titel ist redaktionell', 'aktive kontrollpunkt', 'neutral-evidenzkontrollpunkt nach dem zieljahr', 'überwachungs- und veröffentlichungsverzug', 'umfasst mehr als antibiotikaresistenz', 'keiner dieser termine sagt einen sicheren therapiezusammenbruch voraus'], ['haupttimer', 'aktueller timer', 'standardtimer', 'permanenter timer']],
            'es' => [['título es editorial', 'punto de control activo', 'punto de control neutral de evidencia posterior al objetivo', 'retrasos de vigilancia y publicación', 'más amplia que la resistencia a los antibióticos', 'ninguna de estas fechas predice un colapso terapéutico cierto'], ['temporizador principal', 'temporizador actual', 'temporizador predeterminado', 'temporizador permanente']],
            'nl' => [['titel is redactioneel', 'actieve controlepunt', 'neutral-bewijscontrolepunt na de doelperiode', 'vertraging in surveillance en publicatie', 'breder dan antibioticaresistentie', 'geen van deze data voorspelt een zekere instorting van behandelingen'], ['hoofdtimer', 'huidige timer', 'standaardtimer', 'permanente timer']],
            'sv' => [['titeln är redaktionell', 'aktiva kontrollpunkten', 'neutral-evidenskontrollpunkten efter målåret', 'fördröjning i övervakning och publicering', 'bredare än antibiotikaresistens', 'inget av dessa datum förutsäger en säker behandlingskollaps'], ['huvudtimern', 'aktuell timer', 'standardtimer', 'permanent timer']],
            'pl' => [['tytuł ma charakter redakcyjny', 'aktywnym punktem kontrolnym', 'neutralnym punktem oceny dowodów po terminie celu', 'opóźnieniach nadzoru i publikacji', 'szersza niż oporność na antybiotyki', 'żadna z tych dat nie przewiduje pewnego załamania leczenia'], ['główny licznik', 'bieżący licznik', 'domyślny licznik', 'stały licznik']],
        ];
        foreach (self::LOCALES as $locale) {
            $description = mb_strtolower($countdown->description[$locale], 'UTF-8');
            foreach (array_merge(['pessimistic 2029', 'neutral 2032', 'optimistic 2036'], $descriptionContract[$locale][0]) as $marker) {
                $this->assertStringContainsString($marker, $description, $locale);
            }
            foreach ($descriptionContract[$locale][1] as $marker) {
                $this->assertStringNotContainsString($marker, $description, $locale);
            }
        }

        $projections = $countdown->projections->sortBy('sort_order')->values();
        $this->assertCount(3, $projections);
        $this->assertSame(
            [ProjectionType::Optimistic, ProjectionType::Neutral, ProjectionType::Pessimistic],
            $projections->pluck('type')->all(),
        );
        $expectedDates = [
            ProjectionType::Optimistic->value => '2036-12-31 23:59:59',
            ProjectionType::Neutral->value => '2032-12-31 23:59:59',
            ProjectionType::Pessimistic->value => '2029-12-31 23:59:59',
        ];
        foreach ($projections as $projection) {
            $this->assertSame($expectedDates[$projection->type->value], $projection->target_date?->format('Y-m-d H:i:s'));
            $this->assertSame(self::LOCALES, array_keys($projection->title));
            $this->assertSame(self::LOCALES, array_keys($projection->summary));
            $this->assertSame('editorial temporal scenario', $projection->methodology['classification'] ?? null);
            $this->assertStringContainsString('not epidemiological forecasts', (string) ($projection->methodology['assumption'] ?? ''));
            $this->assertStringContainsString('do not determine the target dates', (string) ($projection->methodology['probability_note'] ?? ''));
            $this->assertSame('2026-07-11', $projection->methodology['assessed_at'] ?? null);
            $this->assertSame(
                str_replace(' ', 'T', $expectedDates[$projection->type->value]).'Z',
                $projection->methodology['target_evaluation_date'] ?? null,
            );
            $this->assertArrayNotHasKey('evaluation_date', $projection->methodology);
            $assessedAt = CarbonImmutable::parse($projection->methodology['assessed_at'], 'UTC');
            $targetEvaluationDate = CarbonImmutable::parse($projection->methodology['target_evaluation_date'], 'UTC');
            $this->assertTrue($assessedAt->lessThan($projection->target_date));
            $this->assertTrue($targetEvaluationDate->equalTo($projection->target_date));
            $this->assertNotEmpty($projection->methodology['semantics'] ?? null);
            $this->assertStringContainsString('does not', strtolower((string) $projection->methodology['semantics']));
            $this->assertNotEmpty($projection->methodology['milestone'] ?? null);
            $this->assertNotEmpty($projection->methodology['date_reasoning'] ?? null);
            $this->assertGreaterThanOrEqual(3, count($projection->methodology['limits'] ?? []));
            $this->assertGreaterThanOrEqual(3, count($projection->methodology['stop_conditions'] ?? []));
            $this->assertGreaterThanOrEqual(5, count($projection->methodology['source_facts'] ?? []));
            $this->assertGreaterThanOrEqual(6, count($projection->methodology['sources'] ?? []));
            foreach ($projection->methodology['sources'] ?? [] as $source) {
                $this->assertStringStartsWith('https://', $source);
            }
        }

        $projectionByType = $projections->keyBy(static fn ($projection): string => $projection->type->value);
        $pessimisticDate = $projectionByType->get(ProjectionType::Pessimistic->value)?->target_date;
        $neutralProjection = $projectionByType->get(ProjectionType::Neutral->value);
        $optimisticDate = $projectionByType->get(ProjectionType::Optimistic->value)?->target_date;
        $this->assertNotNull($pessimisticDate);
        $this->assertNotNull($neutralProjection);
        $this->assertNotNull($optimisticDate);
        $this->assertTrue($pessimisticDate->lessThan($neutralProjection->target_date));
        $this->assertTrue($neutralProjection->target_date->lessThan($optimisticDate));
        $this->assertTrue($countdown->target_date?->equalTo($neutralProjection->target_date));

        $projectionChart = $neutralProjection->visualizations->firstWhere('key', 'projection_curve');
        $this->assertNotNull($projectionChart);
        $this->assertGreaterThanOrEqual(9, count($projectionChart->sources));

        $this->travelTo(CarbonImmutable::parse('2026-07-12 00:00:00', 'UTC'));
        $overview = $this->getJson(route('countdowns.data.overview', ['slug' => $countdown->slug, 'lang' => 'en']))
            ->assertOk()
            ->json('data');
        $this->assertSame('pessimistic', $overview['main_projection']['type']);
        $this->assertStringStartsWith('2029-12-31T23:59:59', $overview['timer']['target_date']);
        $this->assertSame($countdown->description['en'], $overview['description']);
        $this->travelBack();

        $forecasts = $this->getJson(route('countdowns.data.forecasts', ['slug' => $countdown->slug, 'lang' => 'en']))
            ->assertOk()
            ->json('data');
        $this->assertSame('projection_curve', $forecasts['projection_chart']['key']);
        $forecastDates = collect($forecasts['projections'])->mapWithKeys(
            static fn (array $projection): array => [$projection['type'] => $projection['target_date']],
        );
        foreach ($expectedDates as $type => $date) {
            $this->assertStringStartsWith(str_replace(' ', 'T', $date), $forecastDates->get($type));
        }

        $this->assertGreaterThanOrEqual(6, $countdown->visualizations->count());
        $this->assertGreaterThanOrEqual(
            2,
            $countdown->visualizations->filter(
                static fn ($visualization): bool => in_array($visualization->type, [VisualizationType::Line, VisualizationType::Area], true),
            )->count(),
        );
        $this->assertGreaterThanOrEqual(
            1,
            $countdown->visualizations->filter(
                static fn ($visualization): bool => $visualization->type === VisualizationType::Bar,
            )->count(),
        );
        $this->assertSame(VisualizationType::Kpi, $countdown->visualizations->firstWhere('key', 'key_indicators')?->type);

        $allVisualizations = $countdown->visualizations->concat(
            $projections->flatMap(static fn ($projection) => $projection->visualizations),
        );
        $this->assertGreaterThanOrEqual(8, $allVisualizations->count());

        foreach ($allVisualizations as $visualization) {
            $this->assertNotEmpty($visualization->sources, $visualization->key);
            foreach ($visualization->sources as $source) {
                $this->assertStringStartsWith('https://', $source, $visualization->key);
            }
            $this->assertSame(self::LOCALES, array_keys($visualization->reasoning), $visualization->key);
            foreach (self::LOCALES as $locale) {
                $this->assertNotSame('', trim((string) ($visualization->reasoning[$locale] ?? '')), $visualization->key.'.'.$locale);
            }
            $this->assertArrayNotHasKey('sources', $visualization->payload, $visualization->key);
            $this->assertArrayNotHasKey('reasoning', $visualization->payload, $visualization->key);

            if (! in_array($visualization->type, [VisualizationType::Line, VisualizationType::Area, VisualizationType::Bar], true)) {
                continue;
            }

            $this->assertSame(2, $visualization->schema_version, $visualization->key);
            $this->assertArrayHasKey('labels', $visualization->payload, $visualization->key);
            $this->assertArrayHasKey('series', $visualization->payload, $visualization->key);
            $this->assertArrayHasKey('axes', $visualization->payload, $visualization->key);
            $this->assertArrayHasKey('x', $visualization->payload['axes'], $visualization->key);
            $this->assertArrayHasKey('y', $visualization->payload['axes'], $visualization->key);
            $this->assertNotSame('', trim((string) ($visualization->payload['axes']['x']['label'] ?? '')), $visualization->key);
            $this->assertNotSame('', trim((string) ($visualization->payload['axes']['y']['label'] ?? '')), $visualization->key);
            $this->assertNotSame('', trim((string) ($visualization->payload['axes']['y']['unit'] ?? '')), $visualization->key);
            $this->assertNotSame('', trim((string) ($visualization->payload['axes']['y']['format'] ?? '')), $visualization->key);

            $labels = $visualization->payload['labels'];
            $series = $visualization->payload['series'];
            $this->assertIsArray($labels, $visualization->key);
            $this->assertIsArray($series, $visualization->key);
            $this->assertNotEmpty($labels, $visualization->key);
            $this->assertNotEmpty($series, $visualization->key);

            if (collect($series)->every(static fn (mixed $entry): bool => is_array($entry) && array_key_exists('values', $entry))) {
                foreach ($series as $entry) {
                    $this->assertCount(count($labels), $entry['values'], $visualization->key);
                    $this->assertArrayNotHasKey('unit', $entry, $visualization->key);
                    $this->assertArrayNotHasKey('format', $entry, $visualization->key);
                }
            } else {
                $this->assertCount(count($labels), $series, $visualization->key);
            }
        }

        $this->assertDerivedValuesCanBeRecalculated($countdown, $projectionChart);
        $this->assertContentContracts($countdown);
        $this->assertPublicMediaContracts($countdown);
    }

    public function test_owned_patches_are_idempotent_and_rollback_is_scoped(): void
    {
        $this->runOwnedPatches();
        $before = $this->ownedCounts();

        $this->runOwnedPatches();
        $this->assertSame($before, $this->ownedCounts());

        $countdown = Countdown::query()
            ->where('slug', 'antibiotic-apocalypse')
            ->with('contentSources')
            ->firstOrFail();
        $ownedSourceIds = $countdown->contentSources->modelKeys();
        $sharedSource = $countdown->contentSources->firstOrFail();

        $sentinel = Countdown::query()->create([
            'slug' => 'amr-sentinel',
            'title' => ['en' => 'AMR sentinel'],
            'summary' => ['en' => 'Unowned record for rollback verification'],
            'description' => ['en' => 'Must survive rollback of the antibiotic-apocalypse patches.'],
            'severity' => CountdownSeverity::Low,
            'status' => CountdownStatus::Monitoring,
            'target_date' => CarbonImmutable::parse('2040-01-01 00:00:00', 'UTC'),
            'image_path' => 'images/doomsday/antibiotic_apocalypse.png',
            'sort_order' => 99,
            'is_published' => false,
        ]);
        $sentinel->contentSources()->attach($sharedSource->getKey(), [
            'keywords' => json_encode(['sentinel'], JSON_THROW_ON_ERROR),
            'excluded_keywords' => json_encode([], JSON_THROW_ON_ERROR),
            'weight' => 1,
            'is_active' => true,
        ]);

        $this->rollbackOwnedPatches();

        $this->assertFalse(Countdown::query()->where('slug', 'antibiotic-apocalypse')->exists());
        $this->assertTrue(Countdown::query()->where('slug', 'amr-sentinel')->exists());
        $this->assertTrue(ContentSource::query()->whereKey($sharedSource->getKey())->exists());
        $this->assertSame(1, ContentSource::query()->whereIn('id', $ownedSourceIds)->count());
        $this->assertDatabaseHas('content_source_countdown', [
            'countdown_id' => $sentinel->getKey(),
            'content_source_id' => $sharedSource->getKey(),
        ]);
    }

    private function assertDerivedValuesCanBeRecalculated(Countdown $countdown, mixed $projectionChart): void
    {
        $years = range(2025, 2036);
        $this->assertSame(array_map('strval', $years), $projectionChart->payload['labels']);
        $scenarioParameters = [
            'Optimistic — 2036' => ['target_year' => 2036, 'endpoint' => 90],
            'Neutral — 2032' => ['target_year' => 2032, 'endpoint' => 110],
            'Pessimistic — 2029' => ['target_year' => 2029, 'endpoint' => 130],
        ];
        foreach ($projectionChart->payload['series'] as $series) {
            $parameters = $scenarioParameters[$series['name']];
            foreach ($years as $index => $year) {
                $elapsed = min(max($year - 2025, 0), $parameters['target_year'] - 2025);
                $expected = round(100 + (($parameters['endpoint'] - 100) * $elapsed / ($parameters['target_year'] - 2025)), 1);
                $this->assertEqualsWithDelta($expected, $series['values'][$index], 0.05, $series['name'].' '.$year);
            }
        }

        $burden = $countdown->visualizations->firstWhere('key', 'global_burden_2021');
        $this->assertSame([1.14, 4.71], $burden?->payload['series']);

        $bsi = $countdown->visualizations->firstWhere('key', 'eu_resistant_bsi_2024');
        $this->assertSame([11.03, 9.38, 3.51, 2.07], $bsi?->payload['series']);

        $klebsiella = $countdown->visualizations->firstWhere('key', 'carbapenem_klebsiella_trend');
        $this->assertSame([2.18, 3.97, 3.51], $klebsiella?->payload['series']);
        $this->assertEqualsWithDelta(round(3.51 / 1.61, 2), $klebsiella?->payload['series'][0], 0.0001);

        $consumption = $countdown->visualizations->firstWhere('key', 'community_antibiotic_consumption');
        $this->assertSame([18.3, 18.8], $consumption?->payload['series']);

        $access = $countdown->visualizations->firstWhere('key', 'access_antibiotic_share');
        $this->assertSame([60.3, 65], $access?->payload['series']);
        $this->assertEqualsWithDelta(4.7, $access?->payload['series'][1] - $access?->payload['series'][0], 0.0001);

        $pipeline = $countdown->visualizations->firstWhere('key', 'clinical_pipeline_2025');
        $this->assertSame([50, 40], $pipeline?->payload['series']);
        $this->assertSame(90, array_sum($pipeline?->payload['series'] ?? []));
        $this->assertSame(7, 97 - array_sum($pipeline?->payload['series'] ?? []));
    }

    private function assertContentContracts(Countdown $countdown): void
    {
        $this->assertCount(12, $countdown->news);
        $newsPreviewUrls = [];
        foreach ($countdown->news as $news) {
            $this->assertNotSame('', trim($news->title));
            $this->assertNotSame('', trim($news->excerpt));
            $this->assertStringStartsWith('https://', (string) $news->source_url);
            $this->assertStringStartsWith('https://', (string) $news->preview_image_url);
            $this->assertSame('images/doomsday/antibiotic_apocalypse.png', $news->image_path);
            $this->assertFileExists(public_path($news->image_path));
            $this->assertStringNotContainsString('<iframe', strtolower(implode(' ', array_filter([
                $news->title,
                $news->excerpt,
                $news->source_url,
                $news->embed_url,
            ]))));
            $newsPreviewUrls[] = $news->preview_image_url;
        }
        $this->assertCount(count($newsPreviewUrls), array_unique($newsPreviewUrls));

        $youtubeNews = $countdown->news->where('content_type', 'youtube_video');
        $this->assertGreaterThanOrEqual(2, $youtubeNews->count());
        foreach ($youtubeNews as $news) {
            $this->assertYoutubeMedia(
                url: (string) $news->source_url,
                provider: $news->external_provider,
                externalId: $news->external_id,
                embedUrl: $news->embed_url,
                previewImageUrl: $news->preview_image_url,
            );
        }

        $this->assertCount(10, $countdown->initiatives);
        $initiativePreviewUrls = [];
        foreach ($countdown->initiatives as $initiative) {
            $this->assertNotSame('', trim($initiative->title));
            $this->assertNotSame('', trim($initiative->excerpt));
            $this->assertNotSame('', trim((string) $initiative->organization));
            $this->assertStringStartsWith('https://', $initiative->url);
            $this->assertStringStartsWith('https://', (string) $initiative->preview_image_url);
            $this->assertSame('images/doomsday/antibiotic_apocalypse.png', $initiative->image_path);
            $this->assertFileExists(public_path($initiative->image_path));
            $this->assertStringNotContainsString('<iframe', strtolower(implode(' ', array_filter([
                $initiative->title,
                $initiative->excerpt,
                $initiative->body,
                $initiative->url,
                $initiative->embed_url,
            ]))));
            $initiativePreviewUrls[] = $initiative->preview_image_url;
        }
        $this->assertCount(count($initiativePreviewUrls), array_unique($initiativePreviewUrls));

        $youtubeInitiatives = $countdown->initiatives->where('content_type', 'youtube_video');
        $this->assertGreaterThanOrEqual(1, $youtubeInitiatives->count());
        foreach ($youtubeInitiatives as $initiative) {
            $this->assertYoutubeMedia(
                url: $initiative->url,
                provider: $initiative->external_provider,
                externalId: $initiative->external_id,
                embedUrl: $initiative->embed_url,
                previewImageUrl: $initiative->preview_image_url,
            );
        }

        $this->assertGreaterThanOrEqual(10, $countdown->contentSources->count());
        foreach ($countdown->contentSources as $source) {
            $this->assertSame(ContentSource::TYPE_WEBSITE, $source->type);
            $this->assertNotSame('', trim((string) $source->provider));
            $this->assertNotSame('', trim((string) $source->external_id));
            $this->assertStringStartsWith('https://', (string) $source->source_url);
            $this->assertTrue((bool) $source->pivot->is_active);
            $this->assertGreaterThan(0, (int) $source->pivot->weight);
            $this->assertNotEmpty(json_decode((string) $source->pivot->keywords, true, 512, JSON_THROW_ON_ERROR));
        }
    }

    private function assertPublicMediaContracts(Countdown $countdown): void
    {
        $newsPayload = $this->getJson(route('countdowns.data.news', [
            'slug' => $countdown->slug,
            'lang' => 'en',
        ]))
            ->assertOk()
            ->json('data.news');

        $this->assertIsArray($newsPayload);
        $this->assertCount($countdown->news->count(), $newsPayload);
        $newsByUrl = $countdown->news->keyBy('source_url');
        foreach ($newsPayload as $newsItem) {
            $persisted = $newsByUrl->get($newsItem['source_url']);
            $this->assertNotNull($persisted);
            $this->assertSame($persisted->preview_image_url, $newsItem['image_url']);
            $this->assertStringStartsWith('https://', $newsItem['image_url']);
            $this->assertNotSame(asset($persisted->image_path), $newsItem['image_url']);
            $this->assertStringNotContainsString('<iframe', strtolower(json_encode($newsItem, JSON_THROW_ON_ERROR)));
        }

        $initiativePayload = $this->getJson(route('countdowns.data.initiatives', [
            'slug' => $countdown->slug,
            'lang' => 'en',
        ]))
            ->assertOk()
            ->json('data.initiatives');

        $this->assertIsArray($initiativePayload);
        $this->assertCount($countdown->initiatives->count(), $initiativePayload);
        $initiativesByUrl = $countdown->initiatives->keyBy('url');
        foreach ($initiativePayload as $initiativeItem) {
            $persisted = $initiativesByUrl->get($initiativeItem['url']);
            $this->assertNotNull($persisted);
            $this->assertSame($persisted->preview_image_url, $initiativeItem['image_url']);
            $this->assertStringStartsWith('https://', $initiativeItem['image_url']);
            $this->assertNotSame(asset($persisted->image_path), $initiativeItem['image_url']);
            $this->assertStringNotContainsString('<iframe', strtolower(json_encode($initiativeItem, JSON_THROW_ON_ERROR)));
        }
    }

    private function assertYoutubeMedia(
        string $url,
        ?string $provider,
        ?string $externalId,
        ?string $embedUrl,
        ?string $previewImageUrl,
    ): void {
        $this->assertSame('youtube', $provider);
        $this->assertMatchesRegularExpression('/^[A-Za-z0-9_-]{11}$/', (string) $externalId);
        $this->assertSame('https://www.youtube.com/watch?v='.$externalId, $url);
        $this->assertSame('https://www.youtube.com/embed/'.$externalId, $embedUrl);
        $this->assertSame('https://i.ytimg.com/vi/'.$externalId.'/hqdefault.jpg', $previewImageUrl);
    }

    /** @return array<string, int> */
    private function ownedCounts(): array
    {
        $countdown = Countdown::query()
            ->where('slug', 'antibiotic-apocalypse')
            ->with(['projections.visualizations', 'visualizations', 'news', 'initiatives', 'contentSources'])
            ->firstOrFail();

        return [
            'projections' => $countdown->projections->count(),
            'projection_visualizations' => $countdown->projections->sum(
                static fn ($projection): int => $projection->visualizations->count(),
            ),
            'visualizations' => $countdown->visualizations->count(),
            'news' => $countdown->news->count(),
            'initiatives' => $countdown->initiatives->count(),
            'content_sources' => $countdown->contentSources->count(),
        ];
    }

    private function runOwnedPatches(): void
    {
        foreach (self::PATCHES as $patch) {
            $this->assertTrue(Patches::runSinglePatch($patch), 'Failed to run owned patch: '.$patch);
        }
    }

    private function rollbackOwnedPatches(): void
    {
        foreach (array_reverse(self::PATCHES) as $patch) {
            $instance = require base_path('database/patches/'.$patch.'/patch.php');
            $this->assertIsObject($instance, $patch);
            $this->assertTrue(method_exists($instance, 'down'), $patch);
            $instance->down();
        }
    }
}
