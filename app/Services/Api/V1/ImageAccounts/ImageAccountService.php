<?php


namespace App\Services\Api\V1\ImageAccounts;


use App\Models\ImageAccount;
use App\Services\Api\V1\Files\FileService;
use File;

class ImageAccountService
{
    protected $fileService;

    public function __construct(FileService $fileService)
    {
        $this->fileService = $fileService;
    }

    /**
     * @param ImageAccount|int $imageAccountOrId
     * @return bool|null
     * @throws \Exception
     */
    public function destroyImageAccount($imageAccountOrId)
    {
        $imageAccount = $imageAccountOrId instanceof ImageAccount ? $imageAccountOrId
            : ImageAccount::findOrFail($imageAccountOrId);
        $this->fileService->delete($imageAccount->getRawSrc());
        return $imageAccount->delete();
    }

    public function getImageAccountOnlyUserId($id)
    {
        return ImageAccount::with(['account.user_id'])->findOrFail($id, ['id']);
    }
}
