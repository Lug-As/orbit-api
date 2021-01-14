<?php


namespace App\Http\Controllers\Api\V1\Project\FormRequests;


use App\Http\Requests\AppFormRequest;

class StoreProjectRequest extends AppFormRequest
{
    protected $rules = [
        'name' => ['required', 'max:250'],
        'text' => ['required', 'max:5000'],
        'budget' => ['required', 'min:100', 'min:999999'],
        'user_id' => ['required', 'integer', 'exists:users,id'],
    ];
}
