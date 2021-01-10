<?php

namespace App\Http\Controllers\Api\V1\Offer;

use App\Http\Controllers\Api\V1\Offer\FormRequests\StoreOfferRequest;
use App\Http\Controllers\Api\V1\Offer\FormRequests\UpdateOfferRequest;
use App\Http\Controllers\Controller;
use App\Services\Api\V1\Offers\OfferService;
use Illuminate\Http\JsonResponse;

class OfferController extends Controller
{
    protected $offerService;

    public function __construct(OfferService $offerService)
    {
        $this->offerService = $offerService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        return response()->json($this->offerService->searchOffers());
    }

    public function getByUser(int $userId)
    {
        return response()->json($this->offerService->searchOffersByUserId($userId));
    }

    public function getByAccount(int $accountId)
    {
        return response()->json($this->offerService->searchOffersByAccountId($accountId));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreOfferRequest $request
     * @return JsonResponse
     */
    public function store(StoreOfferRequest $request)
    {
        $result = $this->offerService->storeOffer($request->getFormData());
        return response()->json($result,  201);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show($id)
    {
        return response()->json($this->offerService->findOffer($id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateOfferRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(UpdateOfferRequest $request, $id)
    {
        $result = $this->offerService->updateOffer($request->getFormData(), $id);
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
        $this->offerService->destroyOffer($id);
        return response()->json([], 204);
    }
}
