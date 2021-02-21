<?php


namespace App\Services\Api\V1\Regions;


use App\Models\Country;
use App\Services\Api\V1\Countries\Resources\CountriesRegionsResource;

class RegionService
{
    public function searchRegions()
    {
        return CountriesRegionsResource::make(Country::with('regions')->get());
    }
}
