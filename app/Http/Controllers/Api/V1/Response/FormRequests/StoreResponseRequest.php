<?php


namespace App\Http\Controllers\Api\V1\Response\FormRequests;


use App\Http\Requests\AppFormRequest;

class StoreResponseRequest extends AppFormRequest
{
    protected $rules = [
        'text' => ['required', 'string', 'max:2000'],
        'account_id' => ['required', 'integer', 'exists:accounts,id'],
        'project_id' => ['required', 'integer', 'exists:projects,id'],
    ];
}
