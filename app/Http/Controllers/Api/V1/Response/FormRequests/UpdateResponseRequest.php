<?php


namespace App\Http\Controllers\Api\V1\Response\FormRequests;


class UpdateResponseRequest extends StoreResponseRequest
{
    protected $rules = [
        'text' => ['nullable', 'string', 'max:2000'],
        'account_id' => ['nullable', 'integer', 'exists:accounts,id'],
    ];
}
