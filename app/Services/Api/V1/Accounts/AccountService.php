<?php


namespace App\Services\Api\V1\Accounts;


use App\Models\Account;
use App\Services\Api\V1\Accounts\Resources\AccountResource;
use App\Services\Api\V1\Accounts\Resources\AccountsResource;
use App\Traits\CanWrapInData;
use Illuminate\Database\Eloquent\Builder;

class AccountService
{
    use CanWrapInData;

    public function searchAccounts(array $params)
    {
        $params = $this->extractParams($params);
        $queryBuilder = $this->queryBuilder();
        $queryBuilder = $this->filterQuery($queryBuilder, $params);
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

    protected function extractParams(array $params)
    {
        return $params;
    }

    protected function filterQuery(Builder $queryBuilder, array $params)
    {
        return $queryBuilder;
    }
}
