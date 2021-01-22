<?php


namespace App\Policies;


use App\Models\ImageAccount;
use App\Models\User;
use App\Traits\DefaultPolicyFunctions;
use Illuminate\Auth\Access\HandlesAuthorization;

class ImageAccountPolicy
{
    use HandlesAuthorization, DefaultPolicyFunctions;

    public function delete(User $user, ImageAccount $imageAccount)
    {
        return $user->id === $imageAccount->account->user_id;
    }
}
