<?php


namespace App\Services\Api\V1\Regions\Resources;


use App\Services\Api\V1\Countries\Resources\CountryResource;

class RegionResource extends RegionNoCountryResource
{
    public function toArray($request)
    {
        /** @var self|\App\Models\Region $this */
        return array_merge(parent::toArray($request), [
            'country' => CountryResource::make($this->country),
        ]);
    }
}
