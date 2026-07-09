<?php

declare(strict_types=1);

return [
    'backoffice_path' => trim((string) env('AI_STARTER_BACKOFFICE_PATH', 'backoffice'), '/'),
    'demo_agent' => [
        'slug' => env('AI_STARTER_DEMO_AGENT_SLUG', 'quickstart-assistant'),
        'model' => env('OPENAI_MODEL', 'gpt-5.4-mini'),
    ],
];
