<?php

namespace App\Http\Controllers\Api\V1\Request;

use App\Http\Controllers\Api\V1\Request\FormRequests\StoreRequestRequest;
use App\Http\Controllers\Api\V1\Request\FormRequests\UpdateRequestRequest;
use App\Http\Controllers\Controller;
use App\Services\Api\V1\Requests\RequestService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\MessageBag;

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
     * @return Response
     */
    public function index()
    {
        return response()->json($this->requestService->searchRequests());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreRequestRequest $request
     * @return Response|mixed
     */
    public function store(StoreRequestRequest $request)
    {
        $result = $this->requestService->storeRequest($request->getFormData());
        if ($result instanceof MessageBag) {
            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => $result->messages()
            ], 422);
        }
        return $result;
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show(int $id)
    {
        return $this->requestService->findRequest($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateRequestRequest $request
     * @param int $id
     * @return Response
     */
    public function update(UpdateRequestRequest $request, int $id)
    {
        return $this->requestService->updateRequest($request->getFormData(), $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy(int $id)
    {
        return $this->requestService->destroyRequest($id);
    }
}
