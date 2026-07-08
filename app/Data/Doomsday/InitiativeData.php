<?php

declare(strict_types=1);

namespace App\Data\Doomsday;

use Carbon\CarbonImmutable;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
final class InitiativeData extends Data
{
    public function __construct(
        public readonly string $locale,
        public readonly string $type,
        public readonly string $title,
        public readonly string $excerpt,
        public readonly ?string $body,
        public readonly ?string $organization,
        public readonly string $url,
        public readonly ?string $image_url,
        public readonly ?string $cta_label,
        public readonly ?CarbonImmutable $starts_at,
        public readonly ?CarbonImmutable $ends_at,
        public readonly bool $is_featured,
        public readonly int $sort_order,
    ) {
    }
}
