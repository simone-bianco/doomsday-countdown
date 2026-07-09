<?php

declare(strict_types=1);

use SimoneBianco\Patches\Patch;

return new class extends Patch
{
    public bool $transactional = true;

    public function up(): void
    {
        // Reserved for future non-destructive seed preparation.
        // Do not delete the countdown here: generic content-source refreshes can append YouTube news to it.
    }

    public function down(): void
    {
        // No-op: preparation is intentionally non-destructive.
    }
};
