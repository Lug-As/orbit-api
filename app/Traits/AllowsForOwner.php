<?php


namespace App\Traits;


use App\Models\User;
use Illuminate\Database\Eloquent\Model;

trait AllowsForOwner
{
    /**
     * @param User $user
     * @param Model|mixed $entity
     * @return bool
     */
    protected function isOwnEntity(User $user, $entity): bool
    {
        return $user->id === $entity->user_id;
    }
}
