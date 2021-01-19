<?php


namespace App\Services\Api\V1\Regions\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class RegionResource extends JsonResource
{
    public function toArray($request)
    {
        /** @var self|\App\Models\Region $this */
        return [
            'id' => $this->id,
            'name' => $this->name,
            'country_name' => $this->country_name,
        ];
    }
}