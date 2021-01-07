<?php


namespace App\Services\Api\V1\AdTypes\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class AdTypeResourceWithPrice extends JsonResource
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return array_merge(AdTypeResource::make($this->resource)->toArray($request), [
            'price' => $this->pivot->price,
        ]);
    }
}
