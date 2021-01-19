<?php


namespace App\Services\Api\V1\Accounts\Resources;


use App\Services\Api\V1\AdTypes\Resources\AdTypeResourceWithPrice;
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
        return [
            'id' => $this->id,
            'name' => $this->name,
            'image' => $this->image,
            'created_at' => $this->created_at->toDateTimeString(),
            'user' => UserResource::make($this->user),
            'region' => RegionResource::make($this->region),
            'ad_types' => AdTypeResourceWithPrice::collection($this->ad_types),
            'topics' => TopicResource::collection($this->topics),
        ];
    }
}
