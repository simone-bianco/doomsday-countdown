<?php

declare(strict_types=1);

use App\Models\Countdown;
use SimoneBianco\Patches\Patch;

return new class extends Patch
{
    public bool $transactional = true;

    private const SLUG = 'ai-job-apocalypse';

    public function up(): void
    {
        Countdown::query()->updateOrCreate(['slug' => self::SLUG], $this->data()->countdown());
    }

    public function down(): void
    {
        Countdown::query()->where('slug', self::SLUG)->delete();
    }

    private function data(): object
    {
        return require __DIR__.'/data.php';
    }
};
