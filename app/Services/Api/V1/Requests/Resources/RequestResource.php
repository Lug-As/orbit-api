<?php


namespace App\Services\Api\V1\Requests\Resources;


use App\Services\Api\V1\Accounts\Resources\AccountNoRelationsResource;
use App\Services\Api\V1\AdTypes\Resources\AdTypeWithPriceResource;
use App\Services\Api\V1\Ages\Resources\AgeResource;
use App\Services\Api\V1\Regions\Resources\RegionResourceResource;
use App\Services\Api\V1\Topics\Resources\TopicResource;
use App\Services\Api\V1\Users\Resources\UserWithContactsResource;
use Illuminate\Http\Resources\Json\JsonResource;

class RequestResource extends JsonResource
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        /** @var self|\App\Models\Request $this */
        return [
            'id' => $this->id,
            'name' => $this->name,
            'about' => $this->about,
            'image' => $this->image,
            'checked' => $this->checked,
            'created_at' => $this->created_at->toDateTimeString(),
            'is_approved' => $this->isApproved(),
            'is_canceled' => $this->isCanceled(),
            'fail_msg' => $this->fail_msg,
            'account' => $this->account ? AccountNoRelationsResource::make($this->account) : null,
            'user' => UserWithContactsResource::make($this->user),
            'region' => RegionResourceResource::make($this->region),
            'ad_types' => AdTypeWithPriceResource::collection($this->ad_types),
            'topics' => TopicResource::collection($this->topics),
            'ages' => AgeResource::collection($this->ages),
        ];
    }
}
