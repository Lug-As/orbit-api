<?php


namespace App\Services\Api\V1\Accounts\Resources;


use App\Services\Api\V1\AdTypes\Resources\AdTypeWithPriceResource;
use App\Services\Api\V1\Ages\Resources\AgeResource;
use App\Services\Api\V1\Regions\Resources\RegionResource;
use App\Services\Api\V1\Topics\Resources\TopicResource;
use App\Services\Api\V1\Users\Resources\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class AccountResource extends JsonResource
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        /** @var self|\App\Models\Account $this */
        return array_merge(AccountResourceNoRelations::make($this->resource)->toArray($request), [
            'ad_types' => AdTypeWithPriceResource::collection($this->ad_types),
            'topics' => TopicResource::collection($this->topics),
            'ages' => AgeResource::collection($this->ages),
            'region' => RegionResource::make($this->region),
            'user' => UserResource::make($this->user),
        ]);
    }
}
