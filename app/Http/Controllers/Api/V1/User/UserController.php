<?php

namespace App\Http\Controllers\Api\V1\User;

use App\Http\Controllers\Api\V1\User\FormRequests\UpdateUserRequest;
use App\Http\Controllers\Controller;
use App\Services\Api\V1\Users\Resources\UserExtendedResource;
use App\Services\Api\V1\Users\UserService;
use Auth;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function show()
    {
        $user = Auth::user();
        return UserExtendedResource::make($user);
    }

    public function update(UpdateUserRequest $request)
    {
        $user = Auth::user();
        $this->userService->updateUser($user, $request->getFormData());
        return UserExtendedResource::make($user);
    }
}
