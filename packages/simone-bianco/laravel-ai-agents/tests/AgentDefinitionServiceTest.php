<?php

declare(strict_types=1);

use SimoneBianco\LaravelAiAgents\Exceptions\AgentCurrentlyRunningException;
use SimoneBianco\LaravelAiAgents\Exceptions\AgentIsSystemException;
use SimoneBianco\LaravelAiAgents\Exceptions\AgentLockedException;

it('throws AgentLockedException when agent is locked', function () {
    $agent = new class {
        public bool $is_locked = true;
        public int $active_runs_count = 0;
        public bool $is_system = false;
        public string $slug = 'locked';
    };

    $guard = function ($a): void {
        if ($a->is_locked) {
            throw new AgentLockedException("Agent '{$a->slug}' is locked");
        }
        if ($a->active_runs_count > 0) {
            throw new AgentCurrentlyRunningException("running");
        }
        if ($a->is_system) {
            throw new AgentIsSystemException("system");
        }
    };

    expect(fn () => $guard($agent))->toThrow(AgentLockedException::class);
});

it('throws AgentCurrentlyRunningException when active_runs_count > 0', function () {
    $agent = new class {
        public bool $is_locked = false;
        public int $active_runs_count = 2;
        public bool $is_system = false;
        public string $slug = 'busy';
    };

    $guard = function ($a): void {
        if ($a->is_locked) {
            throw new AgentLockedException("locked");
        }
        if ($a->active_runs_count > 0) {
            throw new AgentCurrentlyRunningException("Agent '{$a->slug}' is currently running");
        }
        if ($a->is_system) {
            throw new AgentIsSystemException("system");
        }
    };

    expect(fn () => $guard($agent))->toThrow(AgentCurrentlyRunningException::class);
});

it('throws AgentIsSystemException for system agents', function () {
    $agent = new class {
        public bool $is_locked = false;
        public int $active_runs_count = 0;
        public bool $is_system = true;
        public string $slug = 'sys';
    };

    $guard = function ($a): void {
        if ($a->is_locked) {
            throw new AgentLockedException("locked");
        }
        if ($a->active_runs_count > 0) {
            throw new AgentCurrentlyRunningException("running");
        }
        if ($a->is_system) {
            throw new AgentIsSystemException("Agent '{$a->slug}' is a system agent");
        }
    };

    expect(fn () => $guard($agent))->toThrow(AgentIsSystemException::class);
});
