<?php

declare(strict_types=1);

namespace App\Data\Backoffice\Doomsday;

use App\Enums\NewsLocale;
use Spatie\LaravelData\Attributes\Validation\BooleanType;
use Spatie\LaravelData\Attributes\Validation\Date;
use Spatie\LaravelData\Attributes\Validation\Enum;
use Spatie\LaravelData\Attributes\Validation\In;
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
final class SaveNewsData extends Data
{
    public function __construct(
        #[Required, Enum(NewsLocale::class)]
        public readonly string $locale,
        #[Required, StringType, Max(255)]
        public readonly string $title,
        #[Required, StringType, Max(1000)]
        public readonly string $excerpt,
        #[Nullable, StringType, Max(255)]
        public readonly ?string $source_name,
        #[Nullable, StringType, Url, Max(2000)]
        public readonly ?string $source_url,
        #[Nullable, StringType, Max(255)]
        public readonly ?string $image_path,
        #[Nullable, Date]
        public readonly ?string $published_at,
        #[Required, IntegerType, Min(0)]
        public readonly int $sort_order,
        #[BooleanType]
        public readonly bool $is_featured,
        #[Nullable, In('article', 'youtube_video')]
        public readonly ?string $content_type = null,
        #[Nullable, StringType, Url, Max(2000)]
        public readonly ?string $preview_image_url = null,
        #[Nullable, StringType, Url, Max(2000)]
        public readonly ?string $embed_url = null,
        #[Nullable, StringType, Max(80)]
        public readonly ?string $external_provider = null,
        #[Nullable, StringType, Max(255)]
        public readonly ?string $external_id = null,
    ) {}
}
