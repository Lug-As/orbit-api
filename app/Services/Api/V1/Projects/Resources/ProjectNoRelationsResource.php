<?php


namespace App\Services\Api\V1\Projects\Resources;


class ProjectNoRelationsResource extends ProjectShortResource
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        /** @var self|\App\Models\Project $this */
        return array_merge(parent::toArray($request), [
            'budget' => $this->budget,
            'text' => $this->text,
            'followers_from' => $this->followers_from,
            'followers_to' => $this->followers_to,
            'created_at' => $this->created_at->toDateTimeString(),
        ]);
    }
}
