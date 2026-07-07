<?php

declare(strict_types=1);

namespace App\KeyRotators\Openai;

use SimoneBianco\LaravelKeyRotator\KeyRotator;

final class OpenAIKeyRotator extends KeyRotator
{
    protected static string $serviceName = 'openai';

    /** @var string|array<int, string> */
    protected static string|array $configKey = [
        'services.openai.key',
        'laragent.providers.default.api_key',
        'laragent.providers.openai.api_key',
    ];
}
