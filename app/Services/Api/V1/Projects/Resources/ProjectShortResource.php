<?php


namespace App\Services\Api\V1\Projects\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class ProjectShortResource extends JsonResource
{
    public function toArray($request)
    {
        /** @var self|\App\Models\Project $this */
        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }
}
