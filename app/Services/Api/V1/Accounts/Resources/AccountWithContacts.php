<?php


namespace App\Services\Api\V1\Accounts\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class AccountWithContacts extends JsonResource
{
    public function toArray($request)
    {
        /** @var self|\App\Models\Account $this */
        return array_merge(AccountResource::make($this->resource)->toArray($request), [
            'email' => $this->email,
            'phone' => $this->phone,
            'telegram' => $this->telegram,
        ]);
    }
}
