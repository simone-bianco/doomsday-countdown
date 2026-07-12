<?php

declare(strict_types=1);

namespace App\Data\Doomsday;

use Carbon\CarbonImmutable;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
final class LatestNewsItemData extends Data
{
    public function __construct(
        public readonly int $id,
        public readonly string $title,
        public readonly string $excerpt,
        public readonly string $content_type,
        public readonly ?string $source_name,
        public readonly ?string $source_url,
        public readonly string $image_url,
        public readonly CarbonImmutable $published_at,
        public readonly string $countdown_slug,
        public readonly string $countdown_title,
        public readonly string $countdown_url,
    ) {}
}
