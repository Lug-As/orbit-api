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

    public function destroyAccount(int $id)
    {
        return Account::whereId($id)->delete();
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
}
