<?php

namespace App\Http\Controllers\Api\V1\Request;

use App\Http\Controllers\Api\V1\Request\FormRequests\CancelRequestRequest;
use App\Http\Controllers\Api\V1\Request\FormRequests\StoreRequestRequest;
use App\Http\Controllers\Api\V1\Request\FormRequests\UpdateRequestRequest;
use App\Http\Controllers\Controller;
use App\Services\Api\V1\Requests\RequestService;
use Illuminate\Http\Response;

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
        if (!is_array($result)) {
            return response()->json($result, 422);
        }
        return response()->json($result, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show(int $id)
    {
        return response()->json($this->requestService->findRequest($id));
    }

    /**
     * @param CancelRequestRequest $request
     * @param int $id
     * @return Response
     */
    public function cancel(CancelRequestRequest $request, int $id)
    {
        $this->requestService->cancelRequest($id, $request->get('fail_msg'));
        return response('', 204);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateRequestRequest $request
     * @param int $id
     * @return mixed
     */
    public function update(UpdateRequestRequest $request, int $id)
    {
        $result = $this->requestService->updateRequest($request->getFormData(), $id);
        if (!is_array($result)) {
            return response()->json($result, 422);
        }
        return response()->json($result);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy(int $id)
    {
        $this->requestService->destroyRequest($id);
        return response()->json([], 204);
    }
}
