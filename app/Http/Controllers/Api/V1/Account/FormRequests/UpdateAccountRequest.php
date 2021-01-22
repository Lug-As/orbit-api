<?php


namespace App\Http\Controllers\Api\V1\Account\FormRequests;


use App\Http\Controllers\Api\V1\Request\FormRequests\UpdateRequestRequest;

class UpdateAccountRequest extends UpdateRequestRequest
{
    public function rules()
    {
        $parent_rules = parent::rules();
        unset($parent_rules['name']);
        return array_merge($parent_rules, [
            'gallery' => ['nullable', 'array', 'max:10'],
            'gallery.*' => ['required', 'file', 'mimetypes:image/jpeg,image/jpg,image/png', 'max:5000'],
        ]);
    }
}
