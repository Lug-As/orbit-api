<?php


namespace App\Services\Api\V1\Requests;


use App\Models\Account;
use App\Models\AdType;
use App\Models\Request;
use App\Resources\ValidationErrorsResource;
use App\Services\Api\V1\Requests\Resources\RequestResource;
use App\Services\Api\V1\Requests\Resources\RequestsResource;
use App\Traits\CanWrapInData;
use Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\MessageBag;

class RequestService
{
    use CanWrapInData;

    protected $messageBag;

    public function __construct(MessageBag $messageBag)
    {
        $this->messageBag = $messageBag;
    }

    public function searchRequests()
    {
        return RequestsResource::make($this->requestBuilder()->paginate(10));
    }

    public function findRequest(int $id)
    {
        return $this->wrapInData(RequestResource::make($this->requestBuilder()->findOrFail($id)));
    }

    /**
     * @param array $data
     * @return ValidationErrorsResource|array
     */
    public function storeRequest(array $data)
    {
        if (!$this->checkRequestName($data['name'])) {
            return $this->getErrorMessages();
        }
        $data['user_id'] = 1;
//        $data['user_id'] = Auth::id();
        $request = Request::create($data);
        $request->topics()->sync($data['topics']);
        $request->ad_types()->sync($this->transformAdTypes($data['ad_types']));
        return $this->wrapInData(RequestResource::make($request));
    }

    /**
     * @param array $data
     * @param int $id
     * @return ValidationErrorsResource|array
     */
    public function updateRequest(array $data, int $id)
    {
        $request = Request::findOrFail($id);
        if (isset($data['name']) and !$this->checkRequestName($data['name'], $id)) {
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

    public function destroyRequest(int $id)
    {
        return Request::whereId($id)->delete();
    }

    /**
     * Return builder with Request model's relations
     *
     * @return Builder
     */
    protected function requestBuilder(): Builder
    {
        return Request::with('user', 'ad_types', 'topics');
    }

    protected function transformAdTypes(array $ad_types)
    {
        $out = [];
        foreach ($ad_types as $ad_type) {
            $out[$ad_type['id']] = [
                'price' => $ad_type['price']
            ];
        }
        return $out;
    }

    /**
     * @param string $name
     * @param int|null $except
     * @return ValidationErrorsResource|bool
     */
    protected function checkRequestName(string $name, ?int $except = null)
    {
        if (!$this->checkAccountName($name)) {
            $this->messageBag->add('name', 'Account with this name already exists.');
            return false;
        }
        if (!$this->checkUserRequestName($name, $except)) {
            $this->messageBag->add('name', 'Request with current name has already been taken by you.');
            return false;
        }
        return true;
    }

    protected function getErrorMessages()
    {
        return ValidationErrorsResource::make($this->messageBag->messages());
    }

    protected function checkUserRequestName(string $name, ?int $except = null): bool
    {
        $user_id = 1;
//        $user_id = Auth::id();
        $queryBuilder = Request::whereUserId($user_id)
            ->where('name', $name);
        if ($except !== null) {
            $queryBuilder = $queryBuilder->where('id', '<>', $except);
        }
        $count = $queryBuilder->count();
        return !$count;
    }

    protected function checkAccountName(string $name): bool
    {
        $count = Account::whereName($name)
            ->count();
        return !$count;
    }
}
