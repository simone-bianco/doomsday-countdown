<?php

declare(strict_types=1);

return [
    'database_connection' => env('KEY_ROTATOR_DB_CONNECTION'),
    'encrypt_keys' => env('KEY_ROTATOR_ENCRYPT_KEYS', true),
    'table_name' => env('KEY_ROTATOR_TABLE_NAME', 'rotable_api_keys'),
];
