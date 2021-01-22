<?php


namespace App\Services\Api\V1\Projects\Resources;


use App\Services\Api\V1\Responses\Resources\ResponseNoProjectResource;

class ProjectWithResponsesResource extends ProjectResource
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return array_merge(parent::toArray($request), [
            'responses' => ResponseNoProjectResource::collection($this->responses)
        ]);
    }
}
