<?php


namespace App\Services\Api\V1\Projects\Resources;


use App\Services\Api\V1\AdTypes\Resources\AdTypeResource;
use App\Services\Api\V1\Regions\Resources\RegionResource;
use App\Services\Api\V1\Users\Resources\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectNoRelationsResource extends JsonResource
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        /** @var self|\App\Models\Project $this */
        return [
            'id' => $this->id,
            'text' => $this->text,
            'name' => $this->name,
            'budget' => $this->budget,
            'followers_from' => $this->followers_from,
            'followers_to' => $this->followers_to,
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
