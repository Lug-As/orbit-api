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
        /** @var self|\App\Models\Project $this */
        return array_merge(parent::toArray($request), [
            'responses' => ResponseNoProjectResource::collection($this->responses),
            'responses_count' => $this->responses->count(),
        ]);
    }
}
