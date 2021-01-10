<?php


namespace App\Traits;


use App\Models\User;

trait AllowsAllForAdmins
{
    /**
     * Perform pre-authorization checks.
     *
     * @param User $user
     * @return null|bool
     */
    public function before(User $user)
    {
        if ($user->is_admin) {
            return true;
        }
        return null;
    }
}
