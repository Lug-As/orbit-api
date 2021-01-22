<?php


namespace App\Http\Controllers\Api\V1\Account\FormRequests;


use App\Http\Controllers\Api\V1\Request\FormRequests\UpdateRequestRequest;

class UpdateAccountRequest extends UpdateRequestRequest
{
    public function rules()
    {
        $parent_rules = parent::rules();
        unset($parent_rules['name']);
        return $parent_rules;
    }
}
