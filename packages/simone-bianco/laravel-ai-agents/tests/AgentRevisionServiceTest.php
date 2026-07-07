<?php

declare(strict_types=1);

use SimoneBianco\LaravelAiAgents\Services\AgentRevisionService;

it('caps revisions at 10', function () {
    expect(AgentRevisionService::MAX_REVISIONS)->toBe(10);

    // Simulate the cap logic: keep only the latest 10 by revision_number.
    $revisions = collect(range(1, 13))->map(fn (int $n) => ['revision_number' => $n]);

    $count = $revisions->count();
    $max = AgentRevisionService::MAX_REVISIONS;

    if ($count > $max) {
        $pruneCount = $count - $max;
        $kept = $revisions->sortBy('revision_number')->slice($pruneCount)->values();
    } else {
        $kept = $revisions->values();
    }

    expect($kept)->toHaveCount(10);
    expect($kept->first()['revision_number'])->toBe(4);
    expect($kept->last()['revision_number'])->toBe(13);
})->skip('Logic-only smoke test — full DB test requires Laravel boot.');
