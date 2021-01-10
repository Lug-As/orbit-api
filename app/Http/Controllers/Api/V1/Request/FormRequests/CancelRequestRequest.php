<?php


namespace App\Http\Controllers\Api\V1\Request\FormRequests;


use App\Http\Requests\AppFormRequest;

class CancelRequestRequest extends AppFormRequest
{
    protected $rules = [
        'fail_msg' => ['nullable', 'string', 'max:250'],
    ];
}
