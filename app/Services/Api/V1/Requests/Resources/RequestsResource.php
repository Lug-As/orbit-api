<?php


namespace App\Services\Api\V1\Requests\Resources;


use Illuminate\Http\Resources\Json\ResourceCollection;

class RequestsResource extends ResourceCollection
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return array_merge($this->resource->toArray(), [
            'data' => RequestResource::collection($this->collection),
        ]);
    }
}
