<?php


namespace App\Services\Api\V1\Accounts;


use App\Models\Account;
use App\Services\Api\V1\Accounts\Handlers\QueryFilterHandler;
use App\Services\Api\V1\Accounts\Resources\AccountResource;
use App\Services\Api\V1\Accounts\Resources\AccountsResource;
use App\Services\Api\V1\TikTokApi\TikTokApiManager;
use App\Traits\CanWrapInData;
use Illuminate\Database\Eloquent\Builder;

class AccountService
{
    use CanWrapInData;

    protected $filterHandler;
    protected $tikTokApiManager;

    public function __construct(
        QueryFilterHandler $filterHandler,
        TikTokApiManager $tikTokApiManager
    )
    {
        $this->filterHandler = $filterHandler;
        $this->tikTokApiManager = $tikTokApiManager;
    }

    public function searchAccounts(?array $params = null)
    {
        $queryBuilder = $this->filterHandler->filter($this->queryBuilder(), $params);
        return AccountsResource::make($queryBuilder->paginate(10));
    }

    public function findAccount(int $id)
    {
        return $this->wrapInData(AccountResource::make($this->queryBuilder()->findOrFail($id)));
    }

    public function updateAccount(array $data, int $id)
    {
        $account = Account::findOrFail($id);
        $account->update($data);
        return $this->wrapInData(AccountResource::make($account));
    }

    public function destroyAccount(int $id): void
    {
        $account = Account::find($id);
        if ($account) {
            $account->delete();
        }
    }

    public function refreshAccountInfo($id)
    {
        $account = Account::findOrFail($id);
        $info = $this->tikTokApiManager->loadAccountInfo($account->name);
        if ($info) {
            $account->followers = $info->followers;
            $account->likes = $info->likes;
            $account->save();
        }
        return $this->wrapInData(AccountResource::make($account));
    }

    public function forceDestroyAccount($id): void
    {
        Account::withTrashed()->findOrFail($id)->forceDelete($id);
    }

    public function restoreAccount($id): void
    {
        Account::withTrashed()->findOrFail($id)->restore();
    }

    /**
     * Return builder with model's relations
     *
     * @return Builder
     */
    protected function queryBuilder(): Builder
    {
        return Account::with(['user', 'ad_types', 'topics', 'region', 'ages']);
    }

    /**
     * @param int $id
     * @return Account|null
     */
    public function getAccountOnlyUserId($id, $withTrashed = false): ?Account
    {
        if ($withTrashed) {
            return Account::withTrashed()->findOrFail($id, ['user_id']);
        }
        return Account::findOrFail($id, ['user_id']);
    }

    public function searchTrashedAccounts()
    {
        return AccountsResource::make($this->queryBuilder()->onlyTrashed()->paginate(10));
    }
}
