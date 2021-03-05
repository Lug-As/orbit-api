<?php

namespace App\Http\Controllers\Api\V1\Response;

use App\Http\Controllers\Api\V1\Response\FormRequests\StoreResponseRequest;
use App\Http\Controllers\Api\V1\Response\FormRequests\UpdateResponseRequest;
use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Project;
use App\Models\Response;
use App\Services\Api\V1\Responses\ResponseService;
use Illuminate\Http\JsonResponse;

class ResponseController extends Controller
{
    protected $responseService;

    public function __construct(ResponseService $responseService)
    {
        $this->responseService = $responseService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        $this->authorize('viewAny', Response::class);
        return response()->json($this->responseService->searchResponses());
    }

    public function ownAccountIndex($account_id)
    {
        $this->authorize('ownAccountIndex', Account::findOrFail($account_id, ['user_id']));
        return response()->json($this->responseService->searchByAccount($account_id));
    }

    public function ownProjectIndex($project_id)
    {
        $this->authorize('ownProjectIndex', Project::findOrFail($project_id, ['user_id']));
        return response()->json($this->responseService->searchByProject($project_id));
    }

    public function ownProjectAccountIndex($project_id, $account_id)
    {
        $this->authorize('ownProjectAccountIndex', [Response::class, Account::find($account_id, ['user_id'])]);
        return response()->json($this->responseService->searchByProjectAndAccount($project_id, $account_id));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreResponseRequest $request
     * @return JsonResponse
     */
    public function store(StoreResponseRequest $request)
    {
        $this->authorize('create', Response::class);
        $result = $this->responseService->storeResponse($request->getFormData());
        if ($this->isBadRequestResponse($result)) {
            return response()->json($result, 400);
        }
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
        $this->authorize('view', $this->responseService->getResponseOnlyAccountAndProject($id));
        return response()->json($this->responseService->findResponse($id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateResponseRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(UpdateResponseRequest $request, $id)
    {
        $this->authorize('update', $this->responseService->getResponseOnlyAccount($id));
        $result = $this->responseService->updateResponse($request->getFormData(), $id);
        if ($this->isBadRequestResponse($result)) {
            return response()->json($result, 400);
        }
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
        $this->authorize('delete', $this->responseService->getResponseOnlyAccount($id));
        $this->responseService->destroyResponse($id);
        return response()->json([], 204);
    }
}
