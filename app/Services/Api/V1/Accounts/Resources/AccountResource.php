<?php


namespace App\Services\Api\V1\Accounts\Resources;


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
            'user' => UserResource::make($this->user),
            'image' => $this->image,
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
