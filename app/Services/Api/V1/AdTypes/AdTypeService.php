<?php


namespace App\Services\Api\V1\AdTypes;


use App\Models\AdType;
use App\Services\Api\V1\AdTypes\Resources\AdTypesResource;

class AdTypeService
{
    public function searchAdTypes()
    {
        return AdTypesResource::make(AdType::all());
    }
}
