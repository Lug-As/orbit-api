<?php

namespace App\Http\Controllers\Api\V1\Offer;

use App\Http\Controllers\Api\V1\Offer\FormRequests\StoreOfferRequest;
use App\Http\Controllers\Api\V1\Offer\FormRequests\UpdateOfferRequest;
use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Offer;
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
        $this->authorize('viewAny', Offer::class);
        return response()->json($this->offerService->searchOffers());
    }

    /**
     * @return JsonResponse
     */
    public function ownIndex()
    {
        $this->authorize('viewOwn', Offer::class);
        return response()->json($this->offerService->searchUserOffers());
    }

    /**
     * @param int $accountId
     * @return JsonResponse
     */
    public function getByAccount(int $accountId)
    {
        $this->authorize('viewByAccount', Account::findOrFail($accountId, ['user_id']));
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
        $this->authorize('create', Offer::class);
        $result = $this->offerService->storeOffer($request->getFormData());
        if ($this->isBadRequestResponse($result)) {
            return response()->json($result, 400);
        }
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
        $this->authorize('view', $this->offerService->getOfferOnlyUserIdAndAccount($id));
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
        $this->authorize('update', $this->offerService->getOfferOnlyUserId($id));
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
        $this->authorize('delete', $this->offerService->getOfferOnlyUserId($id));
        $this->offerService->destroyOffer($id);
        return response()->json([], 204);
    }
}
