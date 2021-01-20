<?php

namespace App\Http\Controllers\Api\V1\Account;

use App\Http\Controllers\Api\V1\Account\FormRequests\UpdateAccountRequest;
use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Services\Api\V1\Accounts\AccountService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    protected $accountService;

    public function __construct(AccountService $accountService)
    {
        $this->accountService = $accountService;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Account::class);
        return response()->json($this->accountService->searchAccounts($request->input()));
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show($id)
    {
        $this->authorize('view', $this->accountService->getAccountOnlyUserId($id));
        return response()->json($this->accountService->findAccount($id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateAccountRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(UpdateAccountRequest $request, $id)
    {
        $this->authorize('update', $this->accountService->getAccountOnlyUserId($id));
        return response()->json($this->accountService->updateAccount($request->getFormData(), $id));
    }

    public function refreshInfo($id)
    {
        $this->authorize('refreshInfo', Account::class);
        return response()->json($this->accountService->refreshAccountInfo($id));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
//        $this->authorize('delete', $this->accountService->getAccountOnlyUserId($id));
//        $this->accountService->destroyAccount($id);
        $this->authorize('forceDelete', $this->accountService->getAccountOnlyUserId($id, true));
        $this->accountService->forceDestroyAccount($id);
        return response()->json([], 204);
    }

//    public function forceDestroy($id) {
//        $this->authorize('forceDelete', $this->accountService->getAccountOnlyUserId($id, true));
//        $this->accountService->forceDestroyAccount($id);
//        return response()->json([], 204);
//    }
//    public function restore($id) {
//        $this->authorize('restore', $this->accountService->getAccountOnlyUserId($id, true));
//        $this->accountService->restoreAccount($id);
//        return response()->json([], 204);
//    }
//    public function ownTrashed() {
//        $this->authorize('ownTrashed', Account::class);
//        return response()->json($this->accountService->searchTrashedAccounts());
//    }
}
