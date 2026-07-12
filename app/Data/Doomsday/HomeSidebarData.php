<?php

declare(strict_types=1);

namespace App\Data\Doomsday;

use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
final class HomeSidebarData extends Data
{
    /** @param array<int, LatestNewsItemData> $latest_news */
    public function __construct(
        public readonly array $latest_news,
        public readonly NewsActivityData $signal_activity,
    ) {}
}
