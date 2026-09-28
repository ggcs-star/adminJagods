<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BannerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'target_type' => [
                'required',
                'in:restaurant,category',
            ],

            'target_id' => [
                'required',
                'integer',
            ],

            'name' => [
                'nullable',
                'string',
                'max:255',
            ],

            'description' => [
                'nullable',
                'string',
                'max:255',
            ],

            'url' => [
                'nullable',
                'string',
            ],

            'status' => [
                'required',
                'numeric',
            ],

            'image' => $this->banner
                ? ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:3072']
                : ['required', 'image', 'mimes:jpeg,png,jpg', 'max:3072'],
        ];
    }

    public function attributes()
    {
        return [
            'target_type' => 'target type',
            'target_id'   => 'target',
            'name'        => trans('validation.attributes.name'),
            'image'       => trans('validation.attributes.image'),
            'description' => trans('validation.attributes.description'),
            'url'         => trans('validation.attributes.url'),
            'status'      => trans('validation.attributes.status'),
        ];
    }
}
