<?php

namespace App\Policies;

use App\Models\Request;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RequestPolicy
{
    use HandlesAuthorization;

    /**
     * Perform pre-authorization checks.
     *
     * @param User $user
     * @return null|bool
     */
    public function before(User $user)
    {
        if ($user->is_admin) {
            return false;
        }
        return null;
    }

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
        return $this->isOwnRequest($user, $request);
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
        return $this->isOwnRequest($user, $request);
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
        return $this->isOwnRequest($user, $request);
    }

    public function cancel()
    {
        return false;
    }

    /**
     * @param User $user
     * @param Request $request
     * @return bool
     */
    protected function isOwnRequest(User $user, Request $request): bool
    {
        return $user->id === $request->user_id;
    }
}
