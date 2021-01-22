<?php


namespace App\Services\Api\V1\Accounts\Resources;


use App\Services\Api\V1\ImageAccounts\Resources\ImageAccountResource;

class AccountWithGalleryResource extends AccountResource
{
    public function toArray($request)
    {
        /** @var self|\App\Models\Account $this */
        return array_merge(parent::toArray($request), [
            'gallery' => ImageAccountResource::collection($this->images)
        ]);
    }
}
