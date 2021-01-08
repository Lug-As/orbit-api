<?php


namespace App\Services\Api\V1\Projects\Resources;


use App\Services\Api\V1\Users\Resources\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
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
            'name' => $this->name,
            'budget' => $this->budget,
            'user' => UserResource::make($this->user),
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
