<?php

declare(strict_types=1);
use SimoneBianco\LaravelKeyRotator\Models\RotableApiKey;
use SimoneBianco\Patches\Patch;

return new class extends Patch
{
    public bool $transactional = true;

    public function up(): void
    {
        $keyFilePath = database_path('seeders/Data/keys.json');
        if (! file_exists($keyFilePath)) {
            return;
        }
        $keys = json_decode((string) file_get_contents($keyFilePath), true, 512, JSON_THROW_ON_ERROR);
        foreach ($keys as $keyName => $keyValue) {
            RotableApiKey::query()->updateOrCreate(
                [
                    'service' => 'openai',
                    'key' => $keyValue,
                ],
                [
                    'extra_data' => [
                        'notes' => [
                            'owner' => $keyName,
                        ],
                    ],
                ],
            );
        }
    }

    public function down(): void
    {
        $keyFilePath = database_path('seeders/Data/keys.json');
        if (! file_exists($keyFilePath)) {
            return;
        }
        $keys = json_decode((string) file_get_contents($keyFilePath), true, 512, JSON_THROW_ON_ERROR);
        RotableApiKey::query()
            ->where('service', 'openai')
            ->whereIn('key', array_values($keys))
            ->delete();
    }
};
