<?php


namespace App\Http\Controllers\Api\V1\Offer\FormRequests;


use App\Http\Requests\AppFormRequest;

class StoreOfferRequest extends AppFormRequest
{
    protected $rules = [
        'text' => ['required', 'string', 'max:2000'],
        'account_id' => ['required', 'integer', 'exists:accounts,id'],
    ];
}
