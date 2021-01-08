<?php


namespace App\Resources;


use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class AccessTokenResource extends JsonResource
{
    /**
     * @var \App\Models\User
     */
    protected $user;

    public function __construct($resource, $user)
    {
        parent::__construct($resource);
        $this->user = $user;
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $tokenName = Str::substr($request->userAgent(), 0, 230) . '|' . $request->ip();
        $this->user->tokens()->where('name', $tokenName)->delete();
        $token = $this->user->createToken($tokenName)->plainTextToken;

        return [
            'token' => $token,
            'token_type' => 'Bearer',
        ];
    }
}
