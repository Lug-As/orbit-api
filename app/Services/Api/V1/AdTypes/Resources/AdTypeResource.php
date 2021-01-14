<?php


namespace App\Services\Api\V1\AdTypes\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class AdTypeResource extends JsonResource
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
        ];
    }
}
