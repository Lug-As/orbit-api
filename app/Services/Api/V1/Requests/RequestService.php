<?php


namespace App\Services\Api\V1\Requests;


use App\Models\Account;
use App\Models\Request;
use App\Notifications\AccountCreated;
use App\Resources\BadRequestResource;
use App\Services\Api\V1\Accounts\Resources\AccountWithGalleryResource;
use App\Services\Api\V1\AdTypes\Transformer\AdTypesTransformer;
use App\Services\Api\V1\Files\FileService;
use App\Services\Api\V1\Requests\Resources\RequestsResource;
use App\Services\Api\V1\Requests\Resources\RequestResource;
use App\Services\Api\V1\Telegram\TelegramBot;
use App\Services\Api\V1\TikTokApi\TikTokApiManager;
use App\Traits\BadRequestErrorsGetable;
use App\Traits\CanWrapInData;
use DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Str;
use File;
use Auth;

class RequestService
{
    use CanWrapInData, BadRequestErrorsGetable;

    protected const ERROR_CODE_DUPLICATE_ACCOUNT_NAME = 1;
    protected const ERROR_CODE_DUPLICATE_REQUEST_NAME = 2;

    protected $fileService;
    protected $tikTokApiManager;

    public function __construct(
        MessageBag $messageBag,
        FileService $fileService,
        TikTokApiManager $tikTokApiManager
    )
    {
        $this->messageBag = $messageBag;
        $this->fileService = $fileService;
        $this->tikTokApiManager = $tikTokApiManager;
    }

    public function searchRequests(?string $query)
    {
        $builder = $this->queryBuilder();
        if ($this->validQuery($query)) {
            $builder = $builder->where('name', 'LIKE', "%{$query}%");
        }
        return RequestsResource::make($builder->latest()->paginate(10));
    }

    public function searchUserRequests()
    {
        return RequestsResource::make(
            $this->queryBuilder()->where('user_id', Auth::id())->latest()->paginate(10)
        );
    }

    public function findRequest(int $id)
    {
        $request = $this->queryBuilder()->findOrFail($id);
        return $this->wrapInData(RequestResource::make($request));
    }

    public function cancelRequest($id, ?string $fail_msg)
    {
        $request = Request::findOrFail($id);
        $request->checked = true;
        $request->account_id = null;
        if ($fail_msg) {
            $request->fail_msg = $fail_msg;
        }
        return $request->save();
    }

    /**
     * @param $id
     * @return array
     */
    public function approveRequest($id)
    {
        $request = Request::findOrFail($id);
        if ($request->isNotApproved()) {
            /** @var Account $account */
            $account = DB::transaction(function () use ($request) {
                $account = Account::create([
                    'title' => $request->getRawName(),
                    'image' => $request->getRawImage(),
                    'about' => $request->about,
                    'user_id' => $request->user_id,
                    'region_id' => $request->region_id,
                ]);
                $account->ad_types()->sync($this->transformAdTypesFromModels($request->ad_types));
                $account->topics()->sync($request->topics()->allRelatedIds());
                $account->ages()->sync($request->ages()->allRelatedIds());
                $request->checked = true;
                $request->account_id = $account->id;
                $request->save();
                return $account;
            }, 2);
            $account->notify((new AccountCreated)->locale('ru'));
            $info = $this->tikTokApiManager->loadAccountInfo($account->title);
            if ($info) {
                $account->followers = $info->followers;
                $account->likes = $info->likes;
                $account->save();
            }
        } else {
            $account = $request->account;
        }
        return $this->wrapInData(AccountWithGalleryResource::make($account));
    }

    /**
     * @param $id
     * @param array $data
     * @return Request|BadRequestResource
     */
    public function resendRequest($id, array $data)
    {
        $request = Request::findOrFail($id);
        if ($this->checkRequestIsCanceled($request)) {
            $result = $this->updateRequest($request->id, $data);
            if ($result instanceof BadRequestResource) {
                return $result;
            }
            $request->checked = false;
            $request->fail_msg = null;
            $request->save();
        } else {
            return $this->getErrorMessages();
        }
        return $request;
    }

    /**
     * @param array $data
     * @return BadRequestResource|array
     */
    public function storeRequest(array $data)
    {
        $data['name'] = Str::lower($data['name']);
        if (!$this->checkRequestName($data['name'])) {
            return $this->getErrorMessages();
        }
        $data['user_id'] = Auth::id();
        $request = DB::transaction(function () use ($data) {
            $request = Request::create($data);
            $request->topics()->sync($data['topics']);
            $request->ad_types()->sync($data['ad_types']);
            if (isset($data['ages'])) {
                $request->ages()->sync($data['ages']);
            }
            $request->image = $this->fileService->upload($data['image']);
            $request->save();
            return $request;
        }, 2);
        TelegramBot::notifyAdminAboutRequest($request);
        return $this->wrapInData(RequestResource::make($request));
    }

    /**
     * @param array $data
     * @param int $id
     * @return BadRequestResource|array
     */
    public function updateRequest($id, array $data)
    {
        $request = Request::findOrFail($id);
        if (isset($data['name'])) {
            $data['name'] = Str::lower($data['name']);
            if (!$this->checkRequestName($data['name'], $request->id)) {
                return $this->getErrorMessages();
            }
        }
        if (isset($data['topics'])) {
            $request->topics()->sync($data['topics']);
        }
        if (isset($data['ages'])) {
            $request->ages()->sync($data['ages']);
        }
        if (isset($data['ad_types'])) {
            $request->ad_types()->sync($data['ad_types']);
        }
        if (isset($data['image'])) {
            if ($request->image) {
                $this->fileService->delete($request->getRawImage());
            }
            $data['image'] = $this->fileService->upload($data['image']);
        }
        $request->update($data);
        return $this->wrapInData(RequestResource::make($request));
    }

    public function searchOwnCanceledRequest()
    {
        $requests = $this->queryBuilder()
            ->where('checked', true)
            ->whereNull('account_id')
            ->paginate(10);
        return RequestsResource::make($requests);
    }

    public function searchOwnApprovedRequest()
    {
        $requests = $this->queryBuilder()
            ->where('checked', true)
            ->whereNotNull('account_id')
            ->paginate(10);
        return RequestsResource::make($requests);
    }

    public function destroyRequest(int $id)
    {
        $request = Request::find($id);
        if ($request) {
            if ($request->image && $request->isNotApproved()) {
                $this->fileService->delete($request->getRawImage());
            }
            return $request->delete();
        }
        return true;
    }

    /**
     * @param int $id
     * @return Request|null
     */
    public function getRequestOnlyUserId($id): ?Request
    {
        return Request::findOrFail($id, ['user_id']);
    }

    /**
     * Return builder with model's relations
     *
     * @return Builder
     */
    protected function queryBuilder(): Builder
    {
        return Request::with(['user', 'ad_types', 'topics', 'account', 'region.country', 'ages']);
    }

    /**
     * @param \Illuminate\Database\Eloquent\Collection $ad_types
     * @return array
     */
    protected function transformAdTypesFromModels($ad_types): array
    {
        $ad_types = $ad_types->map(function ($item) {
            $item->price = $item->pivot->price;
            return $item;
        })->toArray();
        return AdTypesTransformer::transform($ad_types);
    }

    /**
     * @param string $name
     * @param int|null $except
     * @return bool
     */
    protected function checkRequestName(string $name, ?int $except = null): bool
    {
        return $this->checkAccountTitle($name)
            && $this->checkUserRequestName($name, $except);
    }

    protected function checkUserRequestName(string $name, ?int $except = null): bool
    {
        $user_id = Auth::id();
        $queryBuilder = Request::whereUserId($user_id)
            ->where('name', $name);
        if ($except !== null) {
            $queryBuilder = $queryBuilder->where('id', '<>', $except);
        }
        $check = !boolval($queryBuilder->count());
        if (!$check) {
            $this->messageBag->add('name', 'Request with current name has already been taken by you.');
            $this->messageBag->add('user_error_code', self::ERROR_CODE_DUPLICATE_REQUEST_NAME);
        }
        return $check;
    }

    protected function checkAccountTitle(string $name): bool
    {
        $check = !boolval(Account::whereTitle($name)
            ->count());
        if (!$check) {
            $this->messageBag->add('name', 'Account with this name already exists.');
            $this->messageBag->add('user_error_code', self::ERROR_CODE_DUPLICATE_ACCOUNT_NAME);
        }
        return $check;
    }

    protected function validQuery(?string $query)
    {
        return Str::length($query) < 24;
    }

    protected function checkRequestIsCanceled(Request $request)
    {
        $check = $request->isCanceled();
        if (!$check) {
            $this->messageBag->add('request', 'You can resend only canceled request.');
        }
        return $check;
    }
}
