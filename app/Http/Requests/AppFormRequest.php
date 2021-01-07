<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;

class AppFormRequest extends FormRequest
{
    protected $rules;

    public function rules()
    {
        return $this->rules;
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    public function getFormData()
    {
        $data = $this->validated();
        $data = Arr::except($data, [
            '_token', '_method',
        ]);
        return $data;
    }
}
