<?php


namespace App\Http\Controllers\Api\V1\Project\FormRequests;


class UpdateProjectRequest extends StoreProjectRequest
{
    public function rules()
    {
        return array_merge(parent::rules(), [
            'name' => ['nullable', 'max:250'],
            'text' => ['nullable', 'max:5000'],
            'budget' => ['nullable', 'min:100', 'min:999999'],
            'user_id' => ['nullable', 'integer', 'exists:users,id'],
        ]);
    }
}
