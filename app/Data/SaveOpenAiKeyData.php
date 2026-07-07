<?php

declare(strict_types=1);

namespace App\Data;

use Spatie\LaravelData\Attributes\Validation\BooleanType;
use Spatie\LaravelData\Attributes\Validation\In;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Nullable;
use Spatie\LaravelData\Attributes\Validation\Numeric;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
final class SaveOpenAiKeyData extends Data
{
    public function __construct(
        #[Nullable, StringType, Max(255)]
        public readonly ?string $label,
        #[Nullable, StringType, Max(500)]
        public readonly ?string $key,
        #[Required, In('none', 'fixed', 'unlimited')]
        public readonly string $base_limit_type,
        #[Nullable, Numeric]
        public readonly ?float $max_base_usage,
        #[Required, In('none', 'daily', 'monthly')]
        public readonly string $free_limit_type,
        #[Nullable, Numeric]
        public readonly ?float $max_free_usage,
        #[BooleanType]
        public readonly bool $is_active,
    ) {
    }
}
