<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreHomeSliderRequest extends FormRequest
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
            'name' => 'required',
            'banner' => 'required',
            'mobile_banner' => 'required',
            'link_type' => 'required',
            'status' => 'required',
            'link' => 'nullable|required_if:link_type,external',
            'link_ref_id' => 'nullable|required_if:link_type,product,category',
        ];
    }

    public function messages()
    {
        return [
            'link.required_if' => "Please enter a valid link",
            'link_ref_id.required_if' => "Please enter an option",
        ];
    }
}
