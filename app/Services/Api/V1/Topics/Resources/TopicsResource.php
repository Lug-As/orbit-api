<?php


namespace App\Services\Api\V1\Topics\Resources;


use Illuminate\Http\Resources\Json\ResourceCollection;

class TopicsResource extends ResourceCollection
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => TopicResource::collection($this->collection),
        ];
    }
}
