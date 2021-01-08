<?php

namespace App\Http\Controllers\Api\V1\Response;

use App\Http\Controllers\Api\V1\Response\FormRequests\StoreResponseRequest;
use App\Http\Controllers\Api\V1\Response\FormRequests\UpdateResponseRequest;
use App\Http\Controllers\Controller;
use App\Services\Api\V1\Responses\ResponseService;

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
     * @return mixed
     */
    public function index()
    {
        return response()->json($this->responseService->searchResponses());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreResponseRequest $request
     * @return mixed
     */
    public function store(StoreResponseRequest $request)
    {
        $result = $this->responseService->storeResponse($request->getFormData());
        if (!is_array($result)) {
            return response()->json($result, 422);
        }
        return response()->json($result, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return mixed
     */
    public function show($id)
    {
        return response()->json($this->responseService->findResponse($id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateResponseRequest $request
     * @param int $id
     * @return mixed
     */
    public function update(UpdateResponseRequest $request, $id)
    {
        $result = $this->responseService->updateResponse($request->getFormData(), $id);
        if (!is_array($result)) {
            return response()->json($result, 422);
        }
        return response()->json($result);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return mixed
     */
    public function destroy($id)
    {
        $this->responseService->destroyResponse($id);
        return response()->json([], 204);
    }
}
