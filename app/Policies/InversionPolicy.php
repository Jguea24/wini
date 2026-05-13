<?php

namespace App\Policies;

use App\Models\Inversion;
use App\Models\User;

class InversionPolicy
{
    public function update(User $user, Inversion $inversion): bool
    {
        return $user->isAdmin() || $inversion->user_id === $user->id;
    }

    public function delete(User $user, Inversion $inversion): bool
    {
        return $user->isAdmin() || $inversion->user_id === $user->id;
    }
}
