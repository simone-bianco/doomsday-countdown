<?php

declare(strict_types=1);

return [
    'cache' => [
        'enabled' => env('DOOMSDAY_CACHE_ENABLED', true),
        'ttl' => (int) env('DOOMSDAY_CACHE_TTL', 86400),
    ],
];
