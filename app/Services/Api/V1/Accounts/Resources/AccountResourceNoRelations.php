<?php


namespace App\Services\Api\V1\Accounts\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class AccountResourceNoRelations extends JsonResource
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
        ];
    }
}
