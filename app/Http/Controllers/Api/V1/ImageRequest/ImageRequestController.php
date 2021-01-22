<?php

namespace App\Http\Controllers\Api\V1\ImageRequest;

use App\Http\Controllers\Controller;
use App\Services\Api\V1\ImageRequests\ImageRequestService;
use Illuminate\Http\JsonResponse;

class ImageRequestController extends Controller
{
    protected $imageRequestService;

    public function __construct(ImageRequestService $imageRequestService)
    {
        $this->imageRequestService = $imageRequestService;
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        $this->authorize('delete', $this->imageRequestService->getImageRequestOnlyUserId($id));
        $this->imageRequestService->destroyImageRequest($id);
        return response()->json([], 204);
    }
}
