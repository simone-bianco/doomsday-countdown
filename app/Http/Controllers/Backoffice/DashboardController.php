<?php

declare(strict_types=1);

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;
use SimoneBianco\LaravelKeyRotator\Models\RotableApiKey;

final class DashboardController extends Controller
{
    public function __invoke(): Response
    {
        return Inertia::render('Backoffice/Index', [
            'backofficePath' => '/' . config('ai-starter.backoffice_path'),
            'users' => User::query()->orderBy('name')->get(['id', 'name', 'email', 'created_at']),
            'apiKeys' => RotableApiKey::query()
                ->where('service', 'openai')
                ->orderByDesc('created_at')
                ->get()
                ->map(fn (RotableApiKey $key): array => [
                    'id' => $key->id,
                    'label' => (string) data_get($key->extra_data, 'label', 'OpenAI key'),
                    'masked_key' => $this->mask((string) $key->key),
                    'base_limit_type' => $key->base_limit_type,
                    'max_base_usage' => $key->max_base_usage,
                    'current_base_usage' => $key->current_base_usage,
                    'free_limit_type' => $key->free_limit_type,
                    'max_free_usage' => $key->max_free_usage,
                    'current_free_usage' => $key->current_free_usage,
                    'is_active' => $key->is_active,
                    'is_depleted' => $key->is_depleted,
                    'last_used_at' => $key->last_used_at?->toISOString(),
                ]),
        ]);
    }

    private function mask(string $key): string
    {
        if ($key === '') {
            return 'empty';
        }

        return substr($key, 0, 7) . '…' . substr($key, -4);
    }
}
