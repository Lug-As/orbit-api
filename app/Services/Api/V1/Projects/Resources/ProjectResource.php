<?php


namespace App\Services\Api\V1\Projects\Resources;


use App\Services\Api\V1\AdTypes\Resources\AdTypeResource;
use App\Services\Api\V1\Regions\Resources\RegionResource;
use App\Services\Api\V1\Users\Resources\UserResource;

class ProjectResource extends ProjectNoRelationsResource
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
            'region' => RegionResource::make($this->region),
            'ad_types' => AdTypeResource::collection($this->ad_types),
            'responses_count' => $this->responses_count,
        ]);
    }
}
