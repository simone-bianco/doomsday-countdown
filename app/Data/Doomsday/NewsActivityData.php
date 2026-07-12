<?php

declare(strict_types=1);

namespace App\Data\Doomsday;

use Carbon\CarbonImmutable;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
final class NewsActivityData extends Data
{
    /**
     * @param  array<int, string>  $bucket_labels
     * @param  array<int, int>  $bucket_counts
     */
    public function __construct(
        public readonly CarbonImmutable $window_start,
        public readonly CarbonImmutable $window_end,
        public readonly array $bucket_labels,
        public readonly array $bucket_counts,
        public readonly int $total_items,
        public readonly int $unique_sources,
        public readonly ?CarbonImmutable $latest_published_at,
        public readonly ?string $top_countdown_slug,
        public readonly ?string $top_countdown_title,
        public readonly int $top_countdown_count,
    ) {}
}
