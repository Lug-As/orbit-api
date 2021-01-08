<?php


namespace App\Http\Controllers\Api\V1\Offer\FormRequests;


class UpdateOfferRequest extends StoreOfferRequest
{
    protected $rules = [
        'text' => ['nullable', 'string', 'max:2000'],
    ];
}
