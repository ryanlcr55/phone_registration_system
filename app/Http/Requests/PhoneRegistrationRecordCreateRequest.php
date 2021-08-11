<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PhoneRegistrationRecordCreateRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'time' => 'bail|required|string',
            'from' => 'bail|required|string',
            'text' => 'bail|required|string',
        ];
    }

}
