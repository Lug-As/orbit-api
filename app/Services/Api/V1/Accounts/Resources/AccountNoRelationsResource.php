<?php


namespace App\Services\Api\V1\Accounts\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class AccountNoRelationsResource extends JsonResource
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'about' => $this->about,
            'image' => $this->image,
            'likes' => $this->likes,
            'followers' => $this->followers,
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}