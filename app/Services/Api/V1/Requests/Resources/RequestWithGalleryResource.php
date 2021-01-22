<?php


namespace App\Services\Api\V1\Requests\Resources;


use App\Services\Api\V1\ImageRequests\Resources\ImageRequestResource;

class RequestWithGalleryResource extends RequestResource
{
    public function toArray($request)
    {
        /** @var self|\App\Models\Request $this */
        return array_merge(parent::toArray($request), [
            'gallery' => ImageRequestResource::collection($this->images)
        ]);
    }
}
