<?php


namespace App\Services\Api\V1\Ages\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class AgeResource extends JsonResource
{
    public function toArray($request)
    {
        /** @var self|\App\Models\Age $this */
        return [
            'id' => $this->id,
            'range' => $this->range,
        ];
    }
}
