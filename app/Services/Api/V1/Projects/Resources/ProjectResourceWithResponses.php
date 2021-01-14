<?php


namespace App\Services\Api\V1\Projects\Resources;


use App\Services\Api\V1\Responses\Resources\ResponseResourceNoProject;

class ProjectResourceWithResponses extends ProjectResource
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $withoutResponses = parent::toArray($request);
        unset($withoutResponses['responses_count']);
        return array_merge($withoutResponses, [
            'responses' => ResponseResourceNoProject::collection($this->responses)
        ]);
    }
}
