<?php

namespace App\Policies;

use App\Models\Account;
use App\Models\Project;
use App\Models\Response;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ResponsePolicy
{
    use HandlesAuthorization;

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
     * @param Response $response
     * @return bool
     */
    public function view(User $user, Response $response)
    {
        return $user->id === $response->account->user_id
            || $user->id === $response->project->user_id;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param User $user
     * @return bool
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @param Response $response
     * @return bool
     */
    public function update(User $user, Response $response)
    {
        return $user->id === $response->account->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param Response $response
     * @return bool
     */
    public function delete(User $user, Response $response)
    {
        return $user->id === $response->account->user_id;
    }

    public function ownProjectIndex(User $user, Project $project)
    {
        return $user->id === $project->user_id;
    }

    public function ownAccountIndex(User $user, Account $account)
    {
        return $user->id === $account->user_id;
    }
}
