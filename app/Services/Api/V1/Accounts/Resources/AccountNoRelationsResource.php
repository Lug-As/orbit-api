<?php


namespace App\Services\Api\V1\Accounts\Resources;


class AccountNoRelationsResource extends AccountShortResource
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        /** @var self|\App\Models\Account $this */
        return array_merge(parent::toArray($request), [
            'id' => $this->id,
            'title' => $this->title,
            'about' => $this->about,
            'image' => $this->image,
            'likes' => $this->likes,
            'followers' => $this->followers,
            'created_at' => $this->created_at->toDateTimeString(),
        ]);
    }
}
