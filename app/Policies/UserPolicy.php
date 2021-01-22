<?php

namespace App\Policies;

use App\Models\User;
use App\Traits\DefaultPolicyFunctions;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function show(User $user, User $showingUser)
    {
        return $user->id === $showingUser->id;
    }
}
