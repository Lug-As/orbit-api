<?php


namespace App\Services\Api\V1\Projects\Resources;


use App\Services\Api\V1\Responses\Resources\ResponseResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ResponsesResource extends ResourceCollection
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return array_merge($this->resource->toArray(), [
            'data' => ResponseResource::collection($this->collection),
        ]);
    }
}
