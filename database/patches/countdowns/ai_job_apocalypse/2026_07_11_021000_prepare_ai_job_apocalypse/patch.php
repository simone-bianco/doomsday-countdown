<?php

declare(strict_types=1);

use SimoneBianco\Patches\Patch;

return new class extends Patch
{
    public bool $transactional = true;

    public function up(): void
    {
        // Preparation is intentionally non-destructive; every owned module is idempotent.
    }

    public function down(): void
    {
        // No-op because no data is created by this module.
    }
};
