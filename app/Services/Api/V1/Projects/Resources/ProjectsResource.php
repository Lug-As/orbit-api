<?php


namespace App\Services\Api\V1\Projects\Resources;


use Illuminate\Http\Resources\Json\ResourceCollection;

class ProjectsResource extends ResourceCollection
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return array_merge($this->resource->toArray(), [
            'data' => ProjectResource::collection($this->collection),
        ]);
    }
}
