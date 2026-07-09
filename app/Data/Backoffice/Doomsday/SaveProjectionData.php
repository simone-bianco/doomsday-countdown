<?php

declare(strict_types=1);

namespace App\Data\Backoffice\Doomsday;

use App\Enums\ProjectionType;
use Spatie\LaravelData\Attributes\Validation\ArrayType;
use Spatie\LaravelData\Attributes\Validation\Date;
use Spatie\LaravelData\Attributes\Validation\Enum;
use Spatie\LaravelData\Attributes\Validation\IntegerType;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Attributes\Validation\Nullable;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\RequiredArrayKeys;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
final class SaveProjectionData extends Data
{
    /**
     * @param array<string, string> $title
     * @param array<string, string>|null $summary
     * @param array<string, string>|null $methodology
     */
    public function __construct(
        #[Required, Enum(ProjectionType::class)]
        public readonly string $type,
        #[Nullable, Date]
        public readonly ?string $target_date,
        #[Required, ArrayType, RequiredArrayKeys('en')]
        public readonly array $title,
        #[Nullable, ArrayType]
        public readonly ?array $summary,
        #[Required, IntegerType, Min(0), Max(100)]
        public readonly int $confidence_score,
        #[Required, IntegerType, Min(0), Max(100)]
        public readonly int $probability_score,
        #[Required, StringType, Max(80)]
        public readonly string $trend,
        #[Nullable, ArrayType]
        public readonly ?array $methodology,
        #[Required, IntegerType, Min(0)]
        public readonly int $sort_order,
    ) {
    }
}
