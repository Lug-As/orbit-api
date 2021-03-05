<?php


namespace App\Services\Api\V1\Users;


use App\Models\User;
use App\Services\Api\V1\Files\FileService;
use DB;
use Illuminate\Database\Eloquent\Model;

class UserService
{
    /**
     * @var FileService
     */
    private $fileService;

    public function __construct(FileService $fileService)
    {
        $this->fileService = $fileService;
    }

    /**
     * @param User|int $userOrId
     * @param array $data
     */
    public function updateUser($userOrId, array $data)
    {
        $user = $userOrId instanceof User ? $userOrId : User::findOrFail($userOrId);
        DB::transaction(function () use ($user, $data) {
            if (isset($data['email']) and $user->email !== $data['email']) {
                $user->email = $data['email'];
                $user->email_verified_at = null;
                /** @var Model $token */
                $token = $user->currentAccessToken();
                $user->tokens()->whereKeyNot($token->getKey())->delete();
                $user->sendEmailVerificationNotification();
            }
            if (isset($data['image'])) {
                if ($user->image) {
                    $this->fileService->delete($user->getRawImage());
                }
                $data['image'] = $this->fileService->upload($data['image']);
            }
            $user->update($data);
        });
        return $user;
    }
}
