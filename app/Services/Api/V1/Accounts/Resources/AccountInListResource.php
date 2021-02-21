<?php


namespace App\Services\Api\V1\Accounts\Resources;


use App\Services\Api\V1\AdTypes\Resources\AdTypeWithPriceResource;
use App\Services\Api\V1\Topics\Resources\TopicResource;
use App\Services\Api\V1\Users\Resources\UserResource;

class AccountInListResource extends AccountShortResource
{
    public function toArray($request)
    {
        return array_merge(parent::toArray($request), [
            'followers' => $this->followers,
            'likes' => $this->likes,
            'user' => UserResource::make($this->user),
            'ad_types' => AdTypeWithPriceResource::collection($this->ad_types),
            'topics' => TopicResource::collection($this->topics),
        ]);
    }
}
