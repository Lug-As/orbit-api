<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Services\Api\V1\Tokens\AccessTokenService;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    protected function sendLoginResponse(Request $request)
    {
        $this->clearLoginAttempts($request);
        if ($response = $this->authenticated($request, $this->guard()->user())) {
            return $response;
        }
        return response()->json([], 204);
    }

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    protected $accessTokenService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(AccessTokenService $accessTokenService)
    {
        $this->middleware('guest')->except('logout');
        $this->accessTokenService = $accessTokenService;
    }

    protected function authenticated(Request $request, $user)
    {
        return response()->json($this->accessTokenService->generate($request, $user));
    }
}
