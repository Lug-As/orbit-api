<?php


namespace App\Services\Api\V1\Ages;


use App\Models\Age;
use App\Services\Api\V1\Ages\Resources\AgesResource;

class AgeService
{
    public function searchAges()
    {
        return AgesResource::make(Age::all());
    }
}
