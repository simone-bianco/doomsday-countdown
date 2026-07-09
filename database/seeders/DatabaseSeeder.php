<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use SimoneBianco\Patches\Facades\Patches;

final class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        if (! Schema::hasTable('sb_patches')) {
            $this->command?->warn('Table sb_patches missing: run migrations before applying data patches.');

            return;
        }
        Patches::runPatches(
            fn (string $message): null => $this->command?->line($message),
        );
    }
}
