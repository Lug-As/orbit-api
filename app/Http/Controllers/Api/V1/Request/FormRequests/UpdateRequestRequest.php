<?php


namespace App\Http\Controllers\Api\V1\Request\FormRequests;


class UpdateRequestRequest extends StoreRequestRequest
{
    public function rules()
    {
        return array_merge(parent::rules(), [
            'name' => ['nullable', 'string', 'max:24'],
            'topics' => ['nullable', 'array'],
            'ad_types' => ['nullable', 'array'],
        ]);
    }
}
