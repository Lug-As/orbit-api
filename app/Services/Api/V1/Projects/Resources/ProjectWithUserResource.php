<?php


namespace App\Services\Api\V1\Projects\Resources;


use App\Services\Api\V1\Users\Resources\UserResource;

class ProjectWithUserResource extends ProjectNoRelationsResource
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        /** @var self|\App\Models\Project $this */
        return array_merge(parent::toArray($request), [
            'user' => UserResource::make($this->user),
        ]);
    }
}
