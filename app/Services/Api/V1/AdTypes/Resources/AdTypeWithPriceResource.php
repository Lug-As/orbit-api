<?php


namespace App\Services\Api\V1\AdTypes\Resources;


class AdTypeWithPriceResource extends AdTypeResource
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return array_merge(parent::toArray($request), [
            'price' => $this->pivot ? $this->pivot->price : null,
        ]);
    }
}
