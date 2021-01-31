<?php


namespace App\Services\Api\V1\Accounts\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class AccountShortResource extends JsonResource
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        /** @var self|\App\Models\Account $this */
        return [
            'id' => $this->id,
            'title' => $this->title,
            'image' => $this->image,
        ];
    }
}
