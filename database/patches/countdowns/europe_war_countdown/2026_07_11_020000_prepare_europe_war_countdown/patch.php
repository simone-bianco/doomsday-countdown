<?php

declare(strict_types=1);

use SimoneBianco\Patches\Patch;

return new class extends Patch
{
    public bool $transactional = true;

    public function up(): void
    {
        // Preparation is intentionally non-destructive; all owned modules are idempotent.
    }

    public function down(): void
    {
        // No-op: this module creates no records.
    }
};
