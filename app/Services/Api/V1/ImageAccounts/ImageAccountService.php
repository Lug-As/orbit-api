<?php


namespace App\Services\Api\V1\ImageAccounts;


use App\Models\ImageAccount;
use App\Services\Api\V1\Files\FileService;
use File;

class ImageAccountService
{
    public function destroyImageAccount($id)
    {
        $imageAccount = ImageAccount::findOrFail($id);
        File::delete(public_path(FileService::UPLOAD_DIR . '/' . $imageAccount->getRawSrc()));
        return $imageAccount->delete();
    }

    public function getImageAccountOnlyUserId($id)
    {
        return ImageAccount::with(['account.user_id'])->findOrFail($id, ['id']);
    }
}
