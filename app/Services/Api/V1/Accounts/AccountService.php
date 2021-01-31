<?php


namespace App\Services\Api\V1\Accounts;


use App\Models\Account;
use App\Models\ImageAccount;
use App\Services\Api\V1\Accounts\Handlers\QueryFilterHandler;
use App\Services\Api\V1\Accounts\Resources\AccountResource;
use App\Services\Api\V1\Accounts\Resources\AccountsResource;
use App\Services\Api\V1\Accounts\Resources\AccountWithGalleryResource;
use App\Services\Api\V1\AdTypes\Transformer\AdTypesTransformer;
use App\Services\Api\V1\Files\FileService;
use App\Services\Api\V1\ImageAccounts\ImageAccountService;
use App\Services\Api\V1\TikTokApi\TikTokApiManager;
use App\Traits\BadRequestErrorsGetable;
use App\Traits\CanWrapInData;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\MessageBag;
use File;

class AccountService
{
    use CanWrapInData, BadRequestErrorsGetable;

    protected $filterHandler;
    protected $tikTokApiManager;
    protected $fileService;
    protected $imageAccountService;

    public function __construct(
        QueryFilterHandler $filterHandler,
        TikTokApiManager $tikTokApiManager,
        FileService $fileService,
        MessageBag $messageBag,
        ImageAccountService $imageAccountService
    )
    {
        $this->filterHandler = $filterHandler;
        $this->tikTokApiManager = $tikTokApiManager;
        $this->fileService = $fileService;
        $this->messageBag = $messageBag;
        $this->imageAccountService = $imageAccountService;
    }

    public function searchAccounts(?array $params = null)
    {
        $queryBuilder = $this->filterHandler->filter($this->queryBuilder(), $params);
        return AccountsResource::make($queryBuilder->paginate(10));
    }

    public function findAccount($id)
    {
        $account = $this->queryBuilder()->with('images')->findOrFail($id);
        return $this->wrapInData(AccountWithGalleryResource::make($account));
    }

    public function updateAccount(array $data, $id)
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
            $account->ad_types()->sync(AdTypesTransformer::transform($data['ad_types']));
        }
        if (isset($data['image'])) {
            $account->image = $this->fileService->upload($data['image']);
            $account->save();
        }
        if (isset($data['gallery'])) {
            if ($this->checkGalleryImagesCount($account->id, count($data['gallery']))) {
                foreach ($data['gallery'] as $gallery_image) {
                    $src = $this->fileService->upload($gallery_image);
                    ImageAccount::create([
                        'src' => $src,
                        'account_id' => $account->id,
                    ]);
                }
            } else {
                return $this->getErrorMessages();
            }
        }
        return $this->wrapInData(AccountWithGalleryResource::make($account));
    }

    public function destroyAccount($id)
    {
        $account = Account::find($id);
        if ($account) {
            if ($account->image) {
                $this->fileService->delete($account->getRawImage());
            }
            if ($account->images) {
                foreach ($account->images as $image) {
                    $this->imageAccountService->destroyImageAccount($image);
                }
            }
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

    /**
     * Return builder with model's relations
     *
     * @return Builder
     */
    protected function queryBuilder(): Builder
    {
        return Account::with(['user', 'ad_types', 'topics', 'region', 'ages']);
    }

    protected function checkGalleryImagesCount($account_id, $with_count = 0)
    {
        $check = (ImageAccount::whereAccountId($account_id)->count() + $with_count) < 10;
        if (!$check) {
            $this->messageBag->add('gallery', 'You can upload max 10 images to gallery.');
        }
        return $check;
    }
}
