<?php

declare(strict_types=1);

use SimoneBianco\Patches\Patch;

return new class extends Patch
{
    public bool $transactional = true;

    public function up(): void
    {
        // Intentionally non-destructive. Every following patch owns and replaces only its stable identifiers.
    }

    public function down(): void
    {
        // No-op: preparation creates no data.
    }
};
