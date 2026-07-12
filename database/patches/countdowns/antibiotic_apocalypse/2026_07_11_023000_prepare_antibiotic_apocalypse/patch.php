<?php

declare(strict_types=1);

use SimoneBianco\Patches\Patch;

return new class extends Patch
{
    public bool $transactional = true;

    public function up(): void
    {
        // Preparation is intentionally non-destructive; each owned patch is independently idempotent.
    }

    public function down(): void
    {
        // No-op: preparation creates no records.
    }
};
