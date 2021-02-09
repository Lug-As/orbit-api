<?php


namespace App\Services\Api\V1\Countries\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class CountryResource extends JsonResource
{
    public function toArray($request)
    {
        /** @var self|\App\Models\Country $this */
        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }
}
