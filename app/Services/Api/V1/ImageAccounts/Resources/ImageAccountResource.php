<?php


namespace App\Services\Api\V1\ImageAccounts\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class ImageAccountResource extends JsonResource
{
    public function toArray($request)
    {
        /** @var self|\App\Models\ImageAccount $this */
        return [
            'src' => $this->src,
        ];
    }
}
