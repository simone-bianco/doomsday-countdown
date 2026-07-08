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
     * @param array<int, string> $intro
     * @param array<int, array{label: string, value: string, description: string}> $stats
     * @param array<int, array{title: string, body: string}> $sections
     * @param array<int, array{label: string, title: string, body: string}> $timeline
     * @param array<int, array{question: string, answer: string}> $faq
     */
    public function __construct(
        public readonly string $app_name,
        public readonly string $current_locale,
        public readonly array $languages,
        public readonly string $title,
        public readonly string $subtitle,
        public readonly string $eyebrow,
        public readonly string $hero_badge,
        public readonly string $filter_watch_label,
        public readonly string $visual_label,
        public readonly string $pipeline_label,
        public readonly string $faq_title,
        public readonly string $faq_subtitle,
        public readonly string $closing_label,
        public readonly array $intro,
        public readonly array $stats,
        public readonly array $sections,
        public readonly array $timeline,
        public readonly array $faq,
        public readonly string $closing_title,
        public readonly string $closing_body,
    ) {
    }
}
