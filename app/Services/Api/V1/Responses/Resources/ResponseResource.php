<?php


namespace App\Services\Api\V1\Responses\Resources;


use App\Services\Api\V1\Accounts\Resources\AccountResource;
use App\Services\Api\V1\Projects\Resources\ProjectResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ResponseResource extends JsonResource
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'text' => $this->text,
            'account' => AccountResource::make($this->account),
            'project' => ProjectResource::make($this->project),
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
