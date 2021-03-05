<?php


namespace App\Services\Api\V1\Accounts\Resources;


class AccountWithContactsResource extends AccountShortResource
{
    public function toArray($request)
    {
        /** @var self|\App\Models\Account $this */
        return array_merge(parent::toArray($request), [
            'name' => $this->user->name,
            'email' => $this->user->email,
            'phone' => $this->user->phone,
            'telegram' => $this->user->telegram,
        ]);
    }
}
