<?php


namespace App\Resources;


use Illuminate\Http\Resources\Json\ResourceCollection;

class BadRequestResource extends ResourceCollection
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'message' => 'The given data was invalid.',
            'errors' => $this->collection,
        ];
    }
}
