<?php


namespace App\Services\Api\V1\Responses;


use App\Models\Account;
use App\Models\Project;
use App\Models\Response;
use App\Services\Api\V1\Responses\Resources\ResponsesResource;
use App\Services\Api\V1\Responses\Resources\ResponseResource;
use App\Services\Api\V1\Responses\Resources\ResponseWithAdvertiserResource;
use App\Traits\BadRequestErrorsGetable;
use App\Traits\CanWrapInData;
use Auth;
use Illuminate\Database\Eloquent\Builder;

class ResponseService
{
    use CanWrapInData, BadRequestErrorsGetable;

    public function searchResponses()
    {
        return ResponsesResource::make($this->queryBuilder()->paginate(10));
    }

    public function findResponse(int $id)
    {
        return $this->wrapInData(ResponseResource::make($this->queryBuilder()->findOrFail($id)));
    }

    public function findResponseWithAdvertiser($id)
    {
        return $this->wrapInData(ResponseWithAdvertiserResource::make($this->queryBuilder()->with('project.user')->findOrFail($id)));
    }

    public function storeResponse(array $data)
    {
        if (!$this->checkResponseAccount($data['account_id'])
            || !$this->checkCreatingResponse($data['project_id'], $data['account_id'])) {
            return $this->getErrorMessages();
        }
        $data['user_id'] = Auth::id();
        $response = Response::create($data);
        return $this->wrapInData(ResponseResource::make($response));
    }

    public function updateResponse(array $data, int $id)
    {
        $response = Response::findOrFail($id);
        if (isset($data['account_id']) and !$this->checkResponseAccount($data['account_id'])) {
            return $this->getErrorMessages();
        }
        $response->update($data);
        return $this->wrapInData(ResponseResource::make($response));
    }

    public function destroyResponse($id)
    {
        return Response::whereId($id)->delete();
    }

    public function searchByAccount($accountId)
    {
        return ResponsesResource::make($this->queryBuilder()
            ->where('account_id', $accountId)
            ->paginate(10));
    }

    public function searchByProject($projectId)
    {
        return ResponsesResource::make($this->queryBuilder()
            ->where('project_id', $projectId)
            ->paginate(10));
    }

    public function searchByProjectAndAccount($projectId, $accountId)
    {
        $result = $this->queryBuilder()
            ->where('project_id', $projectId)
            ->where('account_id', $accountId)
            ->first();
        if ($result) {
            return $this->wrapInData(ResponseResource::make($result));
        }
        return $this->wrapInData(json_encode(new \StdClass()));
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function getResponseOnlyAccountAndProject($id)
    {
        return Response::with(['account.user_id', 'project.user_id'])->findOrFail($id, ['id']);
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function getResponseOnlyAccount($id)
    {
        return Response::with('account.user_id')->findOrFail($id, ['id']);
    }

    /**
     * Return builder with model's relations
     *
     * @return Builder
     */
    protected function queryBuilder(): Builder
    {
        return Response::with(['project', 'account']);
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

    protected function checkCreatingResponse(int $projectId, int $accountId)
    {
        return $this->checkResponseSingular($projectId, $accountId)
            && $this->checkResponseProject($projectId, $accountId);
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

    protected function checkResponseProject(int $projectId, int $accountId)
    {
        $project = Project::find($projectId, ['user_id']);
        $account = Account::find($accountId, ['user_id']);
        $check = $project->user_id !== $account->user_id;
        if (!$check) {
            $this->messageBag->add('project', 'You cannot leave response for your projects.');
        }
        return $check;
    }
}
