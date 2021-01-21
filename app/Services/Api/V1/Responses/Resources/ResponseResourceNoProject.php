<?php


namespace App\Services\Api\V1\Responses\Resources;


use App\Services\Api\V1\Accounts\Resources\AccountNoRelationsResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ResponseResourceNoProject extends JsonResource
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
            'account' => AccountNoRelationsResource::make($this->account),
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
