<?php

namespace App\Http\Controllers\Api\V1\Project;

use App\Http\Controllers\Api\V1\Project\FormRequests\StoreProjectRequest;
use App\Http\Controllers\Api\V1\Project\FormRequests\UpdateProjectRequest;
use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Services\Api\V1\Projects\ProjectService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    protected $projectService;

    public function __construct(ProjectService $projectService)
    {
        $this->projectService = $projectService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        $this->authorize('viewAny', Project::class);
        return response()->json($this->projectService->searchProjects());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreProjectRequest $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        $this->authorize('create', Project::class);
        $result = $this->projectService->storeProject($request->getFormData());
        return response()->json($result, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show($id)
    {
        $this->authorize('view', Project::class);
        return response()->json($this->projectService->findProject($id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateProjectRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, $id)
    {
        $this->authorize('update', $this->projectService->getProjectOnlyUserId($id));
        $result = $this->projectService->updateProject($request->getFormData(), $id);
        return response()->json($result);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        $this->authorize('delete', $this->projectService->getProjectOnlyUserId($id));
        $this->projectService->destroyProject($id);
        return response()->json([], 204);
    }
}
