<?php


namespace App\Http\Controllers\Api\V1\Project\FormRequests;


use App\Http\Requests\AppFormRequest;
use App\Services\Api\V1\AdTypes\Transformer\AdTypesTransformer;

class StoreProjectRequest extends AppFormRequest
{
    protected $rules = [
        'name' => ['required', 'max:250'],
        'text' => ['required', 'max:3000'],
        'budget' => ['required',  'integer', 'max:999999'],
        'followers_from' => ['nullable', 'integer'],
        'followers_to' => ['nullable', 'integer'],
        'ad_types' => ['nullable', 'array'],
        'ad_types.*' => ['integer', 'distinct', 'exists:ad_types,id'],
    ];
}
