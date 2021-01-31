<?php


namespace App\Services\Api\V1\Users\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray($request)
    {
        /** @var self|\App\Models\User $this */
        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }
}
