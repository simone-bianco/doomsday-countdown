<?php

declare(strict_types=1);

namespace App\Data\Doomsday;

use Carbon\CarbonImmutable;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
final class SeoPageData extends Data
{
    /**
     * @param  array<int, SeoAlternateData>  $alternates
     * @param  array<int, array<string, mixed>>  $structured_data
     */
    public function __construct(
        public readonly string $title,
        public readonly string $description,
        public readonly string $canonical_url,
        public readonly string $robots,
        public readonly string $locale,
        public readonly array $alternates,
        public readonly string $x_default_url,
        public readonly SeoOpenGraphData $open_graph,
        public readonly SeoTwitterData $twitter,
        public readonly ?CarbonImmutable $date_modified,
        public readonly array $structured_data,
    ) {}
}
