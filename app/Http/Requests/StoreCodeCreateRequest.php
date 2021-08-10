<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCodeCreateRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'store_name' => 'bail|required|string',
            'lat' => ['required','regex:/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$/'],
            'lon' => ['required','regex:/^[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$/'],
        ];
    }
}
