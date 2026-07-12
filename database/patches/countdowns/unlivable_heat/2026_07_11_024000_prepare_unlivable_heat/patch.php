<?php

declare(strict_types=1);

use SimoneBianco\Patches\Patch;

return new class extends Patch
{
    public bool $transactional = true;

    public function up(): void
    {
        // Intentionally non-destructive: each following patch owns its exact records.
    }

    public function down(): void
    {
        // No-op: preparation creates no data.
    }
};
