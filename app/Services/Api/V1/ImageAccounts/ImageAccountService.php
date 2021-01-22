<?php


namespace App\Services\Api\V1\ImageAccounts;


use App\Models\ImageAccount;

class ImageAccountService
{
    public function destroyImageAccount($id)
    {
        return ImageAccount::whereId($id)->delete();
    }

    public function getImageAccountOnlyUserId($id)
    {
        return ImageAccount::with(['account.user_id'])->findOrFail($id, ['id']);
    }
}
