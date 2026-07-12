<?php

declare(strict_types=1);

return [
    'cache' => [
        'enabled' => env('DOOMSDAY_CACHE_ENABLED', true),
        'ttl' => (int) env('DOOMSDAY_CACHE_TTL', 86400),
    ],
    'content' => [
        'preview_excerpt_limit' => (int) env('DOOMSDAY_PREVIEW_EXCERPT_LIMIT', 220),
    ],
    'locale' => [
        'trusted_country' => [
            'enabled' => (bool) env('DOOMSDAY_TRUSTED_COUNTRY_ENABLED', false),
            'attribute' => env('DOOMSDAY_TRUSTED_COUNTRY_ATTRIBUTE', 'trusted_country_code'),
        ],
    ],
];
