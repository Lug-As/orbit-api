<?php


namespace App\Http\Controllers\Api\V1\Project\FormRequests;


use App\Http\Requests\AppFormRequest;

class StoreProjectRequest extends AppFormRequest
{
    protected $rules = [
        'name' => ['required', 'max:250'],
        'text' => ['required', 'max:5000'],
        'budget' => ['required',  'integer', 'min:100', 'max:999999'],
        'followers_from' => ['nullable', 'integer', 'min:10000'],
        'followers_to' => ['nullable', 'integer', 'min:10000'],
        'user_id' => ['required', 'integer', 'exists:users,id'],
    ];
}
