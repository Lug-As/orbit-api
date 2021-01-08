<?php


namespace App\Services\Api\V1\Offers\Resources;


use App\Services\Api\V1\Accounts\Resources\AccountResource;
use App\Services\Api\V1\Users\Resources\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class OfferResource extends JsonResource
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'text' => $this->text,
            'user' => UserResource::make($this->user),
            'account' => AccountResource::make($this->account),
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
