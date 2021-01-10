<?php


namespace App\Services\Api\V1\Responses;


use App\Models\Account;
use App\Models\Response;
use App\Services\Api\V1\Projects\Resources\ResponsesResource;
use App\Services\Api\V1\Responses\Resources\ResponseResource;
use App\Traits\BadRequestErrorsGetable;
use App\Traits\CanWrapInData;
use Auth;
use Illuminate\Database\Eloquent\Builder;

class ResponseService
{
    use CanWrapInData, BadRequestErrorsGetable;

    public function searchResponses()
    {
        return ResponsesResource::make($this->requestBuilder()->paginate(10));
    }

    public function findResponse(int $id)
    {
        return $this->wrapInData(ResponseResource::make($this->requestBuilder()->findOrFail($id)));
    }

    public function storeResponse(array $data)
    {
        if (!$this->checkResponseAccount($data['account_id'])
            || !$this->checkResponseSingular($data['project_id'], $data['account_id'])) {
            return $this->getErrorMessages();
        }
        $data['user_id'] = Auth::id();
        $offer = Response::create($data);
        return $this->wrapInData(ResponseResource::make($offer));
    }

    public function updateResponse(array $data, int $id)
    {
        $offer = Response::findOrFail($id);
        if (isset($data['account_id']) and !$this->checkResponseAccount($data['account_id'])) {
            return $this->getErrorMessages();
        }
        $offer->update($data);
        return $this->wrapInData(ResponseResource::make($offer));
    }

    public function destroyResponse(int $id)
    {
        return Response::whereId($id)->delete();
    }

    public function searchByAccount(int $accountId)
    {
        return ResponsesResource::make($this->requestBuilder()
            ->where('account_id', $accountId)
            ->paginate(10));
    }

    public function searchByProject(int $projectId)
    {
        return ResponsesResource::make($this->requestBuilder()
            ->where('project_id', $projectId)
            ->paginate(10));
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function getResponseOnlyAccountAndProject(int $id)
    {
        return Response::with(['account.user_id', 'project.user_id'])->findOrFail($id, ['id']);
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function getResponseOnlyAccount(int $id)
    {
        return Response::with('account.user_id')->findOrFail($id, ['id']);
    }

    /**
     * Return builder with Request model's relations
     *
     * @return Builder
     */
    protected function requestBuilder(): Builder
    {
        return Response::with('project', 'account');
    }

    protected function checkResponseAccount(int $accountId)
    {
        $userId = Auth::id();
        $check = boolval(Account::whereId($accountId)
            ->where('user_id', $userId)
            ->count());
        if (!$check) {
            $this->messageBag->add('account', 'You should use your account for response.');
        }
        return $check;
    }

    protected function checkResponseSingular(int $projectId, int $accountId)
    {
        $check = !boolval(Response::whereProjectId($projectId)
            ->where('account_id', $accountId)
            ->count());
        if (!$check) {
            $this->messageBag->add('account', 'You can create only one response for project by one account.');
        }
        return $check;
    }
}
