<?php


namespace App\Services\Api\V1\Tokens;


use Illuminate\Support\Str;

class AccessTokenService
{
    public static function generate($request, $user)
    {
        $tokenName = Str::substr($request->userAgent(), 0, 230) . '|' . $request->ip();
        $user->tokens()->where('name', $tokenName)->delete();
        $token = $user->createToken($tokenName)->plainTextToken;
        return [
            'token' => $token,
            'token_type' => 'Bearer',
        ];
    }
}
