<?php

namespace App\Http\Controllers\Api\V1\Topic;

use App\Http\Controllers\Controller;
use App\Services\Api\V1\Topics\TopicService;
use Illuminate\Http\Request;

class TopicController extends Controller
{
    /**
     * @var TopicService
     */
    private $topicService;

    public function __construct(TopicService $topicService)
    {
        $this->topicService = $topicService;
    }

    public function index()
    {
        return response()->json($this->topicService->searchTopics());
    }
}
