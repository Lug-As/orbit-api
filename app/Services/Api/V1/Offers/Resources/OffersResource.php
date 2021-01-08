<?php


namespace App\Services\Api\V1\Offers\Resources;


use Illuminate\Http\Resources\Json\ResourceCollection;

class OffersResource extends ResourceCollection
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return array_merge($this->resource->toArray(), [
            'data' => OfferResource::collection($this->collection),
        ]);
    }
}
