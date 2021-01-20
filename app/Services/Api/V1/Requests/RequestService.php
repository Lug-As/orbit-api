<?php


namespace App\Services\Api\V1\Requests;


use App\Models\Account;
use App\Models\Request;
use App\Resources\BadRequestResource;
use App\Services\Api\V1\Accounts\Resources\AccountResource;
use App\Services\Api\V1\Files\FileService;
use App\Services\Api\V1\Requests\Resources\RequestResource;
use App\Services\Api\V1\Requests\Resources\RequestsResource;
use App\Services\Api\V1\TikTokApi\TikTokApiManager;
use App\Traits\BadRequestErrorsGetable;
use App\Traits\CanWrapInData;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Str;
use Auth;

class RequestService
{
    use CanWrapInData, BadRequestErrorsGetable;

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
        return RequestsResource::make($builder->paginate(10));
    }

    public function findRequest(int $id)
    {
        return $this->wrapInData(RequestResource::make($this->queryBuilder()->findOrFail($id)));
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
            $account = Account::create([
                'name' => $request->getRawName(),
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
            $info = $this->tikTokApiManager->loadAccountInfo($account->name);
            if ($info) {
                $account->followers = $info->followers;
                $account->likes = $info->likes;
                $account->save();
            }
        } else {
            $account = $request->account;
        }
        return $this->wrapInData(AccountResource::make($account));
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
        $request = Request::create($data);
        $request->topics()->sync($data['topics']);
        $request->ad_types()->sync($this->transformAdTypes($data['ad_types']));
        if (isset($data['image'])) {
            $request->image = $this->fileService->handle($data['image']);
            $request->save();
        }
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
        }
        if (isset($data['name']) and !$this->checkRequestName($data['name'], $request->id)) {
            return $this->getErrorMessages();
        }
        $request->update($data);
        if (isset($data['topics'])) {
            $request->topics()->sync($data['topics']);
        }
        if (isset($data['ad_types'])) {
            $request->ad_types()->sync($this->transformAdTypes($data['ad_types']));
        }
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
        return Request::whereId($id)->delete();
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
        return Request::with(['user', 'ad_types', 'topics', 'account', 'region', 'ages']);
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

    /**
     * @param \Illuminate\Database\Eloquent\Collection $ad_types
     * @return array
     */
    protected function transformAdTypesFromModels($ad_types): array
    {
        /** @var \Illuminate\Database\Eloquent\Collection $ad_types */
        return $this->transformAdTypes($ad_types->map(function ($item) {
            $item->price = $item->pivot->price;
            return $item;
        })->toArray());
    }

    /**
     * @param string $name
     * @param int|null $except
     * @return bool
     */
    protected function checkRequestName(string $name, ?int $except = null): bool
    {
        return $this->checkAccountName($name)
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
        }
        return $check;
    }

    protected function checkAccountName(string $name): bool
    {
        $check = !boolval(Account::whereName($name)
            ->count());
        if (!$check) {
            $this->messageBag->add('name', 'Account with this name already exists.');
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
