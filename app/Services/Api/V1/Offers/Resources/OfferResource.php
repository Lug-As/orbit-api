<?php


namespace App\Services\Api\V1\Offers\Resources;


use App\Services\Api\V1\Accounts\Resources\AccountShortResource;
use App\Services\Api\V1\Users\Resources\UserWithContactsResource;
use Illuminate\Http\Resources\Json\JsonResource;

class OfferResource extends JsonResource
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        /** @var self|\App\Models\Offer $this */
        return [
            'id' => $this->id,
            'text' => $this->text,
            'user' => UserWithContactsResource::make($this->user),
            'account' => AccountShortResource::make($this->account),
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
