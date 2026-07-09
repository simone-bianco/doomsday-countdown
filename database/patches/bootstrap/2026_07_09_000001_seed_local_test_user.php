<?php

declare(strict_types=1);
use App\Models\User;
use SimoneBianco\Patches\Patch;

return new class extends Patch
{
    public bool $transactional = true;

    public function up(): void
    {
        User::query()->updateOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => '#MegaPassword123',
            ],
        );
    }

    public function down(): void
    {
        User::query()->where('email', 'test@example.com')->delete();
    }
};
