<?php


namespace App\Services\Api\V1\Accounts;


use App\Models\Account;
use App\Models\ImageAccount;
use App\Services\Api\V1\Accounts\Handlers\AccountQueryHandler;
use App\Services\Api\V1\Accounts\Resources\AccountInListResource;
use App\Services\Api\V1\Accounts\Resources\AccountResource;
use App\Services\Api\V1\Accounts\Resources\AccountsResource;
use App\Services\Api\V1\Accounts\Resources\AccountWithGalleryResource;
use App\Services\Api\V1\Files\FileService;
use App\Services\Api\V1\ImageAccounts\ImageAccountService;
use App\Services\Api\V1\TikTokApi\TikTokApiManager;
use App\Traits\BadRequestErrorsGetable;
use App\Traits\CanWrapInData;
use Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\MessageBag;

class AccountService
{
    use CanWrapInData, BadRequestErrorsGetable;

    protected $accountFilterHandler;
    protected $tikTokApiManager;
    protected $fileService;
    protected $imageAccountService;

    public function __construct(
        AccountQueryHandler $accountFilterHandler,
        TikTokApiManager $tikTokApiManager,
        FileService $fileService,
        MessageBag $messageBag,
        ImageAccountService $imageAccountService
    )
    {
        $this->accountFilterHandler = $accountFilterHandler;
        $this->tikTokApiManager = $tikTokApiManager;
        $this->fileService = $fileService;
        $this->messageBag = $messageBag;
        $this->imageAccountService = $imageAccountService;
    }

    public function searchAccounts(?array $params = null)
    {
        $queryBuilder = $this->accountFilterHandler->handle($this->queryBuilder(), $params);
        return AccountsResource::make($queryBuilder->paginate(9));
    }

    public function findAccount($id)
    {
        $account = $this->queryBuilder()->with('images')->findOrFail($id);
        return $this->wrapInData(AccountWithGalleryResource::make($account));
    }

    public function searchUserAccounts()
    {
        return $this->wrapInData(AccountInListResource::collection(
                $this->queryBuilder()->where('user_id', Auth::id())->latest()->get()
            ));
    }

    public function updateAccount(array $data, $id)
    {
        $account = Account::findOrFail($id);
        if (isset($data['topics'])) {
            $account->topics()->sync($data['topics']);
        }
        if (isset($data['ages'])) {
            $account->ages()->sync($data['ages']);
        }
        if (isset($data['ad_types'])) {
            $account->ad_types()->sync($data['ad_types']);
        }
        if (isset($data['image'])) {
            if ($account->image) {
                $this->fileService->delete($account->getRawImage());
            }
            $data['image'] = $this->fileService->upload($data['image']);
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
        $account->update($data);
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
            return $account->forceDelete();
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
        return Account::with(['user', 'ad_types', 'topics', 'region.country', 'ages']);
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
