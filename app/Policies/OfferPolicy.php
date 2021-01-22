<?php

namespace App\Policies;

use App\Models\Account;
use App\Models\Offer;
use App\Models\User;
use App\Traits\DefaultPolicyFunctions;
use Illuminate\Auth\Access\HandlesAuthorization;

class OfferPolicy
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
     * Determine whether the user can view own models.
     *
     * @return bool
     */
    public function viewOwn()
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param User $user
     * @param Offer $offer
     * @return bool
     */
    public function view(User $user, Offer $offer)
    {
        return $user->id === $offer->account->user_id
            || $this->isOwnEntity($user, $offer);
    }

    public function viewByAccount(User $user, Account $account)
    {
        return $this->isOwnEntity($user, $account);
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
     * @param Offer $offer
     * @return bool
     */
    public function update(User $user, Offer $offer)
    {
        return $this->isOwnEntity($user, $offer);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param Offer $offer
     * @return bool
     */
    public function delete(User $user, Offer $offer)
    {
        return $this->isOwnEntity($user, $offer);
    }
}
