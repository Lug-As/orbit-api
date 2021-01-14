<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;
use App\Traits\DefaultPolicyFunctions;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProjectPolicy
{
    use HandlesAuthorization, DefaultPolicyFunctions;

    /**
     * Determine whether the user can view any models.
     *
     * @return mixed
     */
    public function viewAny()
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @return mixed
     */
    public function view()
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     *
     * @return mixed
     */
    public function create()
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @param Project $project
     * @return mixed
     */
    public function update(User $user, Project $project)
    {
        return $this->isOwnEntity($user, $project);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param Project $project
     * @return mixed
     */
    public function delete(User $user, Project $project)
    {
        return $this->isOwnEntity($user, $project);
    }
}
