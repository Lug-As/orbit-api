<?php


namespace App\Services\Api\V1\Requests;


use App\Models\Account;
use App\Models\Request;
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

    public function storeRequest(array $data)
    {
        if (!$this->checkUserRequestName($data['name'])) {
            return $this->messageBag->add('name', 'Request with current name has already been taken by you.');
        }
        if (!$this->checkAccountName($data['name'])) {
            return $this->messageBag->add('name', 'The name has already been taken.');
        }
        $data['user_id'] = 1;
//        $data['user_id'] = Auth::id();
        $request = Request::create($data);
        $request->topics()->sync($data['topics']);
        return $this->wrapInData(RequestResource::make($request));
    }

    public function updateRequest(array $data, int $id)
    {
        $request = Request::findOrFail($id);
        $request->update($data);
        $request->topics()->sync($data['topics']);
        return $this->wrapInData(RequestResource::make($request));
    }

    public function destroyRequest(int $id)
    {
        return Request::findOrFail($id)->delete();
    }

    /**
     * Return builder with Request's relations
     *
     * @return Builder
     */
    protected function requestBuilder(): Builder
    {
        return Request::with('user', 'ad_types', 'topics');
    }

    protected function checkUserRequestName(string $name): bool
    {
        $user_id = 1;
//        $user_id = Auth::id();
        $count = Request::whereUserId($user_id)
            ->where('name', $name)
            ->count();
        return !$count;
    }

    protected function checkAccountName(string $name): bool
    {
        $count = Account::whereName($name)
            ->count();
        return !$count;
    }
}
