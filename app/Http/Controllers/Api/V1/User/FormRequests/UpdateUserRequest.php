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
            'telegram' => ['nullable', 'string', 'max:32'],
            'image' => ['nullable', 'file', 'mimetypes:image/jpeg,image/jpg,image/png', 'max:5000'],
            'email' => [
                'nullable', 'string', 'email', 'max:255',
                Rule::unique('users')->ignore($this->user()->id),
            ],
        ];
    }
}
