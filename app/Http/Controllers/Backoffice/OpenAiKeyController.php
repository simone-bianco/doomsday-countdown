<?php

declare(strict_types=1);

namespace App\Http\Controllers\Backoffice;

use App\Data\SaveOpenAiKeyData;
use App\Http\Controllers\Controller;
use App\Services\Backoffice\Doomsday\BackofficeDashboardService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;
use SimoneBianco\LaravelKeyRotator\Models\RotableApiKey;

final class OpenAiKeyController extends Controller
{
    public function __construct(private readonly BackofficeDashboardService $dashboard) {}

    public function index(): Response
    {
        return Inertia::render('Backoffice/OpenAiKeys/Index', $this->dashboard->openAiKeysIndex());
    }

    public function store(SaveOpenAiKeyData $data): RedirectResponse
    {
        if ($data->key === null || $data->key === '') {
            return back()->with('error', 'OpenAI key is required.');
        }

        RotableApiKey::query()->create($this->attributes($data, $data->key));

        return back()->with('success', 'OpenAI key registered.');
    }

    public function update(SaveOpenAiKeyData $data, RotableApiKey $openAiKey): RedirectResponse
    {
        $openAiKey->update($this->attributes($data, $data->key !== '' ? $data->key : null));

        return back()->with('success', 'OpenAI key updated.');
    }

    public function destroy(RotableApiKey $openAiKey): RedirectResponse
    {
        $openAiKey->delete();

        return back()->with('success', 'OpenAI key deleted.');
    }

    /**
     * @return array<string, mixed>
     */
    private function attributes(SaveOpenAiKeyData $data, ?string $key): array
    {
        $attributes = [
            'service' => 'openai',
            'base_limit_type' => $data->base_limit_type,
            'max_base_usage' => $data->max_base_usage,
            'free_limit_type' => $data->free_limit_type,
            'max_free_usage' => $data->max_free_usage,
            'extra_data' => ['label' => $data->label ?: 'OpenAI key'],
            'is_active' => $data->is_active,
            'is_depleted' => false,
        ];

        if ($key !== null && $key !== '') {
            $attributes['key'] = $key;
        }

        return $attributes;
    }
}
