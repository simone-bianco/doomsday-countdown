<?php

declare(strict_types=1);

namespace App\Data\Backoffice\Doomsday;

use App\Enums\CountdownSeverity;
use App\Enums\CountdownStatus;
use Spatie\LaravelData\Attributes\Validation\ArrayType;
use Spatie\LaravelData\Attributes\Validation\BooleanType;
use Spatie\LaravelData\Attributes\Validation\Date;
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
final class SaveCountdownData extends Data
{
    /**
     * @param  array<string, string>  $title
     * @param  array<string, string>  $summary
     * @param  array<string, string>|null  $description
     * @param  array<string, array<int, string>>|null  $causes
     * @param  array<string, array<int, string>>|null  $consequences
     * @param  array<string, array<int, string>>|null  $recommended_actions
     */
    public function __construct(
        #[Required, StringType, Max(140), Regex('/^[a-z0-9]+(?:-[a-z0-9]+)*$/')]
        public readonly string $slug,
        #[Required, ArrayType, RequiredArrayKeys('en')]
        public readonly array $title,
        #[Required, ArrayType, RequiredArrayKeys('en')]
        public readonly array $summary,
        #[Nullable, ArrayType]
        public readonly ?array $description,
        #[Nullable, ArrayType]
        public readonly ?array $causes,
        #[Nullable, ArrayType]
        public readonly ?array $consequences,
        #[Nullable, ArrayType]
        public readonly ?array $recommended_actions,
        #[Required, Enum(CountdownSeverity::class)]
        public readonly string $severity,
        #[Required, Enum(CountdownStatus::class)]
        public readonly string $status,
        #[Nullable, Date]
        public readonly ?string $target_date,
        #[Required, StringType, Max(255)]
        public readonly string $image_path,
        #[Required, StringType, Max(7), Regex('/^#[0-9A-Fa-f]{6}$/')]
        public readonly string $accent_color,
        #[Required, IntegerType, Min(0)]
        public readonly int $sort_order,
        #[BooleanType]
        public readonly bool $is_published,
    ) {}
}
