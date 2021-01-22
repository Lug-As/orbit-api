<?php


namespace App\Services\Api\V1\ImageRequests\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class ImageRequestResource extends JsonResource
{
    public function toArray($request)
    {
        /** @var self|\App\Models\ImageRequest $this */
        return [
            'src' => $this->src,
        ];
    }
}
