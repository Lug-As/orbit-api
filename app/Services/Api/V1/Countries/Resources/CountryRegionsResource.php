<?php


namespace App\Services\Api\V1\Countries\Resources;


use App\Services\Api\V1\Regions\Resources\RegionNoCountryResource;

class CountryRegionsResource extends CountryResource
{
    public function toArray($request)
    {
        /** @var self|\App\Models\Country $this */
        return array_merge(parent::toArray($request), [
            'regions' => RegionNoCountryResource::collection($this->regions),
        ]);
    }
}
