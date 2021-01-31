<?php


namespace App\Policies;


use App\Models\Account;
use App\Models\User;
use App\Traits\DefaultPolicyFunctions;
use Illuminate\Auth\Access\HandlesAuthorization;

class AccountPolicy
{
    use HandlesAuthorization, DefaultPolicyFunctions;

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @param Account $account
     * @return bool
     */
    public function update(User $user, Account $account)
    {
        return $this->isOwnEntity($user, $account);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param Account $account
     * @return bool
     */
    public function delete(User $user, Account $account)
    {
        return $this->isOwnEntity($user, $account);
    }
}
