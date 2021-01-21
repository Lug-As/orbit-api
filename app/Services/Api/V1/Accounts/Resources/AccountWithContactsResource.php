<?php


namespace App\Services\Api\V1\Accounts\Resources;


class AccountWithContactsResource extends AccountResource
{
    public function toArray($request)
    {
        /** @var self|\App\Models\Account $this */
        return array_merge(parent::toArray($request), [
            'email' => $this->email,
            'phone' => $this->phone,
            'telegram' => $this->telegram,
        ]);
    }
}
