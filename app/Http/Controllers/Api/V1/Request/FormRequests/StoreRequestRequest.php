<?php


namespace App\Http\Controllers\Api\V1\Request\FormRequests;


use App\Http\Requests\AppFormRequest;
use App\Services\Api\V1\AdTypes\Transformer\AdTypesTransformer;

class StoreRequestRequest extends AppFormRequest
{
    public function rules()
    {
        return [
            'name' => ['bail', 'required', 'string', 'max:24'],
            'image' => ['required', 'file', 'mimetypes:image/jpeg,image/jpg,image/png', 'max:5000'],
            'about' => ['nullable', 'string', 'max:1000'],
            'region_id' => ['nullable', 'integer', 'exists:regions,id'],
            'topics' => ['required', 'array'],
            'topics.*' => ['integer', 'distinct', 'exists:topics,id'],
            'ages' => ['nullable', 'array'],
            'ages.*' => ['integer', 'exists:ages,id'],
            'ad_types' => ['required', 'array'],
            'ad_types.*' => ['array'],
            'ad_types.*.id' => ['required', 'integer', 'distinct', 'exists:ad_types,id'],
            'ad_types.*.price' => ['nullable', 'integer', 'max:9999999'],
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param \Illuminate\Validation\Validator $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($validator->validated()) {
                $this->transformAdTypes($validator);
            }
        });
    }

    protected function transformAdTypes($validator)
    {
        /** @var \Illuminate\Validation\Validator $validator */
        $data = $validator->getData();
        if (isset($data['ad_types'])) {
            $data['ad_types'] = AdTypesTransformer::transform($data['ad_types']);
            $validator->setData($data);
        }
    }
}
