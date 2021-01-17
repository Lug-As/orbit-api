<?php

namespace App\Policies;

use App\Models\Request;
use App\Models\User;
use App\Traits\DefaultPolicyFunctions;
use Illuminate\Auth\Access\HandlesAuthorization;

class RequestPolicy
{
    use HandlesAuthorization, DefaultPolicyFunctions;

    /**
     * Determine whether the user can view any models.
     *
     * @return bool
     */
    public function viewAny()
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param User $user
     * @param Request $request
     * @return bool
     */
    public function view(User $user, Request $request)
    {
        return $this->isOwnEntity($user, $request);
    }

    /**
     * Determine whether the user can create models.
     *
     * @return bool
     */
    public function create()
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @param Request $request
     * @return bool
     */
    public function update(User $user, Request $request)
    {
        return $this->isOwnEntity($user, $request);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param Request $request
     * @return bool
     */
    public function delete(User $user, Request $request)
    {
        return $this->isOwnEntity($user, $request);
    }

    public function cancel()
    {
        return false;
    }

    public function viewCanceled()
    {
        return true;
    }
}
