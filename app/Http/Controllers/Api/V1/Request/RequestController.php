<?php

namespace App\Http\Controllers\Api\V1\Request;

use App\Http\Controllers\Api\V1\Request\FormRequests\CancelRequestRequest;
use App\Http\Controllers\Api\V1\Request\FormRequests\StoreRequestRequest;
use App\Http\Controllers\Api\V1\Request\FormRequests\UpdateRequestRequest;
use App\Http\Controllers\Controller;
use App\Models\Request;
use App\Services\Api\V1\Requests\RequestService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request as HttpRequest;

class RequestController extends Controller
{
    protected $requestService;

    public function __construct(RequestService $requestService)
    {
        $this->requestService = $requestService;
    }

    /**
     * Display a listing of the resource.
     *
     * @param HttpRequest $request
     * @return JsonResponse
     */
    public function index(HttpRequest $request)
    {
        $this->authorize('viewAny', Request::class);
        return response()->json($this->requestService->searchRequests($request->get('q')));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreRequestRequest $request
     * @return JsonResponse
     */
    public function store(StoreRequestRequest $request)
    {
        $this->authorize('create', Request::class);
        $result = $this->requestService->storeRequest($request->getFormData());
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
    public function show(int $id)
    {
        $this->authorize('view', $this->requestService->getRequestOnlyUserId($id));
        return response()->json($this->requestService->findRequest($id));
    }

    /**
     * @param CancelRequestRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function cancel(CancelRequestRequest $request, int $id)
    {
        $this->authorize('cancel', Request::class);
        $this->requestService->cancelRequest($id, $request->get('fail_msg'));
        return response()->json([], 204);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateRequestRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(UpdateRequestRequest $updateRequest, int $id)
    {
        $this->authorize('update', $this->requestService->getRequestOnlyUserId($id));
        $result = $this->requestService->updateRequest($updateRequest->getFormData(), $id);
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
    public function destroy(int $id)
    {
        $this->authorize('delete', $this->requestService->getRequestOnlyUserId($id));
        $this->requestService->destroyRequest($id);
        return response()->json([], 204);
    }
}
