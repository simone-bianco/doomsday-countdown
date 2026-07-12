<?php

declare(strict_types=1);

namespace App\Services\Doomsday\Locale;

final readonly class PublicLocaleResolution
{
    public function __construct(
        public string $locale,
        public string $source,
    ) {}
}
