<?php


namespace App\Http\Controllers\Api\V1\Request\FormRequests;


use App\Http\Requests\AppFormRequest;

class StoreRequestRequest extends AppFormRequest
{
    protected $rules = [
        'name' => 'required|string',
        'image' => 'nullable|file|mimetypes:image/jpeg,image/png,image/bmp|max:5000',
        'topics' => 'nullable|array',
        'topics.*' => 'integer|exists:topics,id'
    ];
}
