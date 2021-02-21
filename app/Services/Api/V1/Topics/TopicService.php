<?php


namespace App\Services\Api\V1\Topics;


use App\Models\Topic;
use App\Services\Api\V1\Topics\Resources\TopicsResource;

class TopicService
{
    public function searchTopics()
    {
        return TopicsResource::make(Topic::all());
    }
}
