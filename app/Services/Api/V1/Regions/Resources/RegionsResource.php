<?php


namespace App\Services\Api\V1\Regions\Resources;


use Illuminate\Http\Resources\Json\ResourceCollection;

class RegionsResource extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data' => RegionResource::collection($this->collection),
        ];
    }
}
