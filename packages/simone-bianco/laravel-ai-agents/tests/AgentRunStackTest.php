<?php

declare(strict_types=1);

use SimoneBianco\LaravelAiAgents\Support\AgentRunStack;

it('detects cycle when same slug is pushed twice', function () {
    $stack = new AgentRunStack();
    $stack->push('agent-a');
    expect($stack->has('agent-a'))->toBeTrue();
});

it('tracks depth correctly', function () {
    $stack = new AgentRunStack();
    $stack->push('a');
    $stack->push('b');
    expect($stack->depth())->toBe(2);
    $stack->pop('b');
    expect($stack->depth())->toBe(1);
});
