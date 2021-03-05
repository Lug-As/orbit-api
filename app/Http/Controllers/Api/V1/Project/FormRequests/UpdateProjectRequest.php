<?php


namespace App\Http\Controllers\Api\V1\Project\FormRequests;


class UpdateProjectRequest extends StoreProjectRequest
{
    public function rules()
    {
        return array_merge(parent::rules(), [
            'name' => ['nullable', 'max:250'],
            'text' => ['nullable', 'max:3000'],
            'budget' => ['nullable', 'max:999999'],
        ]);
    }
}
