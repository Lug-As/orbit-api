<?php


namespace App\Services\Api\V1\Requests;


use App\Models\Request;
use App\Services\Api\V1\Requests\Resources\RequestResource;
use App\Services\Api\V1\Requests\Resources\RequestsResource;
use Illuminate\Database\Eloquent\Builder;

class RequestService
{
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
        return $this->wrapInData(Request::create($data));
    }

    public function updateRequest(array $data)
    {
        return $this->wrapInData(Request::update($data));
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

    protected function wrapInData($rawData)
    {
        return [
            'data' => $rawData
        ];
    }
}
