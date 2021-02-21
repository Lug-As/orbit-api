<?php


namespace App\Services\Api\V1\Accounts\Resources;


use App\Services\Api\V1\AdTypes\Resources\AdTypeWithPriceResource;
use App\Services\Api\V1\Ages\Resources\AgeResource;
use App\Services\Api\V1\Regions\Resources\RegionResourceResource;
use App\Services\Api\V1\Topics\Resources\TopicResource;
use App\Services\Api\V1\Users\Resources\UserResource;

class AccountResource extends AccountNoRelationsResource
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        /** @var self|\App\Models\Account $this */
        return array_merge(parent::toArray($request), [
            'ad_types' => AdTypeWithPriceResource::collection($this->ad_types),
            'topics' => TopicResource::collection($this->topics),
            'ages' => AgeResource::collection($this->ages),
            'region' => RegionResourceResource::make($this->region),
            'user' => UserResource::make($this->user),
        ]);
    }
}
