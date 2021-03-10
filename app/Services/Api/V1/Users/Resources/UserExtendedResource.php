<?php


namespace App\Services\Api\V1\Users\Resources;


class UserExtendedResource extends UserResource
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        /** @var self|\App\Models\User $this */
        return array_merge(parent::toArray($request), [
            'phone' => $this->phone,
            'email' => $this->email,
            'telegram' => $this->telegram,
            'verifyed' => $this->hasVerifiedEmail(),
            'is_admin' => $this->is_admin,
        ]);
    }
}
