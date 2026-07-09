<?php

declare(strict_types=1);

namespace App\Services\Backoffice\Doomsday;

use App\Models\Countdown;
use App\Models\Initiative;
use App\Models\News;
use App\Models\Projection;
use App\Models\User;
use App\Models\Visualization;
use Illuminate\Database\Eloquent\Builder;
use SimoneBianco\LaravelKeyRotator\Models\RotableApiKey;

final class BackofficeDashboardService
{
    /** @return array<string, mixed> */
    public function dashboard(): array
    {
        return [
            'backofficePath' => $this->backofficePath(),
            'counts' => $this->counts(),
            'metrics' => $this->metrics(),
            'recentCountdowns' => $this->recentCountdowns(),
            'health' => $this->health(),
        ];
    }

    /** @return array<string, mixed> */
    public function usersIndex(): array
    {
        return [
            'backofficePath' => $this->backofficePath(),
            'counts' => $this->counts(),
            'users' => $this->users(),
        ];
    }

    /** @return array<string, mixed> */
    public function openAiKeysIndex(): array
    {
        return [
            'backofficePath' => $this->backofficePath(),
            'counts' => $this->counts(),
            'apiKeys' => $this->apiKeys(),
        ];
    }

    /** @return array<string, int> */
    public function counts(): array
    {
        return [
            'users' => User::query()->count(),
            'apiKeys' => $this->openAiKeys()->count(),
            'countdowns' => Countdown::query()->count(),
        ];
    }

    /** @return array<string, int> */
    public function metrics(): array
    {
        return [
            'countdowns' => Countdown::query()->count(),
            'published' => Countdown::query()->where('is_published', true)->count(),
            'drafts' => Countdown::query()->where('is_published', false)->count(),
            'projections' => Projection::query()->count(),
            'visualizations' => Visualization::query()->count(),
            'news' => News::query()->count(),
            'initiatives' => Initiative::query()->count(),
            'users' => User::query()->count(),
            'apiKeys' => $this->openAiKeys()->count(),
            'activeApiKeys' => $this->openAiKeys()->where('is_active', true)->count(),
        ];
    }

    /** @return array<int, array<string, mixed>> */
    private function users(): array
    {
        return User::query()
            ->orderBy('name')
            ->get(['id', 'name', 'email', 'created_at'])
            ->map(fn (User $user): array => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'created_at' => $user->created_at?->toISOString(),
            ])
            ->all();
    }

    /** @return array<int, array<string, mixed>> */
    private function apiKeys(): array
    {
        return $this->openAiKeys()
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
            ])
            ->all();
    }

    /** @return array<int, array<string, mixed>> */
    private function recentCountdowns(): array
    {
        return Countdown::query()
            ->withCount(['projections', 'visualizations', 'news', 'initiatives'])
            ->latest('updated_at')
            ->limit(5)
            ->get()
            ->map(fn (Countdown $countdown): array => [
                'id' => $countdown->id,
                'slug' => $countdown->slug,
                'title' => $countdown->title,
                'image_path' => $countdown->image_path,
                'status' => $countdown->status->value,
                'severity' => $countdown->severity->value,
                'is_published' => $countdown->is_published,
                'updated_at' => $countdown->updated_at?->toISOString(),
                'projections_count' => $countdown->projections_count,
                'visualizations_count' => $countdown->visualizations_count,
                'news_count' => $countdown->news_count,
                'initiatives_count' => $countdown->initiatives_count,
            ])
            ->all();
    }

    /** @return array<int, array<string, string>> */
    private function health(): array
    {
        $metrics = $this->metrics();

        return [
            ['label' => 'CRUD status', 'value' => 'CRUD ready'],
            ['label' => 'Published coverage', 'value' => $metrics['published'].' published countdowns'],
            ['label' => 'API keys', 'value' => $metrics['activeApiKeys'].' active OpenAI keys'],
        ];
    }

    /** @return Builder<RotableApiKey> */
    private function openAiKeys(): Builder
    {
        return RotableApiKey::query()->where('service', 'openai');
    }

    private function backofficePath(): string
    {
        return '/'.trim((string) config('ai-starter.backoffice_path'), '/');
    }

    private function mask(string $key): string
    {
        if ($key === '') {
            return 'empty';
        }

        return substr($key, 0, 7).'…'.substr($key, -4);
    }
}
