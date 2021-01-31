<?php

namespace App\Http\Controllers\Api\V1\User;

use App\Http\Controllers\Controller;
use App\Services\Api\V1\Users\Resources\UserWithContactsResource;
use Auth;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function show()
    {
        $user = Auth::user();
        return UserWithContactsResource::make($user);
    }
}
