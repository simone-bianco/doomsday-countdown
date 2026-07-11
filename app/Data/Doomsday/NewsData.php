<?php

declare(strict_types=1);

namespace App\Data\Doomsday;

use Carbon\CarbonImmutable;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
final class NewsData extends Data
{
    public function __construct(
        public readonly string $locale,
        public readonly string $title,
        public readonly string $excerpt,
        public readonly string $content_type,
        public readonly ?string $source_name,
        public readonly ?string $source_url,
        public readonly string $image_url,
        public readonly ?string $embed_url,
        public readonly ?string $external_provider,
        public readonly ?CarbonImmutable $published_at,
        public readonly bool $is_featured,
    ) {}
}
