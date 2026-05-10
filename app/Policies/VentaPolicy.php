<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Venta;

class VentaPolicy
{
    public function update(User $user, Venta $venta): bool
    {
        return $user->isAdmin() || $venta->user_id === $user->id;
    }

    public function delete(User $user, Venta $venta): bool
    {
        return $user->isAdmin() || $venta->user_id === $user->id;
    }
}
