<?php


namespace App\Services\Api\V1\Users\Resources;



class UserWithContactsResource extends UserResource
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
        ]);
    }
}
