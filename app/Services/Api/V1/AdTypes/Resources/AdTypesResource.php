<?php


namespace App\Services\Api\V1\AdTypes\Resources;


use Illuminate\Http\Resources\Json\ResourceCollection;

class AdTypesResource extends ResourceCollection
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => AdTypeResource::collection($this->collection),
        ];
    }
}
