<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    use VerifiesEmails;

    /**
     * Mark the authenticated user's email address as verified.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function verify(Request $request)
    {
        $id = $request->route('id');
        $user = $request->user() ?? User::find($id);
        if (
            !$user
            || !hash_equals((string) $id, (string) $user->getKey())
            || !hash_equals((string) $request->route('hash'), sha1($user->getEmailForVerification()))
        ) {
            throw new AuthorizationException;
        }
        if (!$user->hasVerifiedEmail()) {
            if ($user->markEmailAsVerified()) {
                event(new Verified($user));
            }
        }
        return $request->wantsJson()
            ? response()->json([
                'user_id' => $user->id,
                'verifyed' => true,
            ])
            : redirect('/');
    }
}
