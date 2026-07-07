<?php

declare(strict_types=1);

namespace App\Data\Doomsday;

use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
final class LanguageOptionData extends Data
{
    public function __construct(
        public readonly string $code,
        public readonly string $label,
        public readonly string $native_label,
        public readonly string $flag,
        public readonly string $url,
        public readonly bool $is_current,
    ) {
    }
}
