<?php


namespace App\Services\Api\V1\Requests\Resources;


use App\Services\Api\V1\Accounts\Resources\AccountResourceNoRelations;
use App\Services\Api\V1\AdTypes\Resources\AdTypeResourceWithPrice;
use App\Services\Api\V1\Topics\Resources\TopicResource;
use App\Services\Api\V1\Users\Resources\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class RequestResource extends JsonResource
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
            'checked' => $this->checked,
            'created_at' => $this->created_at->toDateTimeString(),
            'is_approved' => $this->isApproved(),
            'fail_msg' => $this->fail_msg,
            'account' => $this->account ? AccountResourceNoRelations::make($this->account) : null,
            'user' => UserResource::make($this->user),
            'ad_types' => AdTypeResourceWithPrice::collection($this->ad_types),
            'topics' => TopicResource::collection($this->topics),
        ];
    }
}
