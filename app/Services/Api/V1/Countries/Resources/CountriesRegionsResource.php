<?php


namespace App\Services\Api\V1\Countries\Resources;


use Illuminate\Http\Resources\Json\ResourceCollection;

class CountriesRegionsResource extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data' => CountryRegionsResource::collection($this->collection),
        ];
    }
}
