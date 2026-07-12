<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

final class PromoteAdminCommand extends Command
{
    protected $signature = 'user:promote-admin
        {email : Existing user email address}
        {--force : Confirm the reviewed administrator rollout}';

    protected $description = 'Promote an existing user to administrator and emit an audit log entry';

    public function handle(): int
    {
        $email = Str::lower(trim((string) $this->argument('email')));

        if (! $this->option('force')) {
            $this->error('Promotion requires --force after the administrator rollout has been reviewed.');

            return self::FAILURE;
        }

        $user = User::query()->whereRaw('LOWER(email) = ?', [$email])->first();
        if (! $user instanceof User) {
            $this->error('No user exists for the supplied email address.');

            return self::FAILURE;
        }

        if ($user->is_admin) {
            $this->info('The user is already an administrator.');

            return self::SUCCESS;
        }

        DB::transaction(function () use ($user): void {
            $user->forceFill(['is_admin' => true])->save();

            Log::notice('security.admin.promoted', [
                'user_id' => $user->getKey(),
                'environment' => app()->environment(),
                'command' => 'user:promote-admin',
            ]);
        });

        $this->info('Administrator promotion completed and audited.');

        return self::SUCCESS;
    }
}
