<?php


namespace App\Services\Api\V1\Accounts;


use App\Models\Account;
use App\Services\Api\V1\Accounts\Handlers\QueryFilterHandler;
use App\Services\Api\V1\Accounts\Resources\AccountResource;
use App\Services\Api\V1\Accounts\Resources\AccountsResource;
use App\Traits\CanWrapInData;
use Illuminate\Database\Eloquent\Builder;

class AccountService
{
    use CanWrapInData;

    protected $filterHandler;

    public function __construct(QueryFilterHandler $filterHandler)
    {
        $this->filterHandler = $filterHandler;
    }

    public function searchAccounts(array $params)
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
        $offer = Account::findOrFail($id);
        $offer->update($data);
        return $this->wrapInData(AccountResource::make($offer));
    }

// https://www.tiktok.com/node/share/user/@danya_milokhin?user_agent=

    public function destroyAccount(int $id): void
    {
        $account = Account::find($id);
        if ($account) {
            $account->delete();
        }
    }

    public function forceDestroyAccount(int $id): void
    {
        $account = Account::find($id);
        if ($account and method_exists($account, 'forceDelete')) {
            $account->forceDelete();
        }
    }

    public function restoreAccount(int $id): void
    {
        $account = Account::find($id);
        if ($account and method_exists($account, 'restore')) {
            $account->restore();
        }
    }

    /**
     * Return builder with model's relations
     *
     * @return Builder
     */
    protected function queryBuilder(): Builder
    {
        return Account::with(['user', 'ad_types', 'topics']);
    }

    /**
     * @param int $id
     * @return Account|null
     */
    public function getAccountOnlyUserId(int $id): ?Account
    {
        return Account::findOrFail($id, ['user_id']);
    }
}
