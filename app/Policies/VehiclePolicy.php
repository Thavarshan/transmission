<?php

namespace App\Policies;

use App\Models\User;

class VehiclePolicy
{
    /**
     * Determine whether the user can view any models.
     *
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return $user->is(auth()->user());
    }
}
