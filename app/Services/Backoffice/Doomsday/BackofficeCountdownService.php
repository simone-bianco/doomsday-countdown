<?php

declare(strict_types=1);

namespace App\Services\Backoffice\Doomsday;

use App\Data\Backoffice\Doomsday\SaveCountdownData;
use App\Models\Countdown;
use App\Models\Initiative;
use App\Models\News;
use App\Models\Projection;
use App\Models\Visualization;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

final class BackofficeCountdownService
{
    private const INDEX_PER_PAGE = 12;

    private const RELATION_PER_PAGE = 8;

    private const INDEX_SORTS = ['id', 'title', 'sort_order'];

    private const RELATION_SORTS = ['id', 'title', 'sort_order'];

    private const VISUALIZATION_SORTS = ['id', 'title', 'key', 'sort_order'];

    public function __construct(
        private readonly BackofficeDoomsdayInputNormalizer $normalizer,
        private readonly BackofficeDoomsdayOptionService $options,
    ) {}

    /** @return array<string, mixed> */
    public function index(string $search = '', string $sort = 'sort_order', string $direction = 'asc'): array
    {
        $search = $this->normalizeSearch($search);
        $sort = $this->normalizeIndexSort($sort);
        $direction = $this->normalizeDirection($direction);
        $query = Countdown::query()
            ->withCount(['projections', 'visualizations', 'news', 'initiatives']);

        $this->applySearch($query, $search, ['slug', 'title', 'summary', 'status', 'severity']);
        $this->applyIndexOrder($query, $sort, $direction);

        $paginator = $query
            ->paginate(self::INDEX_PER_PAGE)
            ->withQueryString();

        return [
            'countdowns' => $this->serializePaginator($paginator, fn (Countdown $countdown): array => $this->summary($countdown), $search, [
                'sort' => $sort,
                'direction' => $direction,
            ]),
            'options' => $this->options->toArray(),
        ];
    }

    /** @param array<string, mixed> $query @return array<string, mixed> */
    public function detail(Countdown $countdown, array $query = []): array
    {
        $countdown->loadCount(['projections', 'visualizations', 'news', 'initiatives']);

        return [
            'countdown' => $this->countdown($countdown, $query),
            'options' => $this->options->toArray(),
        ];
    }

    /** @return array<string, mixed> */
    public function projectionForm(Countdown $countdown, ?Projection $projection = null): array
    {
        $countdown->loadCount(['projections', 'visualizations', 'news', 'initiatives']);
        $projection?->load(['visualizations' => fn ($query) => $query->orderBy('sort_order')->orderBy('id')]);

        return [
            'countdown' => $this->summary($countdown),
            'projection' => $projection instanceof Projection ? $this->projection($projection) : null,
            'options' => $this->options->toArray(),
        ];
    }

    /** @return array<string, mixed> */
    public function visualizationForm(Countdown $countdown, ?Visualization $visualization = null, ?Projection $projection = null): array
    {
        $countdown->loadCount(['projections', 'visualizations', 'news', 'initiatives']);
        $projection?->load(['visualizations' => fn ($query) => $query->orderBy('sort_order')->orderBy('id')]);

        return [
            'countdown' => $this->summary($countdown),
            'projection' => $projection instanceof Projection ? $this->projection($projection) : null,
            'visualization' => $visualization instanceof Visualization ? $this->visualization($visualization) : null,
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

            $countdown->visualizations->each(fn (Visualization $visualization): ?bool => $visualization->delete());
            $countdown->projections->each(function (Projection $projection): void {
                $projection->visualizations->each(fn (Visualization $visualization): ?bool => $visualization->delete());
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

    /** @param array<string, mixed> $query @return array<string, mixed> */
    private function countdown(Countdown $countdown, array $query): array
    {
        $projectionSort = $this->normalizeRelationSort($this->queryString($query, 'projections_sort'), self::RELATION_SORTS);
        $projectionDirection = $this->normalizeDirection($this->queryString($query, 'projections_direction'));
        $visualizationSort = $this->normalizeRelationSort($this->queryString($query, 'visualizations_sort'), self::VISUALIZATION_SORTS);
        $visualizationDirection = $this->normalizeDirection($this->queryString($query, 'visualizations_direction'));
        $newsSort = $this->normalizeRelationSort($this->queryString($query, 'news_sort'), self::RELATION_SORTS);
        $newsDirection = $this->normalizeDirection($this->queryString($query, 'news_direction'));
        $initiativeSort = $this->normalizeRelationSort($this->queryString($query, 'initiatives_sort'), self::RELATION_SORTS);
        $initiativeDirection = $this->normalizeDirection($this->queryString($query, 'initiatives_direction'));

        return [
            ...$this->summary($countdown),
            'description' => $countdown->description,
            'causes' => $countdown->causes,
            'consequences' => $countdown->consequences,
            'recommended_actions' => $countdown->recommended_actions,
            'target_date' => $countdown->target_date?->toISOString(),
            'image_path' => $countdown->image_path,
            'accent_color' => $countdown->accent_color,
            'projections' => $this->paginatedProjections($countdown, $this->queryString($query, 'projections_search'), $projectionSort, $projectionDirection),
            'visualizations' => $this->paginatedVisualizations($countdown, $this->queryString($query, 'visualizations_search'), $visualizationSort, $visualizationDirection),
            'news' => $this->paginatedNews($countdown, $this->queryString($query, 'news_search'), $newsSort, $newsDirection),
            'initiatives' => $this->paginatedInitiatives($countdown, $this->queryString($query, 'initiatives_search'), $initiativeSort, $initiativeDirection),
        ];
    }

    /** @return array<string, mixed> */
    private function paginatedProjections(Countdown $countdown, string $search, string $sort, string $direction): array
    {
        $query = $countdown->projections()
            ->with(['visualizations' => fn ($query) => $query->orderBy('sort_order')->orderBy('id')]);

        $this->applySearch($query, $search, ['type', 'title', 'summary', 'trend', 'methodology']);
        $this->applyRelationOrder($query, $sort, $direction);

        $paginator = $query
            ->paginate(self::RELATION_PER_PAGE, ['*'], 'projections_page')
            ->withQueryString();

        return $this->serializePaginator($paginator, fn (Projection $projection): array => $this->projection($projection), $search, [
            'sort' => $sort,
            'direction' => $direction,
        ]);
    }

    /** @return array<string, mixed> */
    private function paginatedVisualizations(Countdown $countdown, string $search, string $sort, string $direction): array
    {
        $query = $countdown->visualizations();

        $this->applySearch($query, $search, ['key', 'type', 'title', 'description']);
        $this->applyRelationOrder($query, $sort, $direction);

        $paginator = $query
            ->paginate(self::RELATION_PER_PAGE, ['*'], 'visualizations_page')
            ->withQueryString();

        return $this->serializePaginator($paginator, fn (Visualization $visualization): array => $this->visualization($visualization), $search, [
            'sort' => $sort,
            'direction' => $direction,
        ]);
    }

    /** @return array<string, mixed> */
    private function paginatedNews(Countdown $countdown, string $search, string $sort, string $direction): array
    {
        $query = $countdown->news();

        $this->applySearch($query, $search, ['locale', 'title', 'excerpt', 'source_name', 'source_url']);
        $this->applyRelationOrder($query, $sort, $direction);

        $paginator = $query
            ->paginate(self::RELATION_PER_PAGE, ['*'], 'news_page')
            ->withQueryString();

        return $this->serializePaginator($paginator, fn (News $news): array => $this->news($news), $search, [
            'sort' => $sort,
            'direction' => $direction,
        ]);
    }

    /** @return array<string, mixed> */
    private function paginatedInitiatives(Countdown $countdown, string $search, string $sort, string $direction): array
    {
        $query = $countdown->initiatives();

        $this->applySearch($query, $search, ['locale', 'type', 'title', 'excerpt', 'organization', 'url']);
        $this->applyRelationOrder($query, $sort, $direction);

        $paginator = $query
            ->paginate(self::RELATION_PER_PAGE, ['*'], 'initiatives_page')
            ->withQueryString();

        return $this->serializePaginator($paginator, fn (Initiative $initiative): array => $this->initiative($initiative), $search, [
            'sort' => $sort,
            'direction' => $direction,
        ]);
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
            'visualizations' => $projection->relationLoaded('visualizations')
                ? $projection->visualizations->sortBy('sort_order')->values()->map(fn (Visualization $visualization): array => $this->visualization($visualization))->all()
                : [],
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

    /** @return array<string, mixed> */
    private function news(News $news): array
    {
        return [
            'id' => $news->id,
            'locale' => $news->locale->value,
            'title' => $news->title,
            'excerpt' => $news->excerpt,
            'content_type' => $news->content_type,
            'source_name' => $news->source_name,
            'source_url' => $news->source_url,
            'preview_image_url' => $news->preview_image_url,
            'embed_url' => $news->embed_url,
            'external_provider' => $news->external_provider,
            'external_id' => $news->external_id,
            'image_path' => $news->image_path,
            'published_at' => $news->published_at?->toISOString(),
            'sort_order' => $news->sort_order,
            'is_featured' => $news->is_featured,
        ];
    }

    /** @return array<string, mixed> */
    private function initiative(Initiative $initiative): array
    {
        return [
            'id' => $initiative->id,
            'locale' => $initiative->locale->value,
            'type' => $initiative->type->value,
            'title' => $initiative->title,
            'excerpt' => $initiative->excerpt,
            'body' => $initiative->body,
            'organization' => $initiative->organization,
            'url' => $initiative->url,
            'image_path' => $initiative->image_path,
            'cta_label' => $initiative->cta_label,
            'starts_at' => $initiative->starts_at?->toISOString(),
            'ends_at' => $initiative->ends_at?->toISOString(),
            'sort_order' => $initiative->sort_order,
            'is_featured' => $initiative->is_featured,
        ];
    }

    /** @template TModel of object @param LengthAwarePaginator<TModel> $paginator @param callable(TModel): array<string, mixed> $map @param array<string, string> $filters @return array<string, mixed> */
    private function serializePaginator(LengthAwarePaginator $paginator, callable $map, string $search, array $filters = []): array
    {
        return [
            'data' => $paginator->getCollection()->map($map)->values()->all(),
            'links' => $paginator->linkCollection()->map(fn (array $link): array => [
                'url' => $link['url'],
                'label' => $link['label'],
                'active' => $link['active'],
            ])->values()->all(),
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
                'from' => $paginator->firstItem(),
                'to' => $paginator->lastItem(),
            ],
            'filters' => [
                'search' => $search,
                ...$filters,
            ],
        ];
    }

    private function applyIndexOrder(Builder $query, string $sort, string $direction): void
    {
        if ($sort === 'title') {
            $grammar = $query->getQuery()->getGrammar();
            $driver = $query->getModel()->getConnection()->getDriverName();
            $castType = $driver === 'mysql' ? 'CHAR' : 'TEXT';
            $wrapped = $grammar->wrap('title');

            $query->orderByRaw('LOWER(CAST('.$wrapped.' AS '.$castType.')) '.$direction)
                ->orderBy('id');

            return;
        }

        $query->orderBy($sort, $direction);

        if ($sort !== 'id') {
            $query->orderBy('id');
        }
    }

    private function applyRelationOrder(Builder|Relation $query, string $sort, string $direction): void
    {
        $builder = $query instanceof Builder ? $query : $query->getQuery();

        if ($sort === 'title') {
            $grammar = $builder->getQuery()->getGrammar();
            $driver = $builder->getModel()->getConnection()->getDriverName();
            $castType = $driver === 'mysql' ? 'CHAR' : 'TEXT';
            $wrapped = $grammar->wrap('title');

            $builder->orderByRaw('LOWER(CAST('.$wrapped.' AS '.$castType.')) '.$direction);
        } else {
            $builder->orderBy($sort, $direction);
        }

        if ($sort !== 'id') {
            $builder->orderBy('id');
        }
    }

    /** @param Builder|Relation $query @param array<int, string> $columns */
    private function applySearch(Builder|Relation $query, string $search, array $columns): void
    {
        $search = $this->normalizeSearch($search);
        if ($search === '') {
            return;
        }

        $builder = $query instanceof Builder ? $query : $query->getQuery();
        $like = '%'.mb_strtolower($search).'%';
        $grammar = $builder->getQuery()->getGrammar();
        $driver = $builder->getModel()->getConnection()->getDriverName();
        $castType = $driver === 'mysql' ? 'CHAR' : 'TEXT';

        $query->where(function (Builder $nested) use ($columns, $like, $grammar, $castType): void {
            foreach ($columns as $column) {
                $wrapped = $grammar->wrap($column);
                $nested->orWhereRaw('LOWER(CAST('.$wrapped.' AS '.$castType.')) LIKE ?', [$like]);
            }
        });
    }

    private function normalizeSearch(string $search): string
    {
        return trim(mb_substr($search, 0, 120));
    }

    private function normalizeIndexSort(string $sort): string
    {
        return in_array($sort, self::INDEX_SORTS, true) ? $sort : 'sort_order';
    }

    /** @param array<int, string> $allowedSorts */
    private function normalizeRelationSort(string $sort, array $allowedSorts): string
    {
        return in_array($sort, $allowedSorts, true) ? $sort : 'id';
    }

    private function normalizeDirection(string $direction): string
    {
        return strtolower($direction) === 'desc' ? 'desc' : 'asc';
    }

    /** @param array<string, mixed> $query */
    private function queryString(array $query, string $key): string
    {
        $value = $query[$key] ?? '';

        return is_string($value) ? $this->normalizeSearch($value) : '';
    }
}
