<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;

final class UserPolicy
{
    public function viewAny(User $actor): bool
    {
        return $actor->is_admin;
    }

    public function view(User $actor, User $user): bool
    {
        return $actor->is_admin;
    }

    public function create(User $actor): bool
    {
        return $actor->is_admin;
    }

    public function update(User $actor, User $user): bool
    {
        return $actor->is_admin;
    }

    public function delete(User $actor, User $user): bool
    {
        return $actor->is_admin;
    }
}
