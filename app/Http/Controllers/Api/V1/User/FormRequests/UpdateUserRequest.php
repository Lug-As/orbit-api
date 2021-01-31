<?php


namespace App\Http\Controllers\Api\V1\User\FormRequests;


use App\Http\Requests\AppFormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends AppFormRequest
{
    public function rules()
    {
        return [
            'name' => ['nullable', 'string', 'max:150'],
            'phone' => ['nullable', 'string', 'size:10'],
            'email' => [
                'nullable', 'string', 'email', 'max:255',
                Rule::unique('users')->ignore($this->user()->id),
            ],
        ];
    }
}
