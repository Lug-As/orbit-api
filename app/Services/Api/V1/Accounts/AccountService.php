<?php


namespace App\Services\Api\V1\Accounts;


use App\Models\Account;
use App\Models\ImageAccount;
use App\Services\Api\V1\Accounts\Handlers\QueryFilterHandler;
use App\Services\Api\V1\Accounts\Resources\AccountResource;
use App\Services\Api\V1\Accounts\Resources\AccountsResource;
use App\Services\Api\V1\Accounts\Resources\AccountWithGalleryResource;
use App\Services\Api\V1\Files\FileService;
use App\Services\Api\V1\TikTokApi\TikTokApiManager;
use App\Traits\CanWrapInData;
use Illuminate\Database\Eloquent\Builder;

class AccountService
{
    use CanWrapInData;

    protected $filterHandler;
    protected $tikTokApiManager;
    protected $fileService;

    public function __construct(
        QueryFilterHandler $filterHandler,
        TikTokApiManager $tikTokApiManager,
        FileService $fileService
    )
    {
        $this->filterHandler = $filterHandler;
        $this->tikTokApiManager = $tikTokApiManager;
        $this->fileService = $fileService;
    }

    public function searchAccounts(?array $params = null)
    {
        $queryBuilder = $this->filterHandler->filter($this->queryBuilder(), $params);
        return AccountsResource::make($queryBuilder->paginate(10));
    }

    public function findAccount(int $id)
    {
        $account = $this->queryBuilder()->with('images')->findOrFail($id);
        return $this->wrapInData(AccountWithGalleryResource::make($account));
    }

    public function updateAccount(array $data, int $id)
    {
        $account = Account::findOrFail($id);
        $account->update($data);
        if (isset($data['topics'])) {
            $account->topics()->sync($data['topics']);
        }
        if (isset($data['ages'])) {
            $account->ages()->sync($data['ages']);
        }
        if (isset($data['ad_types'])) {
            $account->ad_types()->sync($this->transformAdTypes($data['ad_types']));
        }
        if (isset($data['image'])) {
            $account->image = $this->fileService->handle($data['image']);
            $account->save();
        }
        if (isset($data['gallery'])) {
            foreach ($data['gallery'] as $gallery_image) {
                $src = $this->fileService->handle($gallery_image);
                ImageAccount::create([
                    'src' => $src,
                    'account_id' => $account->id,
                ]);
            }
        }
        return $this->wrapInData(AccountWithGalleryResource::make($account));
    }

    public function destroyAccount(int $id)
    {
        $account = Account::find($id);
        if ($account) {
            return $account->delete();
        }
        return true;
    }

    public function refreshAccount($id)
    {
        $account = Account::findOrFail($id);
        $info = $this->tikTokApiManager->loadAccountInfo($account->title);
        if ($info) {
            $account->followers = $info->followers;
            $account->likes = $info->likes;
            $account->save();
        }
        return $this->wrapInData(AccountResource::make($account));
    }

    public function forceDestroyAccount($id): void
    {
        Account::withTrashed()->findOrFail($id)->forceDelete();
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

    protected function transformAdTypes(array $ad_types): array
    {
        $out = [];
        foreach ($ad_types as $ad_type) {
            if (isset($ad_type['price'])) {
                $out[$ad_type['id']] = [
                    'price' => $ad_type['price'],
                ];
            } else {
                $out[] = $ad_type['id'];
            }
        }
        return $out;
    }
}
