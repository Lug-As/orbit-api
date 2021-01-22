<?php

namespace App\Http\Controllers\Api\V1\ImageAccount;

use App\Http\Controllers\Controller;
use App\Services\Api\V1\ImageAccounts\ImageAccountService;
use Illuminate\Http\JsonResponse;

class ImageAccountController extends Controller
{
    protected $imageAccountService;

    public function __construct(ImageAccountService $imageAccountService)
    {
        $this->imageAccountService = $imageAccountService;
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        $this->authorize('delete', $this->imageAccountService->getImageAccountOnlyUserId($id));
        $this->imageAccountService->destroyImageAccount($id);
        return response()->json([], 204);
    }
}
