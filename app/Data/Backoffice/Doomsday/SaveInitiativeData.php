<?php

declare(strict_types=1);

namespace App\Data\Backoffice\Doomsday;

use App\Enums\InitiativeLocale;
use App\Enums\InitiativeType;
use Spatie\LaravelData\Attributes\Validation\BooleanType;
use Spatie\LaravelData\Attributes\Validation\Date;
use Spatie\LaravelData\Attributes\Validation\Enum;
use Spatie\LaravelData\Attributes\Validation\IntegerType;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Attributes\Validation\Nullable;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Attributes\Validation\Url;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
final class SaveInitiativeData extends Data
{
    public function __construct(
        #[Required, Enum(InitiativeLocale::class)]
        public readonly string $locale,
        #[Required, Enum(InitiativeType::class)]
        public readonly string $type,
        #[Required, StringType, Max(255)]
        public readonly string $title,
        #[Required, StringType, Max(1000)]
        public readonly string $excerpt,
        #[Nullable, StringType]
        public readonly ?string $body,
        #[Nullable, StringType, Max(255)]
        public readonly ?string $organization,
        #[Required, StringType, Url, Max(500)]
        public readonly string $url,
        #[Nullable, StringType, Max(255)]
        public readonly ?string $image_path,
        #[Nullable, StringType, Max(80)]
        public readonly ?string $cta_label,
        #[Nullable, Date]
        public readonly ?string $starts_at,
        #[Nullable, Date]
        public readonly ?string $ends_at,
        #[Required, IntegerType, Min(0)]
        public readonly int $sort_order,
        #[BooleanType]
        public readonly bool $is_featured,
    ) {
    }
}
