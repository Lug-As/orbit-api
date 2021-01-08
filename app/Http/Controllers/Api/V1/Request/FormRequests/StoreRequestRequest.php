<?php


namespace App\Http\Controllers\Api\V1\Request\FormRequests;


use App\Http\Requests\AppFormRequest;

class StoreRequestRequest extends AppFormRequest
{
    protected $rules = [
        'name' => ['required', 'string'],
        'image' => ['nullable', 'file', 'mimetypes:image/jpeg,image/png,image/bmp', 'max:5000'],
        'topics' => ['required', 'array'],
        'topics.*' => ['integer', 'exists:topics,id'],
        'ad_types' => ['required', 'array'],
        'ad_types.*.id' => ['integer', 'exists:ad_types,id'],
        'ad_types.*.price' => ['integer'],
    ];
}
