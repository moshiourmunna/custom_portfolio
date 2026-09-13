<?php

namespace App\Policies;

use App\Models\User;

class ContentPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['super-admin', 'editor']);
    }

    public function update(User $user): bool
    {
        return $user->hasAnyRole(['super-admin', 'editor']);
    }
}
