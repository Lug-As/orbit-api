<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    use VerifiesEmails;

    protected function verified(Request $request)
    {
        return response()->json([
            'user_id' => $request->user()->id,
            'verifyed' => true,
        ]);
    }

    public function show(Request $request) // verification notice
    {
        return $request->user()->hasVerifiedEmail()
            ? redirect($this->redirectPath())
            : $this->canBeResend($request);
    }

    protected function canBeResend(Request $request)
    {
        return response()->json([
            'user_id' => $request->user()->id,
            'verifyed' => false,
        ]);
    }
}
