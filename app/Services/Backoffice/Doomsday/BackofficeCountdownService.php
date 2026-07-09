<?php

declare(strict_types=1);

namespace App\Services\Backoffice\Doomsday;

use App\Data\Backoffice\Doomsday\SaveCountdownData;
use App\Models\Countdown;
use App\Models\Projection;
use App\Models\Visualization;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

final class BackofficeCountdownService
{
    public function __construct(
        private readonly BackofficeDoomsdayInputNormalizer $normalizer,
        private readonly BackofficeDoomsdayOptionService $options,
    ) {
    }

    /** @return array<string, mixed> */
    public function index(): array
    {
        return [
            'countdowns' => Countdown::query()
                ->withCount(['projections', 'visualizations', 'news', 'initiatives'])
                ->orderBy('sort_order')
                ->orderBy('id')
                ->get()
                ->map(fn (Countdown $countdown): array => $this->summary($countdown))
                ->all(),
            'options' => $this->options->toArray(),
        ];
    }

    /** @return array<string, mixed> */
    public function detail(Countdown $countdown): array
    {
        $countdown->load(['projections.visualizations', 'visualizations', 'news', 'initiatives']);

        return [
            'countdown' => $this->countdown($countdown),
            'options' => $this->options->toArray(),
        ];
    }

    public function create(SaveCountdownData $data): Countdown
    {
        $this->ensureSlugIsUnique($data->slug);

        return Countdown::query()->create($this->attributes($data));
    }

    public function update(Countdown $countdown, SaveCountdownData $data): Countdown
    {
        $this->ensureSlugIsUnique($data->slug, $countdown);
        $countdown->update($this->attributes($data));

        return $countdown->refresh();
    }

    public function delete(Countdown $countdown): void
    {
        DB::transaction(function () use ($countdown): void {
            $countdown->loadMissing(['visualizations', 'projections.visualizations']);

            $countdown->visualizations->each(fn (Visualization $visualization): bool|null => $visualization->delete());
            $countdown->projections->each(function (Projection $projection): void {
                $projection->visualizations->each(fn (Visualization $visualization): bool|null => $visualization->delete());
            });

            $countdown->delete();
        });
    }

    /** @return array<string, mixed> */
    private function attributes(SaveCountdownData $data): array
    {
        return [
            'slug' => $data->slug,
            'title' => $this->normalizer->localizedText($data->title, 'title'),
            'summary' => $this->normalizer->localizedText($data->summary, 'summary'),
            'description' => $this->normalizer->optionalLocalizedText($data->description),
            'causes' => $this->normalizer->localizedList($data->causes),
            'consequences' => $this->normalizer->localizedList($data->consequences),
            'recommended_actions' => $this->normalizer->localizedList($data->recommended_actions),
            'icon' => $data->icon,
            'severity' => $data->severity,
            'status' => $data->status,
            'target_date' => $this->normalizer->nullableDate($data->target_date),
            'image_path' => $data->image_path,
            'accent_color' => $data->accent_color,
            'sort_order' => $data->sort_order,
            'is_published' => $data->is_published,
        ];
    }

    private function ensureSlugIsUnique(string $slug, ?Countdown $ignore = null): void
    {
        $query = Countdown::query()->where('slug', $slug);
        if ($ignore instanceof Countdown) {
            $query->whereKeyNot($ignore->getKey());
        }

        if ($query->exists()) {
            throw ValidationException::withMessages(['slug' => 'A countdown with this slug already exists.']);
        }
    }

    /** @return array<string, mixed> */
    private function summary(Countdown $countdown): array
    {
        return [
            'id' => $countdown->id,
            'slug' => $countdown->slug,
            'title' => $countdown->title,
            'summary' => $countdown->summary,
            'image_path' => $countdown->image_path,
            'severity' => $countdown->severity->value,
            'status' => $countdown->status->value,
            'is_published' => $countdown->is_published,
            'sort_order' => $countdown->sort_order,
            'projections_count' => $countdown->projections_count,
            'visualizations_count' => $countdown->visualizations_count,
            'news_count' => $countdown->news_count,
            'initiatives_count' => $countdown->initiatives_count,
            'updated_at' => $countdown->updated_at?->toISOString(),
        ];
    }

    /** @return array<string, mixed> */
    private function countdown(Countdown $countdown): array
    {
        return [
            ...$this->summary($countdown),
            'description' => $countdown->description,
            'causes' => $countdown->causes,
            'consequences' => $countdown->consequences,
            'recommended_actions' => $countdown->recommended_actions,
            'icon' => $countdown->icon,
            'target_date' => $countdown->target_date?->toISOString(),
            'image_path' => $countdown->image_path,
            'accent_color' => $countdown->accent_color,
            'projections' => $countdown->projections->sortBy('sort_order')->values()->map(fn (Projection $projection): array => $this->projection($projection))->all(),
            'visualizations' => $countdown->visualizations->sortBy('sort_order')->values()->map(fn (Visualization $visualization): array => $this->visualization($visualization))->all(),
            'news' => $countdown->news->sortByDesc('published_at')->values()->map(fn ($news): array => $news->toArray())->all(),
            'initiatives' => $countdown->initiatives->sortBy('sort_order')->values()->map(fn ($initiative): array => $initiative->toArray())->all(),
        ];
    }

    /** @return array<string, mixed> */
    private function projection(Projection $projection): array
    {
        return [
            'id' => $projection->id,
            'type' => $projection->type->value,
            'target_date' => $projection->target_date?->toISOString(),
            'title' => $projection->title,
            'summary' => $projection->summary,
            'confidence_score' => $projection->confidence_score,
            'probability_score' => $projection->probability_score,
            'trend' => $projection->trend,
            'methodology' => $projection->methodology,
            'sort_order' => $projection->sort_order,
            'visualizations' => $projection->visualizations->sortBy('sort_order')->values()->map(fn (Visualization $visualization): array => $this->visualization($visualization))->all(),
        ];
    }

    /** @return array<string, mixed> */
    private function visualization(Visualization $visualization): array
    {
        return [
            'id' => $visualization->id,
            'key' => $visualization->key,
            'type' => $visualization->type->value,
            'title' => $visualization->title,
            'description' => $visualization->description,
            'payload' => $visualization->payload,
            'schema_version' => $visualization->schema_version,
            'sort_order' => $visualization->sort_order,
        ];
    }
}
