<?php

declare(strict_types=1);

namespace App\Data\Doomsday;

use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
final class CountdownPageData extends Data
{
    /**
     * @param array<int, LanguageOptionData> $languages
     * @param array<int, CountdownIndexData> $countdowns
     * @param array<string, string> $hero
     */
    public function __construct(
        public readonly string $app_name,
        public readonly string $current_locale,
        public readonly array $languages,
        public readonly array $hero,
        public readonly array $countdowns,
        public readonly ?CountdownOverviewData $selected_countdown,
    ) {
    }
}
