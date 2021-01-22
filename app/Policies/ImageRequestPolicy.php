<?php


namespace App\Policies;


use App\Models\ImageRequest;
use App\Models\User;
use App\Traits\DefaultPolicyFunctions;
use Illuminate\Auth\Access\HandlesAuthorization;

class ImageRequestPolicy
{
    use HandlesAuthorization, DefaultPolicyFunctions;

    public function delete(User $user, ImageRequest $imageRequest)
    {
        return $user->id === $imageRequest->request->user_id;
    }
}
