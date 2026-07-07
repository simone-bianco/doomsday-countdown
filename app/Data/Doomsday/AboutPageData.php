<?php

declare(strict_types=1);

namespace App\Data\Doomsday;

use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
final class AboutPageData extends Data
{
    /**
     * @param array<int, LanguageOptionData> $languages
     * @param array<int, array{title: string, body: string}> $sections
     */
    public function __construct(
        public readonly string $app_name,
        public readonly string $current_locale,
        public readonly array $languages,
        public readonly string $title,
        public readonly string $subtitle,
        public readonly array $sections,
    ) {
    }
}
