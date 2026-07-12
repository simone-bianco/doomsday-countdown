<?php

declare(strict_types=1);

namespace App\Data\Backoffice\Doomsday;

use App\Enums\VisualizationType;
use Spatie\LaravelData\Attributes\Validation\ArrayType;
use Spatie\LaravelData\Attributes\Validation\Enum;
use Spatie\LaravelData\Attributes\Validation\IntegerType;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Attributes\Validation\Nullable;
use Spatie\LaravelData\Attributes\Validation\Regex;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\RequiredArrayKeys;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
final class SaveVisualizationData extends Data
{
    /**
     * @param  array<string, string>  $title
     * @param  array<string, string>|null  $description
     * @param  array<string, string>|null  $explanation
     * @param  array<int, string>  $sources
     * @param  array<string, string>  $reasoning
     * @param  array<string, mixed>  $payload
     */
    public function __construct(
        #[Required, StringType, Max(120), Regex('/^[a-z0-9]+(?:[_-][a-z0-9]+)*$/')]
        public readonly string $key,
        #[Required, Enum(VisualizationType::class)]
        public readonly string $type,
        #[Required, ArrayType, RequiredArrayKeys('en')]
        public readonly array $title,
        #[Nullable, ArrayType]
        public readonly ?array $description,
        #[Nullable, ArrayType]
        public readonly ?array $explanation,
        #[Required, ArrayType]
        public readonly array $sources,
        #[Required, ArrayType, RequiredArrayKeys('en')]
        public readonly array $reasoning,
        #[Required, ArrayType]
        public readonly array $payload,
        #[Required, IntegerType, Min(1)]
        public readonly int $schema_version,
        #[Required, IntegerType, Min(0)]
        public readonly int $sort_order,
    ) {}
}
