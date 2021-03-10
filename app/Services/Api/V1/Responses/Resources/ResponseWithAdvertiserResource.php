<?php


namespace App\Services\Api\V1\Responses\Resources;


use App\Services\Api\V1\Accounts\Resources\AccountWithContactsResource;
use App\Services\Api\V1\Projects\Resources\ProjectWithUserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ResponseWithAdvertiserResource extends JsonResource
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        /** @var self|\App\Models\Response $this */
        return [
            'id' => $this->id,
            'text' => $this->text,
            'account' => AccountWithContactsResource::make($this->account),
            'project' => ProjectWithUserResource::make($this->project),
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
