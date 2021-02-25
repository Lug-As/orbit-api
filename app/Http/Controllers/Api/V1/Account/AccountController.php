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
        $this->middleware(['auth', 'verified'])
            ->except(['index', 'show', 'refresh']);
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request)
    {
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
        $result = $this->accountService->updateAccount($request->getFormData(), $id);
        if ($this->isBadRequestResponse($result)) {
            return response()->json($result, 400);
        }
        return response()->json($result);
    }

    public function refresh($id)
    {
        return response()->json($this->accountService->refreshAccount($id));
    }

    public function own()
    {
        return response()->json($this->accountService->searchUserAccounts());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        $this->authorize('delete', $this->accountService->getAccountOnlyUserId($id));
        $this->accountService->destroyAccount($id);
        return response()->json([], 204);
    }
}
