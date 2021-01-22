<?php


namespace App\Services\Api\V1\ImageRequests;


use App\Models\ImageRequest;
use App\Services\Api\V1\Files\FileService;
use File;

class ImageRequestService
{
    public function destroyImageRequest($id)
    {
        $imageRequest = ImageRequest::findOrFail($id);
        File::delete(public_path(FileService::UPLOAD_DIR . '/' . $imageRequest->getRawSrc()));
        return $imageRequest->delete();
    }

    public function getImageRequestOnlyUserId($id)
    {
        return ImageRequest::with(['request.user_id'])->findOrFail($id, ['id']);
    }
}
