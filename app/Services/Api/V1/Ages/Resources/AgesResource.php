<?php


namespace App\Services\Api\V1\Ages\Resources;


use Illuminate\Http\Resources\Json\ResourceCollection;

class AgesResource extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data' => AgeResource::collection($this->collection),
        ];
    }
}
