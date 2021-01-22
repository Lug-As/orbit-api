<?php


namespace App\Services\Api\V1\ImageAccounts;


use App\Models\ImageAccount;
use App\Services\Api\V1\Files\FileService;
use File;

class ImageAccountService
{
    /**
     * @param ImageAccount|int $imageAccountOrId
     * @return bool|null
     * @throws \Exception
     */
    public function destroyImageAccount($imageAccountOrId)
    {
        $imageAccount = $imageAccountOrId instanceof ImageAccount ? $imageAccountOrId
            : ImageAccount::findOrFail($imageAccountOrId);
        File::delete(public_path(FileService::UPLOAD_DIR . '/' . $imageAccount->getRawSrc()));
        return $imageAccount->delete();
    }

    public function getImageAccountOnlyUserId($id)
    {
        return ImageAccount::with(['account.user_id'])->findOrFail($id, ['id']);
    }
}
