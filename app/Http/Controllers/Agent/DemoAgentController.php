<?php

declare(strict_types=1);

namespace App\Http\Controllers\Agent;

use App\Data\AgentPromptData;
use App\Http\Controllers\Controller;
use App\Services\Starter\DemoAgentService;
use Illuminate\Http\JsonResponse;
use Throwable;

final class DemoAgentController extends Controller
{
    public function __invoke(AgentPromptData $data, DemoAgentService $agent): JsonResponse
    {
        try {
            return response()->json($agent->run($data->message));
        } catch (Throwable $exception) {
            return response()->json([
                'ok' => false,
                'error' => $exception->getMessage(),
                'payload' => ['message' => $data->message],
            ]);
        }
    }
}
